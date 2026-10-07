<?php
set_time_limit(0);
error_reporting(0);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL & ~E_WARNING);
error_reporting(E_ERROR);

include_once("telegrambot.php");
$print='<html>
<head>
<style type=text/css>
option:first-child{
    font-weight:bold;
}</style>';

date_default_timezone_set('Asia/Kolkata');

$cookie=trim(file_get_contents("flip.txt"))."; solved_captcha=1771789267-25029-79986200-765a7110efbb6343cdce29e7c46e80da8391c9c5f91cc5bbb732a797a6cccd92;";
if(stristr($cookie,"ud="))
	$cookie=preg_replace("#ud=(.*?); #","",$cookie);
if(stristr($cookie,"ud="))
	$cookie=preg_replace("#ud=(.*?);#","",$cookie);
if(stristr($cookie,"ud="))
	$cookie=preg_replace("#ud=(.*?)$#","",$cookie);


preg_match("#SN=(.*?);#",$cookie,$mat);
$sn=$mat[1];
//$heads[]="SN: ".$sn;

preg_match("#at=(.*?);#",$cookie,$mat);
$at=$mat[1];
//$heads[]="at: ".$at;

preg_match("#S=(.*?);#",$cookie,$mat);
$sc=$mat[1];

$securetoken='dla3fDJ/9MW4YLajotSdZt1Ngm8gtQtVPe8NRQfpNGskHNUSl2gLy39yMsaDDnevuX+dtYoUbEKb7eKuZLT26A==';
$securetoken='';

$deviceid = 'NWU4M2YwN2E5YTY5Yjc5MTUxNjJiNTA2NmE1YmQ2YzdmODBjZTk1ODkzYjFiYzU2NjZmNGMwYWNiYmJlZTk0NjUzMDU0NjliNDk5M2JkNmI2NzNjNWU4ZTE0NTg0N2MyNGJhMmJiYThkZTUyNDM1Mjc4MTAwOWVkZjAwMjZmNTAxNjMyZTNkNjQ4YmYwOTQyOGU3YzZjMjI5Y2JkODUyNDQ1ZGRjNWNjOWRlNjphNWM0NzFkMjdhMjg0NWIxOThlN2FiMDYwMWE3OTIwZA==';

function myFilter($string) {
  return strpos($string, 'Cookie') === false;
}

function array_map_assoc( $callback , $array ){
  $r = array();
  foreach ($array as $key=>$value)
    $r[$key] = $callback($key,$value);
  return $r;
}

function updatecookie($html, $cookiestring, $url1)
{
	$parts = explode("\r\n\r\nHTTP/", $html);
	$parts = (count($parts) > 1 ? 'HTTP/' : '').array_pop($parts);
	list($headers, $body) = explode("\r\n\r\n", $parts, 2);
	
	if(!stristr($url1,"flipkart.com"))
	{
		return array(trim($body), trim($cookiestring));
	}
	
	$cookies = array();
	$cookieexplode=explode(";", $cookiestring);
	
	foreach($cookieexplode as $value)
	{
		parse_str(trim($value),$cookie);
		$cookies = array_merge($cookies,  $cookie); 
	}
	
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $html, $matches);
	foreach($matches[1] as $item) {
		parse_str($item, $cookie);
		$cookies = array_merge($cookies, $cookie);
	}
	$cookiestring1 = implode('; ',array_map_assoc(function($k,$v){return "$k=$v";},$cookies));
	
	if($cookiestring!=$cookiestring1)
	{
		//echo "$cookiestring<hr>$cookiestring1";
		$fh=fopen("flip.txt","w+");
		fwrite($fh,$cookiestring1);
		fclose($fh);
	}
	
	return array(trim($body), trim($cookiestring1));
}

function curl_call($method, $url, $headers, $data="", $onlyget=false)
{
	global $cookie;
	if(stristr(php_uname(),"Windows"))
	{
		global $proxy;
		//$proxy="127.0.0.1:8888";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36"); 
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		if($method=="POST")
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_REFERER, "https://www.flipkart.com/");
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html=curl_exec($ch);
		$code=curl_getinfo($ch, CURLINFO_HTTP_CODE);
		list($html, $cookie) = updatecookie($html, $cookie, $url);
		return array($html, $code);
	}
	else
	{
		$cmd='';
		if($method=="POST")
		{
			$cmd.='../curl_chrome_android -i -w "\nRESPONSE_CODE->%{http_code}" -X POST \''.$url.'\' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
			$cmd.='-d \''.$data.'\'';
		}
		else if($onlyget==true)
		{
			$cmd.='../curl_chrome_android -i -L -w "\nRESPONSE_CODE->%{http_code}==>%{url_effective}" \''.$url.'\' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
			//echo $cmd;
			$output = shell_exec($cmd);
			$data=explode("\nRESPONSE_CODE->",$output);
			$data1=explode("==>",$data[1]);
			return array($data[0],$data1[0],$data1[1]);
		}
		else
		{
			$cmd.='../curl_chrome_android -i -w "\nRESPONSE_CODE->%{http_code}" \''.$url.'\' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
		}
		
		$cmd.=' --compressed';
		//echo "<p><code>$url<br>$data</code></p>";
		$output = shell_exec($cmd);
		$data=explode("\nRESPONSE_CODE->",$output);
		$html=$data[0];
		list($html, $cookie) = updatecookie($html, $cookie, $url);
		return array($html,$data[1]);
	}
}


function atexpired($cookie,$at,$sn,$sc)
{
	$heads=[];
	/*
	$heads[]='X-MARKETPLACE-CONTEXT: FLIPKART';
	$heads[]='X-AR-AVAILABILITY: NOT_PRESENT';
	$heads[]='X-MULTIWIDGET-VERSION: 7.14.9';
	$heads[]='x-atlas-versions: 20892000/2420100';
	$heads[]='Network-Type: wifi';
	$heads[]='x-unified-atlas-version: 2420100.-1.-1.7014009.2008092.1002050';
	$heads[]='X-DLS: true';*/
	//$heads[]='X-AppSession-ID: 1ea4df50-af48-48b0-a63e-748ee541f7cb_1770639848380';
	//$heads[]='X-Location-Info: {"la":29.4171146,"lo":76.9833567,"cl":true}';
	//$heads[]='Content-Type: application/json';
	$heads[]='User-Agent:  Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36';
	$heads[]='Cookie: '.$cookie;
	$heads[]='at: '.$at;
	//$heads[]='X-Visit-Id: 31144985973de35722ddc15de34334c2-1770643376657';
	//$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
	$heads[]='sn: '.$sn;
	$heads[]='Accept-Encoding: gzip';
	$heads[]='secureCookie: '.$sc;
	
	$url1="https://www.flipkart.com/account/orders/search?orderUnit_state=On_the_way%2CDelivered";
	//$post='{"eventId":"021e5faf-c06b-4cae-b671-6ed46390da6d","source":"bureau","deviceId":"31144985973de35722ddc15de34334c2"}';
	list($html, $status_code) = curl_call('GET',$url1,$heads);
	
	//$jss=json_decode($html,1);
	//$newat=$jss["SESSION"]["at"];
	//$newsn=$jss["SESSION"]["sn"];
	preg_match("SN=(.*?);",$html,$cookie);
	$newsn=$mat[1];

	preg_match("at=(.*?);",$html,$cookie);
	$newat=$mat[1];
	//echo $html;
	
	//var_dump($newsn);
	//var_dump($newat);

	return array($newat,$newsn);
}


if($_REQUEST["reset"]=="yes")
{
	$date=date("d/m/Y");
	file_put_contents('notdoagain.txt', $date."\r\n");
	die("reset done");
}

if($_REQUEST["submit"]=="cart")
{
	echo "<h2>Cart</h2><hr>";
	//$proxy="127.0.0.1:8888";
	$ch=curl_init();
	
		
		
		//$heads[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36';
		$heads[]='X-User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop';
		$heads[]='Accept: */*';
		$heads[]='Cookie: '.$cookie;
		$heads[]='Connection: keep-alive';
		$heads[]='Origin: https://www.flipkart.com';
		$heads[]='Content-Type: application/json';
		$heads[]='Accept-Language: en-US,en;q=0.8';
		
		$url1="https://www.flipkart.com/api/4/page/fetch";
		
		$post='{"pageUri":"/viewcart","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"networkSpeed":106,"trackingContext":{"context":{"eVar51":"reco_factBasedRecommendation/backInStock_hp","eVar61":"hp_reco_WHITELISTED_factBasedRecommendation/backInStock_Items+Back+in+Stock_LIST_productCard_cc_1_NA_view-all"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';
		
		list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
		
		if($status_code>400)
			die("<br><font color=red><b>Error or Logout ??</font><br>");
		
		$m=json_decode($html,1);
		$slots=$m["RESPONSE"]["slots"];
		$accmail=$m["SESSION"]["email"] . " " . $m["SESSION"]["flipkartFirstUser"];
		echo "<br>$accmail<br>";
		foreach($slots as $cart)
		{
			$lid=$pid="";
			$pp=$cart["widget"]["data"]["actions"][0]["action"]["params"];
			$lid=$pp["listingId"];
			$pid=$pp["productId"];
			if(empty($lid))
				continue;
			
			$post='{"actionRequestContext":{"pageNumber":1.0,"pageUri":"/viewcart?exploreMode=true&marketplace=FLIPKART","type":"CART_REMOVE","items":[{"productId":"'.$pid.'","listingId":"'.$lid.'"}],"marketPlaces":[]}}';
			
			$url1="https://www.flipkart.com/api/1/action/view";
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			$m=json_decode($html,1);
			$text=$m["RESPONSE"]["actionResponseContext"]["actionMessages"][0]["text"];
			echo $text."<br><br>";
		}
die;
}
else if($_REQUEST["submit"])
{
		$ch=curl_init();

		$heads=array();
		$get_contents=file_get_contents("notdoagain.txt");
		$acc_contents=file_get_contents("combinedacc.txt");
		echo "<p>".$_SERVER['PHP_SELF']."<br>$acc_contents<br>".$_REQUEST["name"]."</p>";
		$date=date("d/m/Y");

		$tries=$_REQUEST["i"];
		$maxprice=isset($_REQUEST["maxprice"]) ? $_REQUEST["maxprice"] : "999999";
		$qttytty=$qt=$_REQUEST["qt"];
		if(!empty($_REQUEST["url"]))
		{
			$url=$_REQUEST["url"];
			if(stristr($url,"pid="))
			{
				preg_match("#pid=(.*?)(&|$)#",$url,$mat);
				$opid=$mat[1];
			}
			if(stristr($url,"lid="))
			{
				preg_match("#lid=(.*?)(&|$)#",$url,$mat);
				$itemid=$mat[1];
			}
			else if(stristr($url,"listingid="))
			{
				preg_match("#listingid=(.*?)(&|$)#",$url,$mat);
				$itemid=$mat[1];
			}
			else if(stristr($url,"/pr?"))
			{
				$myjson=json_decode(masterflipkart($url),1);
				var_dump($myjson);
				$itemid = array_keys($myjson)[0];
			}
			else
			{
				$myjson=json_decode(flipkart($url),1);
				if(count($myjson)>1)
				{
				ksort($myjson);
				}
				$itemid = $myjson[array_keys($myjson)[0]];
				var_dump($myjson);
			}
		}
		else
		{
			$itemid=$_REQUEST["itemid"];
			$opid=$_REQUEST["pid"];
		}


		if(stristr($itemid,"http"))
		{
			preg_match("#pid=(.*?)&#",$itemid,$mat);
			$opid=$mat[1];
			preg_match("#lid=(.*?)&#",$itemid,$mat);
			$itemid=$mat[1];
		}

		$biscart=$_REQUEST["biscart"];
		$proxy=$_REQUEST["proxy"];
		
		if(empty($itemid))
			die("EMpty IteM Id");

		$time=round(microtime(true) * 1000);
		$onetry = 0;
		
		checkstop();
		
		eval(base64_decode("aWYoIXN0cmlzdHIoZ2V0Y3dkKCksImF1dG8iKSAmJiAoc3RyaXN0cigkaXRlbWlkLCJNT0IiKSB8fCBzdHJpc3RyKCRpdGVtaWQsIlRBQiIpIHx8IHN0cmlzdHIoJGl0ZW1pZCwiQ09NIikpICYmICRfUkVRVUVTVFsiYXBwIl0hPSJ5ZXMiICYmICRfUkVRVUVTVFsiYmlzIl0hPSJ5ZXMiKQ0Kc2xlZXAoNSk7"));
		
onemoretry:

		
		$heads = array_filter($heads, 'myFilter');
		
		$heads=[];
		$heads[]='X-MARKETPLACE-CONTEXT: FLIPKART';
		$heads[]='X-AR-AVAILABILITY: NOT_PRESENT';
		$heads[]='X-MULTIWIDGET-VERSION: 7.14.9';
		$heads[]='x-atlas-versions: 20892000/2420100';
		$heads[]='Network-Type: wifi';
		$heads[]='x-unified-atlas-version: 2420100.-1.-1.7014009.2008092.1002050';
		$heads[]='X-DLS: true';
		//$heads[]='X-AppSession-ID: 1ea4df50-af48-48b0-a63e-748ee541f7cb_1770639848380';
		//$heads[]='X-Location-Info: {"la":29.4171146,"lo":76.9833567,"cl":true}';
		$heads[]='Content-Type: application/json';
		$heads[]='User-Agent: okhttp/4.9.2';
		$heads[]='Cookie: '.$cookie;
		$heads[]='at: '.$at;
		//$heads[]='X-Visit-Id: 31144985973de35722ddc15de34334c2-1770643376657';
		$heads[]='secureToken: '.$securetoken;
		$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
		$heads[]='sn: '.$sn;
		$heads[]='Accept-Encoding: gzip';
		$heads[]='secureCookie: '.$sc;
		
		$incart=0;
		if(stristr($url,"gift-card") || stristr($itemid,"LSTGV"))
		{
			$url1="https://www.flipkart.com/api/5/checkout?loginFlow=false";
			$post='{"checkoutType":"EGV","egvRequests":[{"receiverEmailId":"vikaskumar615@gmail.com","receiverName":"vikas","message":"","listingId":"'.$itemid.'","productId":"'.$opid.'","confReceiverEmailId":"vikaskumar615@gmail.com"}]}';
		}
		else if($biscart=="yes")
		{
			echo "<h3>BIS in CART check</h3><hr>";
			$url1="https://www.flipkart.com/api/5/checkout?infoLevel=order_summary";
			$post='{"checkoutType":"PHYSICAL","cartRequest":{"pageType":"CartPage","cartContext":{"'.$itemid.'":{"assessmentContextId":null,"cashifyDiscountApplied":false,"exchangeContext":null,"exchangeContextId":null,"offerId":null,"parentContext":null,"parentProductContext":null,"payWithEMISelected":null,"previousQuantity":0,"productId":"'.$pid.'","quantity":'.$qt.',"reverseBuyingType":null,"selectedActions":null,"shopId":null,"shopListId":null,"shopListItemId":null,"superCoinSelected":null,"vulcanDiscountApplied":false}}}}';
		}
		else
		{
			$url1="https://www.flipkart.com/api/5/checkout?loginFlow=false";
			$post='{"cartRequest":{"cartContext":{"'.$itemid.'":{"quantity":'.$qt.'}}},"checkoutType":"PHYSICAL"}';
		}
		
		for($i=0;$i<$tries;$i++)
		{	
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			if(stristr($html,"AT expired") || $status_code=="429")
			{
				list($at,$sn)=atexpired($cookie,$at,$sn,$sc);
				$heads=[];
				$heads[]='X-MARKETPLACE-CONTEXT: FLIPKART';
				$heads[]='X-AR-AVAILABILITY: NOT_PRESENT';
				$heads[]='X-MULTIWIDGET-VERSION: 7.14.9';
				$heads[]='x-atlas-versions: 20892000/2420100';
				$heads[]='Network-Type: wifi';
				$heads[]='x-unified-atlas-version: 2420100.-1.-1.7014009.2008092.1002050';
				$heads[]='X-DLS: true';
				//$heads[]='X-AppSession-ID: 1ea4df50-af48-48b0-a63e-748ee541f7cb_1770639848380';
				//$heads[]='X-Location-Info: {"la":29.4171146,"lo":76.9833567,"cl":true}';
				$heads[]='Content-Type: application/json';
				$heads[]='User-Agent: okhttp/4.9.2';
				$heads[]='Cookie: '.$cookie;
				$heads[]='at: '.$at;
				//$heads[]='X-Visit-Id: 31144985973de35722ddc15de34334c2-1770643376657';
				$heads[]='secureToken: '.$securetoken;
				$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
				$heads[]='sn: '.$sn;
				$heads[]='Accept-Encoding: gzip';
				$heads[]='secureCookie: '.$sc;
				
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			}
			$requeststatus=$status_code;
			$lpghtml=$html;
			
			if(stristr($html,"\"flipkartAssured\":true"))
				$flipkartassured="F_ASSURED";
			else
				$flipkartassured="<strike>Not ASSURED</strike>";
			
			$jss=json_decode($html,1);
			$accmail=$jss["RESPONSE"]["accountInfo"]["emailId"] . " " . $jss["RESPONSE"]["accountInfo"]["smsNotifyNumber"];
			$pin_code=$jss["RESPONSE"]["addressData"]["pincode"];
			if(empty($accmail) || $accmail==" ")
				$accmail=$jss["SESSION"]["email"];

			if(!empty($accmail))
			{
				$fh=fopen("combinedacc.txt","w+");
				fwrite($fh,$accmail." ".$pin_code);
				fclose($fh);
			}

			
			$maintitle = $jss["RESPONSE"]["orderSummary"]["requestedStores"][0]["buyableStateItems"][0]["mainTitle"];
			$desc = $jss["RESPONSE"]["orderSummary"]["requestedStores"][0]["buyableStateItems"][0]["coSubTitle"];
			$pid = $jss["RESPONSE"]["orderSummary"]["requestedStores"][0]["buyableStateItems"][0]["productId"];
			//echo $pid," ",$opid;
			if(!empty($opid) && !empty($pid) && $pid!=$opid && $_REQUEST["server"]=="yes")
			{	
				die("<br><h2>in cart $pid != $opid</h2>");
			}
			//die("failure");
			
			if($maintitle == "")
			{
				preg_match_all('#"mainTitle":"(.*?)",#',$html,$mat);
				foreach($mat[1] as $va)
					$maintitle.=$va."\n";
			}
			
			if($_REQUEST["server"]!="yes" && stristr($html,"Item not available for purchase"))
			{
				echo "<meta http-equiv=refresh content=0></head><br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br><b><font color=red>".$html."</font></b><br>";
				die;
			}
			
			if(!empty($desc))
				$maintitle.=" ====> ".$desc;
			
			preg_match('#"discountPercent":(.*?),#',$html,$mat);
			$discountpercent=$mat[1];
			
			preg_match('#"grandTotal":(.*?),#',$html,$mat);
			$price=$mat[1];
			
			$qtty = $jss["RESPONSE"]["orderSummary"]["requestedStores"][0]["buyableStateItems"][0]["quantity"];
			if(empty($qtty))
				$qtty = $_REQUEST["qt"];
			//preg_match('#"quantity":(.*?)(,|})#',$html,$mat);
			//$qtty=$mat[1];
			
			preg_match('#"deliveryCharges":(.*?),#',$html,$mat);
			$dvs=$mat[1];		
			
			if(empty($maintitle))
			{
				preg_match('#"mainTitle":(.*?),#',$html,$mat);
				$maintitle=$mat[1];
			}

			if(stristr($maintitle,"panty"))
				die("<br><font color=red>$maintitle</font> found");		
			

			preg_match('#"sellerName":"(.*?)",#',$html,$mat);
			$seller=$mat[1];
			
			preg_match('#"sellerId":"(.*?)",#',$html,$mat);
			$sellerid=$mat[1];
			
			preg_match('#"category":"(.*?)",#',$html,$mat);
			$productcategory=$mat[1];
			
			if(!stristr($_SERVER['PHP_SELF'],"flip") && !stristr($_SERVER['PHP_SELF'],"mega") && !stristr($_SERVER['PHP_SELF'],"auto") && !empty($productcategory))
			{
				if(stristr($productcategory,"Mobile"))
					sleep(3);
			}
			
			preg_match('#"promiseDate":"(.*?)",#',$html,$mat);
			$promisedate=$mat[1];
			
			preg_match("#\"cartItemRefId\":\"(.*?)\"#",$html,$mat);
			$cartid=$mat[1];
			
			if($_REQUEST["server"]=="yes" && ($seller=="RetailNet" || $seller=="supercomnet" || $seller=="HSAtlastradeFashion" || $seller=="UNIVERSALPRODUCTSGROUP"))
			{
				if(!empty($cartid))
				{
					if($price<=50)
						$qttytty="10";
					else if($price<=150)
						$qttytty="7";
					else if($price<=3000)
						$qttytty="7";
					else
						$qttytty="2";
			
			$heads = array_filter($heads, 'myFilter');
			$heads[]='Cookie: '.$cookie;
					$url1="https://www.flipkart.com/api/1/action/view";
					
					$post='{"actionRequestContext":{"checkoutUpsertItemsRequest":[{"cartItemRefId":"'.$cartid.'","quantity":'.$qttytty.'}],"expressCoFlow":false,"pageNumber":1,"pageUri":"/viewcheckout?checkoutInitiated=true","type":"CHECKOUT_UPDATE_ITEM_QUANTITY"}}';
					
					list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
					
					$html222=$html;
					$requeststatus=$status_code;
					
					if(preg_match('#"finalPrice":(.*?),#',$html222,$mat))
					$price=$mat[1];
					
					if(preg_match('#"quantity":(.*?),#',$html222,$mat))
					$qtty=$mat[1];
				}
			}
			
			if($qtty == Null or $qtty == "" or $qtty == "null")
				$qtty = "1";
			
			if(($price/$qtty)>$maxprice)
			{
				echo "<meta http-equiv=refresh content=0></head><b><br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br><br><br><font color=maroon>Price is Not in The Range: <br><h4><font color=red>$price > $maxprice</font></h4>Quantity = $qtty</font></b><br><br>Seller = $seller<br>";
				die;
			}

			if(stristr($get_contents,$date) && $_REQUEST["filename"]!="bis" && $_REQUEST["bis"]!="yes")
			{	
				if(stristr($get_contents,$itemid) && $_REQUEST["server"]=="yes" && ($price>30 || $discountpercent<90))
				{
					telegram(urlencode($_SERVER['REQUEST_URI']."\n<b>$accmail \n Can't order again Today.\n $maintitle </b>\n ".$_REQUEST["url"]));
					die("Can't order again Today. http://".file_get_contents("https://yt-dl.org/ip").$_SERVER['REQUEST_URI']."?reset=yes");
				}
			}
			else
			{
				file_put_contents('notdoagain.txt', $date."\r\n");
			}

			if(stristr($html,"Unable to proceed with this item"))
			{
				preg_match('#"serviceabilityText":[\s]*"(.*?)",#',$html,$mat);
				echo "<meta http-equiv=refresh content=0></head><br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br><b><font color=red>".$mat[1]."</font></b><br>";
				die;
			}
			
			if(stristr($html,"checkout is not deliverable"))
			{
				//preg_match('#"serviceabilityText":[\s]*"(.*?)",#',$html,$mat);
				echo "<meta http-equiv=refresh content=0></head><br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br><b><font color=red>checkout is not deliverable</font></b><br>";
				die;
			}
			
			$actions=$jss["RESPONSE"]["actionDetails"]["actions"][0]["success"];
			if(stristr($html,"EMPTY_CHECKOUT_CART"))
				echo "<br><b><font color=red>Your checkout has no items.</font></b>";
			else if((stristr($html,'"errorCode":null') || $actions==true) && stristr($html,'address') && stristr($html,'cartItemRefId') && !stristr($html,"EMPTY_CHECKOUT_CART"))
			{
				//print("<b><font color=green>$html</font></b>");
				
				$print.=date("d/m/Y H:i:s")." => $accmail ($pin_code)<br></h4>Quantity = $qtty</font></b><br>Seller = $seller<br><br><b><font color=green>IN CART Price: $price Rs.</font></b>";
				$incart=1;
				break;
			}
			else if(stristr($html,"cart is full"))
			{
				telegram(urlencode($_SERVER['REQUEST_URI']."\n<b>$accmail \n \n URGENT: CART IS FULL. \n CART IS FULL.</b>\n".$_REQUEST["url"]));
				die("<br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br>cart full");
			}
			else if(stristr($html,"logged in"))
			{
				$fh=file_get_contents("messages.txt");
				if(!stristr($fh,"LOGGED OUT"))
				{
					telegram(urlencode("<b>".$_SERVER['REQUEST_URI']."\n".$acc_contents."\n \n URGENT: USER LOGGED OUT</b>"),true);
					$fh=fopen("messages.txt","w+");
					fwrite($fh,$_SERVER['REQUEST_URI']."\n".$acc_contents."\n \n URGENT: USER LOGGED OUT");
					fclose($fh);
				}
				die($html."<h3>logged out</h3><hr>");
			}
			else if(stristr($html,"captcha"))
			{
				$url1="https://www.flipkart.com/api/1/user/device/fingerprint";
				$post=file_get_contents("fingerprint.txt");
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
				telegram(urlencode("<b>".$_SERVER['REQUEST_URI']." \n \n Captcha</b>"),true);
				die($html."<h3>captcha</h3><hr>");
			}
			else if(stristr($html,"reached the maximum units allowed"))
				die("<br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br><b><font color=red>$html</font></b><br>");
			else if(stristr($html,"out of"))
				die("<meta http-equiv=refresh content=0><br>".date("d/m/Y H:i:s")." => $accmail ($pin_code)<br><b><font color=red>$html</font></b><br></head>");
			else if(stristr($html,"Lot of rush for the best offers!"))
				echo "<meta http-equiv=refresh content=0><br><b><font color=maroon>STATUS_CODE:=$requeststatus Lot of Rush</font></b>";
			else if(stristr($html,"CHECKOUT_NOT_BUYABLE"))
				die("<meta http-equiv=refresh content=0><br><b><font color=red>STATUS_CODE:=$requeststatus CHECKOUT_NOT_BUYABLE</font></b>");
			else if($requeststatus>=500)
				echo "<meta http-equiv=refresh content=0><br><b><font color=red>STATUS_CODE:=$requeststatus $html</font></b>";
			else if(!empty($jss["RESPONSE"]["actionResponseContext"]["actionMessages"][0]["text"]))
				echo "<meta http-equiv=refresh content=0><br><b><font color=red>$requeststatus -> ".$jss["RESPONSE"]["actionResponseContext"]["actionMessages"][0]["text"]."</font></b>";
			else
				echo "<meta http-equiv=refresh content=0><br><b><font color=red>$requeststatus -> $html</font></b>";
		}
		$print.="<br>";
		if($incart==0)
			die("<meta http-equiv=refresh content=0></head>");
		
		
		$url14="https://www.flipkart.com/api/4/page/fetch";
		$post14='{"pageUri":"http://www.flipkart.com/viewcheckout?checkoutInitiated=true&tr_tenant=FLIPKART","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"stateInfoMap":null,"slotIdInfoMap":null,"gamificationBUInfoMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"networkSpeed":553,"trackingContext":null,"fetchSeoData":false,"ltcContext":null},"partnerContext":null,"locationContext":null,"requestContext":{"type":"CHECKOUT_PAGE","userContext":{"fkUPINpciTokenPresent":false,"simSlotId":[],"installedUPIApps":["com.google.android.apps.nbu.paisa.user","com.indiaBulls","in.amazon.mShop.android.shopping","com.phonepe.app"]}}}';
		
		//list($html111, $status_code1111) = curl_call('POST',$url14,$heads,$post14);
		//-------------------------------------------------- apply supercoins --------------------------------------//
		
		if(preg_match('#"coinInrSavings":(.*?),#',$html,$matsc))
		{
			if(preg_match('#"availableCoins":(.*?),#',$html,$matsc1))
			{
				if($matsc[1]!=0 && $matsc1[1]!=0 && $matsc[1]<=$matsc1[1])
				{
			
					/*$url1="https://www.flipkart.com/api/4/page/fetch?cacheFirst=false";
							
					$post='{"pageUri":"/viewcheckout?checkoutInitiated=true&tr_tenant=FLIPKART","pageContext":{"trackingContext":{"context":{"eVar61":""}},"networkSpeed":4900},"locationContext":{}}';
							
					list($html1, $status_code) = curl_call('POST',$url1,$heads,$post);
					
					preg_match("#\"cartItemRefId\":\"(.*?)\"#",$html1,$matcae);
					$cartid=$matcae[1];*/
					
					$url1="https://www.flipkart.com/api/1/action/view";
							
					$post='{"actionRequestContext":{"checkoutUpsertItemsRequest":[{"cartItemRefId":"'.$cartid.'","coinSelected":true}],"expressCoFlow":false,"pageNumber":1,"pageUri":"/viewcheckout?checkoutInitiated=true&tr_tenant=FLIPKART","type":"CHECKOUT_USE_COIN","userContext":{"fkUPINpciTokenPresent":false,"simSlotId":[],"installedUPIApps":["com.google.android.apps.nbu.paisa.user","com.phonepe.app","com.indiaBulls","in.amazon.mShop.android.shopping"]}}}';
							
					list($html1, $status_code) = curl_call('POST',$url1,$heads,$post);
					
					preg_match('#"totalCost":(.*?),#',$html1,$tsc1);
					
					echo "<br>supercoin applied = ".$matsc[1]." <= ".$matsc1[1]." -> <font color=green>".$tsc1[1]."</font> Rs.<br>";
				}
			}
		}
		
	again:	
	
		$url1="https://www.flipkart.com/api/3/checkout/paymentToken";
		$heads = array_filter($heads, 'myFilter');
		$heads[]='Cookie: '.$cookie;
		//die(print_r($heads));
	    //list($html, $status_code) = curl_call('GET',$url1,$heads,"");
		
		$m=json_decode($html,1);
		$token=$m["RESPONSE"]["getPaymentToken"]["token"];
		
		$url14="https://www.flipkart.com/api/1/action/view";
		$heads=[];
		$heads[]='X-Request-MetaInfo: {"actionType":"CHECKOUT_PAYMENT_TOKEN_GENERATE","pageUri":"http://www.flipkart.com/viewcheckout?checkoutInitiated=true&tr_tenant=FLIPKART"}';
		$heads[]='X-AR-AVAILABILITY: NOT_PRESENT';
		$heads[]='X-MULTIWIDGET-VERSION: 7.14.9';
		$heads[]='x-atlas-versions: 20892000/2420100';
		$heads[]='x-request-metaInfo: {"actionType":"CHECKOUT_PAYMENT_TOKEN_GENERATE","pageUri":"http%3A%2F%2Fwww.flipkart.com%2Fviewcheckout%3FcheckoutInitiated%3Dtrue%26tr_tenant%3DFLIPKART"}';
		$heads[]='Network-Type: wifi';
		$heads[]='x-unified-atlas-version: 2420100.-1.-1.7014009.2008092.1002050';
		$heads[]='X-DLS: true';
		//$heads[]='X-AppSession-ID: 1ea4df50-af48-48b0-a63e-748ee541f7cb_1770639848380';
		//$heads[]='X-Location-Info: {"la":29.4171146,"lo":76.9833567,"cl":true}';
		$heads[]='Content-Type: application/json';
		$heads[]='User-Agent: okhttp/4.9.2';
		$heads[]='Cookie: '.$cookie;
		$heads[]='at: '.$at;
		//$heads[]='X-Visit-Id: 31144985973de35722ddc15de34334c2-1770643376657';
		$heads[]='secureToken: '.$securetoken;
		$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
		$heads[]='sn: '.$sn;
		$heads[]='Accept-Encoding: gzip';
		preg_match("#S=(.*?);#",$cookie,$mat);
			$sc=$mat[1];
		$heads[]='secureCookie: '.$sc;
		
		
		$post14='{"actionRequestContext":{"pageNumber":1.0,"pageUri":"http://www.flipkart.com/viewcheckout?checkoutInitiated=true&tr_tenant=FLIPKART","preferredPayOptions":[],"type":"CHECKOUT_PAYMENT_TOKEN_GENERATE","userContext":{"installedUPIApps":["com.google.android.apps.nbu.paisa.user","com.indiaBulls","in.amazon.mShop.android.shopping","com.phonepe.app"],"simSlotId":[],"fkUPINpciTokenPresent":false}}}';
		list($html111, $status_code1111) = curl_call('POST',$url14,$heads,$post14);
		
		preg_match("#token=(.*?)\"#",$html111,$mat);
		$token=$mat[1];
		
		
		
		if(stristr($html,"We are seeing a surge in the number of"))
			die("<meta http-equiv=refresh content=0></head>".$html);
		else if(empty($token))
		{
			echo "empty token $status_code1111";
			
			if(stristr($html111,"\"STATUS_CODE\":500") || $status_code1111==429)
			{
				list($html111, $status_code1111) = curl_call('POST',$url14,$heads,$post14);
				preg_match("#token=(.*?)\"#",$html111,$mat);
				$token=$mat[1];
				if(stristr($html111,"\"STATUS_CODE\":500") || $status_code1111==429)
				{
					list($html111, $status_code1111) = curl_call('POST',$url14,$heads,$post14);
					preg_match("#token=(.*?)\"#",$html111,$mat);
					$token=$mat[1];
					if(stristr($html111,"\"STATUS_CODE\":500") || $status_code1111==429)
					{
						list($html111, $status_code1111) = curl_call('POST',$url14,$heads,$post14);
						preg_match("#token=(.*?)\"#",$html111,$mat);
						$token=$mat[1];
					}
				}
			}
			if(empty($token))
			{
				if(stristr($html111,"Unable to proceed"))
					telegram(urlencode($_SERVER['PHP_SELF']."\n<b>$accmail</b>\n $itemid\n $maintitle\n\n Payment token: Unable to proceed. <b>cookie issue.</b>"),true);
				else if(stristr($html111,"\"STATUS_CODE\":500") || stristr($html111,"Please try again later") || $status_code1111==500)
					telegram(urlencode($_SERVER['PHP_SELF']."\n<b>$accmail</b>\n $itemid\n $maintitle\n\n Payment token: Please try again later. <b>cookie issue.</b>"),true);
				else if(stristr($html111,"your checkout session has expired"))
					telegram(urlencode($_SERVER['PHP_SELF']."\n<b>$accmail</b>\n $itemid\n $maintitle\n\n Payment token: your checkout session has expired. <b>cookie issue.</b>"),true);
				else if(!stristr($html111,"\"STATUS_CODE\":200"))
					telegram(urlencode($_SERVER['PHP_SELF']."\n<b>$accmail</b>\n $itemid\n $maintitle\n\n Payment token: unidentified error. cookie issue. \n$html"),true);
					//echo "<br>payment token empty = ".$html;
				die("<meta http-equiv=refresh content=5></head><br>payment token empty = ".$html111."=".$status_code1111);
			}
		}
		
		unset($heads);
		
		curl_close($ch);
		//die("$token found");
		
		if(((int)$price/(int)$qtty)>7000 && rand(1,2)==2)
			goto cod;
		
		
		if($_REQUEST["gv"]=="yes")
		{
			unset($heads);
			$heads[]='Accept: */*';
			$heads[]='x-client-trace-id: b1824b18-b1ad-64c7-32c5-31dad999270d';
			$heads[]='Connection: keep-alive';
			$heads[]='Origin: https://www.flipkart.com';
			$heads[]='x-ab-experiments: {"phonepe_quick_checkout":"true"}';
			$heads[]='Content-Type: application/json';
			$heads[]='Accept-Language: en-US,en;q=0.9,hi;q=0.8,mt;q=0.7';
			$heads[]='Referer: https://www.flipkart.com/rv/pay?token='.$token;
		
			$post='{"token":"'.$token.'","payment_instrument":"QC_SCLP","is_diff_shown_to_user":false,"device_capabilities":{"read_sms":false,"phonepe_sdk":true,"juspay_sdk":true,"nda_enabled":true,"upi_enabled":true,"phonepe_sdk_version":"1.6.5","phonepe_device_id":"'.$deviceid.'","user_app_details":[{"app_name":"PHONEPE","user_status":"INVALID","app_version":"-1"}]}}';
			
			$url1="https://payments.flipkart.com/fkpay/api/v3/payments/select";
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			if(stristr($html,"Verify with OTP to use Gift Card balance"))
			{
				echo "<p><h3>cant proceed as otp not verified for gv use. please verify and update device id</h3>";
				goto cod;
			}
			
			if(stristr($html,"try again"))
			{
				echo '<meta http-equiv="refresh" content="5; url='.$_SERVER["HTTP_REFERER"].'">';
				goto cod;
			}
			
			
			$m=json_decode($html,1);
			foreach($m["options"] as $v)
			{
				if($v["payment_instrument"]=="QC_SCLP")
				{
					$txnid=$v["transaction_id"];
					break;
				}
			}
			if(!empty($txnid))
			{
				$post='{"token":"'.$token.'","transaction_ids":["'.$txnid.'"]}';
				$url1="https://payments.flipkart.com/fkpay/api/v3/payments/complete";
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
				$m=json_decode($html,1);	
			}
			else
			{
				echo "empty transaction ID";
				goto cod;
			}
		
			telegram(urlencode("<b>$discountpercent %</b>\n $maintitle \n<b>$price</b>\n ".$_REQUEST["url"]."\n in stock."),true);
			
		}
		else if($_REQUEST["phonepe"]=="yes")
		{
phonepe:
			unset($heads);
			$url1="https://payments.flipkart.com/fkpay/api/v3/payments/pay?token=$token&instrument=PHONEPE";
		
			$heads[]='Connection: keep-alive';
			$heads[]='sec-ch-ua: "Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"';
			$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36 FKUA/msite/0.0.3/msite/Mobile';
			$heads[]='Content-Type: application/json';
			$heads[]='sec-ch-ua-mobile: ?1';
			$heads[]='User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36';
			$heads[]='sec-ch-ua-platform: "Android"';
			$heads[]='Accept: */*';
			$heads[]='x-ab-experiments: {"egv_travel_enabled":1,"gpay_integration":2,"wallet_experiment":1,"NU_COD_DEFAULT":1,"vpa_payments_page":1,"SC_PAY":1,"scpay_v2_experiment":2}';
			$heads[]='x-device-source: msite';
			$heads[]='Origin: https://www.flipkart.com';
			$heads[]='Sec-Fetch-Site: same-site';
			$heads[]='Sec-Fetch-Mode: cors';
			$heads[]='Sec-Fetch-Dest: empty';
			$heads[]='Referer: https://www.flipkart.com/';
			$heads[]='Accept-Encoding: gzip, deflate, br';
			$heads[]='Accept-Language: en-US,en;q=0.8';
			$heads[]='Cookie: '.$cookie;
			
			//$heads[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36';
			
			
			
			$ch=curl_init();
			
			$post='{"upi_details":{"app_code":"PHONEPE","package_name":"com.phonepe.app"},"payment_instrument":"PHONEPE","token":"'.$token.'","section_info":{"section_name":"OTHERS"},"user_selected_adjustment_ids":[]}';
			
			$url1="https://1.pay.payzippy.com/fkpay/api/v3/payments/paywithdetails?token=$token";
				
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			$m=json_decode($html,1);
			
			if($m["response_status"]=="FAILED")
			{
				$mmmsg=$m["messages"][0]["message"];
				
				if(stristr($html,"out of stock") || stristr($html,"high volume"))
					echo "<meta http-equiv=refresh content=0><br>$mmmsg<br>";
				else
					telegram(urlencode("<b>".$_SERVER['PHP_SELF']."\n\n$accmail</b>\n$itemid\n$maintitle\n$mmmsg"),true);
				echo "<b>$accmail</b><br>$itemid<br><h2>$print<br>Last step=$token<br>$mmmsg</h2>$price Rs.<br>$qtty<br><hr>";
				
				if($qt!="1")
				{
					echo "<h3><font color=blue>Retrying with 1 quantity</font></h3>";
					$qt=$qttytty="1";
					
					unset($heads);
					$heads[]='Connection: keep-alive';
					$heads[]='sec-ch-ua: "Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"';
					$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36 FKUA/msite/0.0.3/msite/Mobile';
					$heads[]='Content-Type: application/json';
					$heads[]='sec-ch-ua-mobile: ?1';
					$heads[]='User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36';
					$heads[]='sec-ch-ua-platform: "Android"';
					$heads[]='Accept: */*';
					$heads[]='Origin: https://www.flipkart.com';
					$heads[]='Sec-Fetch-Site: same-site';
					$heads[]='Sec-Fetch-Mode: cors';
					$heads[]='Sec-Fetch-Dest: empty';
					$heads[]='Referer: https://www.flipkart.com/';
					$heads[]='Accept-Encoding: gzip, deflate, br';
					$heads[]='Accept-Language: en-US,en;q=0.8';
					$heads[]='Cookie: '.$cookie;
		
					$url1="https://www.flipkart.com/api/1/action/view";
				
					$post='{"actionRequestContext":{"checkoutUpsertItemsRequest":[{"cartItemRefId":"'.$cartid.'","quantity":'.$qttytty.'}],"expressCoFlow":false,"pageNumber":1,"pageUri":"/viewcheckout?checkoutInitiated=true","type":"CHECKOUT_UPDATE_ITEM_QUANTITY"}}';
					
					$url1="https://payments.flipkart.com/fkpay/api/v3/payments/complete";
			
					list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
					
					$html222=$html;
					
					if(preg_match('#"finalPrice":(.*?),#',$html222,$mat))
					$price=$mat[1];
					
					if(preg_match('#"quantity":(.*?),#',$html222,$mat))
					$qtty=$mat[1];
				
					goto again;
				}
				if(!empty($url))
					explodelink($url);
				checkcommand();
				die;
			}
			
			
			$phonepeurl=$m["primary_action"]["url"];
			$txnid=$m["txn_id"];
		
			if(stristr($phonepeurl,"pgCancelResponse"))
			{
				telegram(urlencode("<b>$accmail \n $discountpercent %</b>\n $maintitle \n<b>$price Rs.($qtty)</b> + $dvs\n $seller=$sellerid\n $itemid \n pgCancelResponse"),true);
				
				
				$post='{"actionRequestContext":{"type":"CART_REMOVE","items":[{"listingId":"'.$itemid.'"}],"marketPlaces":[],"pageUri":"/viewcart","pageNumber":1}}';

				$url1="https://www.flipkart.com/api/1/action/view";
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
				writelogg($html);
			}
			else if(!stristr($phonepeurl,"phonepe"))
			{
				writelogg(date("d/m/Y H:i:s")."=>".$html);
			}
			else
			{
				if(($price=="" || $price==" ") && !stristr($html,"item not available") && !stristr($html,"surge"))
					writelog("$itemid => $lpghtml");
				
				$fh=fopen("../phonepe.html","a+");
				fwrite($fh, "<p><div id=$itemid>".date("d/m/Y H:i:s")." => <font color=darkpink><b>".$_SERVER['PHP_SELF']."</b></font> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font></b> + $dvs <i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><a href=$phonepeurl target=\"_blank\">$phonepeurl</a></div></p>\r\n\r\n");
				fclose($fh);
				
				$jsondayaa='{
						"source":"'.$_SERVER['PHP_SELF'].'",
						"acc":"'.$accmail.' ['.$pin_code.']",
						"discount":"'.$discountpercent.'",
						"productname":"'.str_replace('"','\"',$maintitle).'",
						"listingid":"'.$itemid.'",
						"price":'.$price.',
						"qty":'.$qtty.',
						"deliverycharge":'.$dvs.',
						"seller":"'.$seller.'",
						"deliverydate":"'.$promisedate.'",
						"link":"'.$phonepeurl.'",
						"assured":"'.$flipkartassured.'",
						"txnid":"'.$txnid.'",
						"timestamp":'.time().'
					}';
					
				$fh=fopen("../phonepelinks.txt","a+");
				fwrite($fh, "\r\n".$jsondayaa.",");
				fclose($fh);
				
				telegram(urlencode($_SERVER['PHP_SELF']." --- ".$_REQUEST["filename"]."\n<b>$accmail (#pin__$pin_code)"."\n"."$discountpercent %</b>"."\n"."$maintitle"."\n"."<b>$price Rs.</b> ($qtty) + $dvs\n$itemid\n$flipkartassured\n$seller=$sellerid - $promisedate\n\n$phonepeurl"));
				
				/*
				$post="data=".urlencode($jsondayaa);
	
				curl_close($ch);
				$ch=curl_init();
				curl_setopt($ch, CURLOPT_URL,"http://139.59.0.200/phonepejson.php");
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_PROXY, $proxy);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$html=curl_exec($ch);*/
				 
				if($dvs=="0.0" || $dvs=="0")
					file_get_contents("http://localhost/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <font color=darkpink><b>".$_SERVER['PHP_SELF']."</b></font> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font></b> + $dvs <i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><a href=$phonepeurl target=\"_blank\">$phonepeurl</a>"));
				else
					file_get_contents("http://localhost/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <u><font color=maroon><b>".$_SERVER['PHP_SELF']."</b></font></u> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font> + <font color=red><b>$dvs</b></font> <br><i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><a href=$phonepeurl target=\"_blank\">$phonepeurl</a>"));
			
				if(!stristr($get_contents,"$itemid") && $_REQUEST["server"]=="yes")
				{
					 $fh=fopen("notdoagain.txt","a+");
					 fwrite($fh, "$itemid => $maintitle => $price\r\n");
					 fclose($fh);
				}
			}
			
			//$m=file_get_contents(urlencode("<b>$accmail \n $discountpercent %</b>\n $maintitle \n<b>$price</b>\n $itemid \n $phonepeurl"));
			
			if($onetry==0 && stristr($phonepeurl,"pgCancelResponse"))
			{
				$onetry++;
				goto onemoretry;			
			}

			unset($heads);
			echo "<a href=$phonepeurl>$phonepeurl</a><h3>".date("d/m/Y H:i:s")." => <b>$accmail ($pin_code)</b> - $discountpercent % - $maintitle -<b>$price Rs.($qtty)</b><h3><hr>";
			
			if(!empty($url))
				explodelink($url);
		}
        else if($_REQUEST["gpay"]=="yes" || $_REQUEST["phonepe"]=="gpay")
		{
gpay:
			unset($heads);
			
			//$url1="https://1.pay.payzippy.com/fkpay/api/v3/payments/paywithdetails?instrument=UPI_INTENT";
			
			$heads[]='Cookie: '.$cookie;
			$heads[]='sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"';
			$heads[]='x-user-agent: Mozilla/5.0 (Linux; Android 8.0.0; Lenovo K8 Note) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36 FKUA/msite/0.0.1/msite/Mobile';
			$heads[]='x-accept-language: en';
			$heads[]='sec-ch-ua-mobile: ?1';
			$heads[]='x-device-source: msite';
			$heads[]='content-type: application/json';
			$heads[]='User-Agent: Mozilla/5.0 (Linux; Android 8.0.0; Lenovo K8 Note) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36';
			//$heads[]='x-ab-experiments: {"pay_on_delivery":1,"phonepe_quick_checkout":1,"BNPL_Preferred":1,"upi_experiment":1,"Silent_auth_test":1,"payOnDelivery":1,"gpay_integration":2,"enableIcons":2,"phone_auth_experiment":1,"wallet_experiment":1,"saved_nudge":2,"cod_nudge_experiment":1,"new_cust_pref":1,"pay_on_delivery_1":1,"pay_on_delivery_2":1,"NU_COD_DEFAULT":1,"digital_india_nudge":1,"safety_messaging_nudge":1,"vpa_payments_page":1,"OFFER_NUDGE":1,"SOCIAL_PROOF_NUDGE":1,"card_eligibility_check":1,"NB_NUDGE":1,"COD_captcha_1":1,"cod_captcha_2":1,"VSC_SECURITY":1,"SC_PAY":1,"tokenisation_enabled":1,"fpl_split_payments":2,"egv_travel_enabled":1,"scpay_v2_experiment":2,"paytm_postpaid_enabled":2,"showRevampedFailTransaction":false,"ismvpdodlive":1,"paymentsCTACopyChangeContinueSecurely":false,"paymentsCTACopyChangePaySecurely":false,"CTABasisUPIOption":false,"highLight_best_discount":false,"visible_option_collapse":false,"vernac_cod_default":true,"nac_cod_default":true,"RoutingExp":false,"pbo_fee_enabled":false}';
			$heads[]='Accept: */*';
			$heads[]='Origin: https://www.flipkart.com';
			$heads[]='Sec-Fetch-Site: cross-site';
			$heads[]='Sec-Fetch-Mode: cors';
			$heads[]='Sec-Fetch-Dest: empty';
			$heads[]='Referer: https://www.flipkart.com/';
			$heads[]='Accept-Encoding: gzip, deflate, br';
			$heads[]='Accept-Language: en-US,en;q=0.9';
			
			$ch=curl_init();
			
			$url1="https://www.flipkart.com/payments?token=$token";
			
			list($html, $status_code) = curl_call('GET',$url1,$heads);
			
			preg_match("#window.sessionId[\s]*=[\s]*\"(.*?)\"#",$html,$mats);
			
			$heads[]='x-session-id: '.$mats[1];
			$heads[]='x-payment-revamp: m1';
			
			$url1="https://payments.flipkart.com/fkpay/api/v3/payments/paywithdetails?instrument=UPI_INTENT";
			//$post='{"token":"'.$token.'","device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false,"nda_enabled":false,"upi_enabled":false,"phonepe_sdk_version":null,"phonepe_device_id":null},"payment_instrument":"UPI_INTENT","is_diff_shown_to_user":false,"upi_details":{"package_name":"com.google.android.apps.nbu.paisa.user","app_code":"GooglePay"},"user_selected_adjustment_ids":[]}';
			$post='{"token":"'.$token.'","payment_instrument":"UPI_INTENT","upi_details":{"app_code":"GooglePay","package_name":"com.google.android.apps.nbu.paisa.user"},"user_selected_adjustment_ids":[],"device_information":{"colorDepth":24,"javaEnabled":false,"javaScriptEnabled":true,"language":"en-US","screenHeight":892,"screenWidth":412,"timeDifference":-330},"device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false,"nda_enabled":false,"upi_enabled":false},"is_diff_shown_to_user":false}';
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			$m=json_decode($html,1);
			
			if($m["response_status"]=="FAILED")
			{
				$mmmsg=$m["messages"][0]["message"];
				if(stristr($html,"out of stock"))
					echo "<meta http-equiv=refresh content=0>";
				else
					telegram(urlencode("<b>".$_SERVER['PHP_SELF']."\n\n$accmail</b>\n$itemid\n$maintitle\n$mmmsg"));
				echo "<b>$accmail</b><br>$itemid<br><h2>$mmmsg</h2>$price Rs.<br>$qtty<br>";
				
				if($qt!="1")
				{
					echo "<br><h3><font color=blue>Retrying with 1 quantity</font></h3>";
					$qt=$qttytty="1";
					unset($heads);
					$heads[]='X-user-agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36 FKUA/msite/0.0.1/msite/Mobile';
					$heads[]='Accept: */*';
					$heads[]='Cookie: '.$cookie;
					$heads[]='Connection: keep-alive';
					$heads[]='Origin: https://www.flipkart.com';
					$heads[]='Content-Type: application/json';
					$heads[]='Accept-Language: en-US,en;q=0.8';
		
					$url1="https://www.flipkart.com/api/1/action/view";
				
					$post='{"actionRequestContext":{"checkoutUpsertItemsRequest":[{"cartItemRefId":"'.$cartid.'","quantity":'.$qttytty.'}],"expressCoFlow":false,"pageNumber":1,"pageUri":"/viewcheckout?checkoutInitiated=true","type":"CHECKOUT_UPDATE_ITEM_QUANTITY"}}';
					
			
					list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
					
					$html222=$html;
					
					if(preg_match('#"finalPrice":(.*?),#',$html222,$mat))
					$price=$mat[1];
					
					if(preg_match('#"quantity":(.*?),#',$html222,$mat))
					$qtty=$mat[1];
				
					goto again;
				}
				
				if(!empty($url))
					explodelink($url);
				
				checkcommand();
				die;
			}
			
			
			$phonepeurl=$m["primary_action"]["url"];
			$acsurl=$m["primary_action"]["parameters"]["acsurl"];
			$txnid=$m["txn_id"];
            
            $altlink="http://139.59.0.200/fkart/redirectgpay.php?url=".urlencode($phonepeurl)."&acsurl=".urlencode($acsurl);
		
			if(stristr($phonepeurl,"pgCancelResponse"))
			{
				telegram(urlencode("<b>$accmail \n $discountpercent %</b>\n $maintitle \n<b>$price Rs.($qtty)</b> + $dvs\n $seller\n $itemid \n pgCancelResponse"));
				
				unset($heads);
				
				$heads[]='X-user-agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36 FKUA/msite/0.0.1/msite/Mobile';
				$heads[]='Accept: */*';
				$heads[]='Cookie: '.$cookie;
				$heads[]='Connection: keep-alive';
				$heads[]='Origin: https://www.flipkart.com';
				$heads[]='Content-Type: application/json';
				$heads[]='Accept-Language: en-US,en;q=0.8';
				
				$post='{"actionRequestContext":{"type":"CART_REMOVE","items":[{"listingId":"'.$itemid.'"}],"marketPlaces":[],"pageUri":"/viewcart","pageNumber":1}}';
		
				$url1="https://www.flipkart.com/api/1/action/view";
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
				
				writelogg($html);
			}
			else
			{
				if(($price=="" || $price==" ") && !stristr($html,"item not available") && !stristr($html,"surge"))
					writelog("$itemid => $lpghtml");
				
				telegram(urlencode($_SERVER['PHP_SELF']." --- ".$_REQUEST["filename"]."\n<b>$accmail (#pin__$pin_code)"."\n"."$discountpercent %</b>"."\n"."$maintitle"."\n"."<b>$price Rs.</b> ($qtty) + $dvs\n$itemid\n$seller - $promisedate\n\n$phonepeurl\n\n$altlink"));
				
				$fh=fopen("phonepeurl.html","a+");
				fwrite($fh, "<a href=$phonepeurl>".date("d/m/Y H:i:s")." => <b>$accmail - $discountpercent %</b>- $maintitle -<b>$price Rs.($qtty)</b> + $dvs $seller ==> $phonepeurl</a><br><br>\r\n");
				fclose($fh);
				
				if($dvs=="0.0" || $dvs=="0")
					file_get_contents("http://139.59.0.200/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <font color=darkpink><b>".$_SERVER['PHP_SELF']."</b></font> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font></b> + $dvs <i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><a href=$phonepeurl target=\"_blank\">$phonepeurl</a><br><br><a href=$altlink target=\"_blank\">$altlink</a>"));
				else
					file_get_contents("http://139.59.0.200/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <u><font color=maroon><b>".$_SERVER['PHP_SELF']."</b></font></u> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font> + <font color=red><b>$dvs</b></font> <br><i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><a href=$phonepeurl target=\"_blank\">$phonepeurl</a><br><br><a href=$altlink target=\"_blank\">$altlink</a>"));
			
				if(!stristr($get_contents,"$itemid") && $_REQUEST["server"]=="yes")
				{
				 $fh=fopen("notdoagain.txt","a+");
				 fwrite($fh, "$itemid => $maintitle => $price\r\n");
				 fclose($fh);
				}
			}
			
			//$m=file_get_contents(urlencode("<b>$accmail \n $discountpercent %</b>\n $maintitle \n<b>$price</b>\n $itemid \n $phonepeurl"));
			
			if($onetry==0 && stristr($phonepeurl,"pgCancelResponse"))
			{
				$onetry++;
				goto onemoretry;			
			}

			unset($heads);
			echo "<a href=$phonepeurl>$phonepeurl</a><br><br><a href=$altlink>$altlink</a><br><br><br>$txnid<br><h3>".date("d/m/Y H:i:s")." => <b>$accmail ($pin_code)</b> - $discountpercent % - $maintitle -<b>$price Rs.($qtty)</b></h3><hr>";
			
			if(!empty($url))
				explodelink($url);
		}
		else if($_REQUEST["upi"]=="yes" || $_REQUEST["phonepe"]=="upi")
		{
upi:
			unset($heads);
			
			//$url1="https://1.pay.payzippy.com/fkpay/api/v3/payments/paywithdetails?instrument=UPI_INTENT";
			
			
			
			$ch=curl_init();
			
			
			$heads=[];
			$heads[]='sec-ch-ua: "Not)A;Brand";v="8", "Chromium";v="138", "Android WebView";v="138"';
			$heads[]='sec-ch-ua-mobile: ?1';
			$heads[]='sec-ch-ua-platform: "Android"';
			$heads[]='User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
			$heads[]='Origin: null';
			$heads[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
			$heads[]='X-Requested-With: com.flipkart.android';
			$heads[]='Content-Type: application/x-www-form-urlencoded';
			$heads[]='Sec-Fetch-Site: none';
			$heads[]='Sec-Fetch-Mode: navigate';
			$heads[]='Sec-Fetch-User: ?1';
			$heads[]='Sec-Fetch-Dest: document';
			$heads[]='Accept-Encoding: gzip, deflate, br, zstd';
			$heads[]='Accept-Language: en,en-IN;q=0.9,hi-IN;q=0.8,hi;q=0.7,en-US;q=0.6';
			$heads[]='Cookie: '.$cookie;
			$heads[]='at: '.$at;
			$heads[]='X-Visit-Id: 31144985973de35722ddc15de34334c2-1770643376657';
			$heads[]='secureToken: '.$securetoken;
			$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
			$heads[]='sn: '.$sn;
			$heads[]='Accept-Encoding: gzip';
			//$heads[]='secureCookie: '.$sc;
			
			
			$url1="https://pay.flipkart.com/payments?token=$token&enablestreaming=true";
			
			$post='_sn_='.$sn.'&_at_='.urlencode($at).'&_sc_='.urlencode($sc).'&appVisitorId=31144985973de35722ddc15de34334c2';
			$heads[]='content-type: application/x-www-form-urlencoded';
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			
			
			
			preg_match("#window.sessionId[\s]*=[\s]*\"(.*?)\"#",$html,$mats);
			
			
			$heads=[];
			$heads[]='sec-ch-ua-platform: "Android"';
			$heads[]='x-device-source: android';
			$heads[]='sec-ch-ua: "Not)A;Brand";v="8", "Chromium";v="138", "Android WebView";v="138"';
			$heads[]='sec-ch-ua-mobile: ?1';
			$heads[]='x-device-details: {"channel":"android","platform":"android","appVersion":"2420100"}';
			$heads[]='x-session-id: '.$mats[1];
			$heads[]='x-payment-revamp: m1';
			$heads[]='User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2)';
			$heads[]='token: '.$token;
			$heads[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
			$heads[]='X-Requested-With: com.flipkart.android';
			$heads[]='user-language: en';
			//$heads[]='x-trace-id: 239abc2a-ba08-4555-9212-446cc434618f';
			$heads[]='user-language: en';
			$heads[]='x-user-language: en';
			$heads[]='X-Requested-With: com.flipkart.android';
			$heads[]='device-details: {"channel":"android","platform":"android","appVersion":"2420100"}';
			//$heads[]='x-client-trace-id: 239abc2a-ba08-4555-9212-446cc434618f';
			$heads[]='Sec-Fetch-Site: same-site';
			$heads[]='Sec-Fetch-Mode: cors';
			$heads[]='Sec-Fetch-Dest: empty';
			$heads[]='Referer: https://pay.flipkart.com/';
			$heads[]='Accept: */*';
			$heads[]='Accept-Language: en,en-IN;q=0.9,hi-IN;q=0.8,hi;q=0.7,en-US;q=0.6';
			$heads[]='Cookie: '.$cookie;
			$heads[]='at: '.$at;
			//$heads[]='X-Visit-Id: 31144985973de35722ddc15de34334c2-1770643376657';
			$heads[]='secureToken: '.$securetoken;
			$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/138.0.7204.179 Mobile Safari/537.36 FKUA/Retail/2420100/Android/Mobile (asus/ASUS_X00TD/31144985973de35722ddc15de34334c2) FKUA/msite/0.0.1/msite/Mobile';
			$heads[]='sn: '.$sn;
			$heads[]='Accept-Encoding: gzip';
			$heads[]='secureCookie: '.$sc;
			$heads[]='Content-Type: application/json';

			

			
			//$post='{"token":"'.$token.'","device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false,"nda_enabled":false,"upi_enabled":false,"phonepe_sdk_version":null,"phonepe_device_id":null},"payment_instrument":"UPI_INTENT","is_diff_shown_to_user":false,"upi_details":{"package_name":"com.google.android.apps.nbu.paisa.user","app_code":"GooglePay"},"user_selected_adjustment_ids":[]}';
			
			$url1="https://payments.flipkart.com/fkpay/v5/payments/instrument/UPI_INTENT/verify";
			//$heads[]='Content-Type: application/json';
			
			$post='{"token":"'.$token.'","paymentInstrument":"UPI_INTENT","provider":"FLIPKART","instrumentDetails":{"instrumentType":"UPI_INTENT","packageName":"com.phonepe.app","appCode":"PhonePe"}}';
			
			//list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			//sleep(5);
			
			$url1="https://payments.flipkart.com/fkpay/api/v3/payments/paywithdetails?instrument=UPI_INTENT";
			
			$post='{"token":"'.$token.'","payment_instrument":"UPI_INTENT","upi_details":{"app_code":"GooglePay","package_name":"com.google.android.apps.nbu.paisa.user"},"user_selected_adjustment_ids":[],"device_information":{"colorDepth":24,"javaEnabled":false,"javaScriptEnabled":true,"language":"en","screenHeight":720,"screenWidth":360,"timeDifference":-330},"device_capabilities":{"read_sms":false,"phonepe_sdk":true,"juspay_sdk":true,"nda_enabled":false,"upi_enabled":true,"user_app_details":[{"app_name":"PHONEPE","user_status":"INVALID","app_version":"-1"}],"phonepe_device_id":"Mzk1YWYwYmE0OGQ0YTI5MDg3Yzg2MzAxZjE1NDZkNGY2ZDg4OGJkYzk0YzZmYzIzMWQwM2MyNjA4YmYyMDhhZDMwYjU5MTQ3MDI2MWJjYWNjMTlmMzVkOGI3MDU6YTVjNDcxZDI3YTI4NDViMTk4ZTdhYjA2MDFhNzkyMGQ=","phonepe_sdk_version":"1.6.8"},"is_diff_shown_to_user":false}';
			$post='{"token":"'.$token.'","payment_instrument":"UPI_INTENT","upi_details":{"app_code":"PhonePe","package_name":"com.phonepe.app"},"user_selected_adjustment_ids":[],"device_information":{"colorDepth":24,"javaEnabled":false,"javaScriptEnabled":true,"language":"en","screenHeight":720,"screenWidth":360,"timeDifference":-330},"device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":true,"nda_enabled":false,"upi_enabled":true,"user_app_details":[{"app_name":"PHONEPE","user_status":"INVALID","app_version":"-1"}]},"is_diff_shown_to_user":false}';
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			$m=json_decode($html,1);
			
			if($m["response_status"]=="FAILED")
			{
				$mmmsg=$m["messages"][0]["message"];
				if(stristr($html,"out of stock"))
					echo "<meta http-equiv=refresh content=0>";
				else
					telegram(urlencode("<b>".$_SERVER['PHP_SELF']."\n\n$accmail</b>\n$itemid\n$maintitle\n$mmmsg"));
				echo "<b>$accmail</b><br>$itemid<br><h2>$mmmsg</h2>$price Rs.<br>$qtty<br>";
				
				if($qt!="1")
				{
					echo "<br><h3><font color=blue>Retrying with 1 quantity</font></h3>";
					$qt=$qttytty="1";
					unset($heads);
					$heads[]='X-user-agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36 FKUA/msite/0.0.1/msite/Mobile';
					$heads[]='Accept: */*';
					$heads[]='Cookie: '.$cookie;
					$heads[]='Connection: keep-alive';
					$heads[]='Origin: https://www.flipkart.com';
					$heads[]='Content-Type: application/json';
					$heads[]='Accept-Language: en-US,en;q=0.8';
		
					$url1="https://www.flipkart.com/api/1/action/view";
				
					$post='{"actionRequestContext":{"checkoutUpsertItemsRequest":[{"cartItemRefId":"'.$cartid.'","quantity":'.$qttytty.'}],"expressCoFlow":false,"pageNumber":1,"pageUri":"/viewcheckout?checkoutInitiated=true","type":"CHECKOUT_UPDATE_ITEM_QUANTITY"}}';
					
			
					list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
					
					$html222=$html;
					
					if(preg_match('#"finalPrice":(.*?),#',$html222,$mat))
					$price=$mat[1];
					
					if(preg_match('#"quantity":(.*?),#',$html222,$mat))
					$qtty=$mat[1];
				
					goto again;
				}
				
				if(!empty($url))
					explodelink($url);
				
				checkcommand();
				die;
			}
			
			
			$phonepeurl=$m["primary_action"]["url"];
			$acsurl=$m["primary_action"]["parameters"]["acsurl"];
			$txnid=$m["txn_id"];
            
            $altlink="http://139.59.0.200/fkart/redirectgpay.php?url=".urlencode($phonepeurl)."&ts=".time();
		
			if(stristr($phonepeurl,"pgCancelResponse"))
			{
				telegram(urlencode("<b>$accmail \n $discountpercent %</b>\n $maintitle \n<b>$price Rs.($qtty)</b> + $dvs\n $seller\n $itemid \n pgCancelResponse"));
				
				unset($heads);
				
				$heads[]='X-user-agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36 FKUA/msite/0.0.1/msite/Mobile';
				$heads[]='Accept: */*';
				$heads[]='Cookie: '.$cookie;
				$heads[]='Connection: keep-alive';
				$heads[]='Origin: https://www.flipkart.com';
				$heads[]='Content-Type: application/json';
				$heads[]='Accept-Language: en-US,en;q=0.8';
				
				$post='{"actionRequestContext":{"type":"CART_REMOVE","items":[{"listingId":"'.$itemid.'"}],"marketPlaces":[],"pageUri":"/viewcart","pageNumber":1}}';
		
				$url1="https://www.flipkart.com/api/1/action/view";
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
				
				writelogg($html);
			}
			else
			{
				if(($price=="" || $price==" ") && !stristr($html,"item not available") && !stristr($html,"surge"))
					writelog("$itemid => $lpghtml");
				
				telegram(urlencode($_SERVER['PHP_SELF']." --- ".$_REQUEST["filename"]."\n<b>$accmail (#pin__$pin_code)"."\n"."$discountpercent %</b>"."\n"."$maintitle"."\n"."<b>$price Rs.</b> ($qtty) + $dvs\n$itemid\n$seller - $promisedate\n\n$phonepeurl\n\n$altlink"));
				
				$fh=fopen("phonepeurl.html","a+");
				fwrite($fh, "<a href=$phonepeurl>".date("d/m/Y H:i:s")." => <b>$accmail - $discountpercent %</b>- $maintitle -<b>$price Rs.($qtty)</b> + $dvs $seller ==> $phonepeurl</a><br><br>\r\n");
				fclose($fh);
				
				if($dvs=="0.0" || $dvs=="0")
					file_get_contents("http://139.59.0.200/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <font color=darkpink><b>".$_SERVER['PHP_SELF']."</b></font> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font></b> + $dvs <i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><br><br><a href=$altlink target=\"_blank\">".urldecode($altlink)."</a>"));
				else
					file_get_contents("http://139.59.0.200/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <u><font color=maroon><b>".$_SERVER['PHP_SELF']."</b></font></u> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font> + <font color=red><b>$dvs</b></font> <br><i>$seller=$sellerid $flipkartassured</i> - $promisedate<br>$txnid<br><br><br><a href=$altlink target=\"_blank\">".urldecode($altlink)."</a>"));
			
				if(!stristr($get_contents,"$itemid") && $_REQUEST["server"]=="yes")
				{
				 $fh=fopen("notdoagain.txt","a+");
				 fwrite($fh, "$itemid => $maintitle => $price\r\n");
				 fclose($fh);
				}
			}
			
			//$m=file_get_contents(urlencode("<b>$accmail \n $discountpercent %</b>\n $maintitle \n<b>$price</b>\n $itemid \n $phonepeurl"));
			
			if($onetry==0 && stristr($phonepeurl,"pgCancelResponse"))
			{
				$onetry++;
				goto onemoretry;			
			}

			unset($heads);
			echo "<a href=$phonepeurl>$phonepeurl</a><br><br><a href=$altlink>$altlink</a><br><br><br>$txnid<br><h3>".date("d/m/Y H:i:s")." => <b>$accmail ($pin_code)</b> - $discountpercent % - $maintitle -<b>$price Rs.($qtty)</b></h3><hr>";
			
			if(!empty($url))
				explodelink($url);
		}
		else
		{
			cod:
			unset($heads);
			$ch=curl_init();
			
			$heads[]='Connection: keep-alive';
			$heads[]='sec-ch-ua: "Chromium";v="110", "Google Chrome";v="110", "Not=A?Brand";v="24"';
			$heads[]='X-User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop';
			$heads[]='Content-Type: application/json';
			$heads[]='sec-ch-ua-mobile: ?0';
			$heads[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36';
			$heads[]='sec-ch-ua-platform: "Windows"';
			$heads[]='Accept: */*';
			$heads[]='x-ab-experiments: {"phonepe_quick_checkout":"true"}';
			$heads[]='Origin: https://www.flipkart.com';
			$heads[]='Sec-Fetch-Site: same-site';
			$heads[]='Sec-Fetch-Mode: cors';
			$heads[]='Sec-Fetch-Dest: empty';
			$heads[]='Referer: https://www.flipkart.com/';
			$heads[]='Accept-Encoding: gzip, deflate, br';
			$heads[]='Accept-Language: en-US,en;q=0.8';
			$heads[]='Cookie: '.$cookie;
			
			
			$post='{"token":"'.$token.'","device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false,"phonepe_sdk_version":null,"phonepe_device_id":null}}';
			
			
			$url1="https://payments.flipkart.com/fkpay/api/v3/payments/options";
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			$m=json_decode($html,1);
			$paymentopts=$m["options"];
			foreach($paymentopts as $options)
			{
				$method=$options["payment_instrument"];
				if($method=="COD" or $method=="POD")
				{
					$applicable=$options["applicable"];
					if($applicable==false)
					{
						echo "<br><font color=red>cod not applicable</font><br>";
						goto gpay;
					}
				}
			}
			
			/*
			curl_setopt($ch, CURLOPT_URL,"https://payments.flipkart.com/fkpay/api/v3/payments/captcha/$token");
			curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_ENCODING , "gzip");
			curl_setopt($ch, CURLOPT_COOKIE, "unicorn_shadow=false; token=$token");
			curl_setopt($ch, CURLOPT_REFERER, "https://www.flipkart.com/rv/pay/captcha?payment_instrument=COD&token=$token");
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $heads);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$html=curl_exec($ch);	
			$m=json_decode($html,1);
			$cap_id=$m["captcha_image"]["id"];
			$cap_image=$m["captcha_image"]["image"];
			
			if(empty($cap_image))
			{
				echo "<meta http-equiv=refresh content=0></head>";
				//goto phonepe;
			}
			
			curl_close($ch);
			$ch=curl_init();
			
			$filename="captchas/cap".rand(1,20).".jpeg";
			 $ifp = fopen( $filename, "wb" ); 
			fwrite( $ifp, base64_decode($cap_image) ); 
			fclose( $ifp );
				
				
			$cFile = curl_file_create(realpath($filename), "image/jpeg", basename(realpath($filename)));
			// $cFile = '@' . realpath($filename);
			
			//$post="method=base64&key=f342220716124164cfe53f7dc486f27d&numeric=1&max_len=3&body=$cap_image&json=1&submit=Upload+and+get+the+ID";
			//$post="method=post&key=&numeric=1&max_len=3&file=$cFile&json=1&submit=Upload+and+get+the+ID";	
			
				$post = array(
			'method'    => 'post', 
			'key'       => '079ec8fc8d7097f537cfcc0aeb49aa80', 
			'file'      => $cFile,
			'numeric'	=> '1',
			'max_len'	=> '3',
			'json'	=> '1'
			);
		
			curl_setopt($ch, CURLOPT_URL,"http://2captcha.com/in.php");
			curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$html=curl_exec($ch);
			//echo $html;
			
			$ahha=json_decode($html,1);
			$requestid=$ahha["request"];
			
			if(stristr($requestid,"ZERO"))
				telegram(urlencode($_SERVER['PHP_SELF']." --- ".$_REQUEST["filename"]."\n<b>"."$accmail ($pin_code)</b>\n 2captcha: $html"));
			
			sleep(3);
				
			curl_setopt($ch, CURLOPT_URL,"http://2captcha.com/res.php?key=079ec8fc8d7097f537cfcc0aeb49aa80&action=get&id=$requestid&json=1");
			curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$html=curl_exec($ch);	
			$ahha=json_decode($html,1);
			$code=$ahha["request"];
			
			$ctr=0;
			$status="0";
			$status=$ahha["status"];
			
			while($status=="0")
			{
				sleep(1);
				$ctr++;
				if($ctr==15)
				{
					echo "<meta http-equiv=refresh content=0></head>";
					//goto phonepe;
				}
				$html=curl_exec($ch);	
				$ahha=json_decode($html,1);
				$code=$ahha["request"];
				$status=$ahha["status"];
				//echo $html;
			}
				
			
			if(is_numeric($code))*/
			{
		
				curl_close($ch);
				$ch=curl_init();
				unset($heads);
				
				$time=round(microtime(true) * 1000);
				
				$url1="https://payments.flipkart.com/fkpay/api/v3/payments/pay?token=$token&instrument=COD";
				
				
				$heads[]='Connection: keep-alive';
				$heads[]='sec-ch-ua: "Chromium";v="110", "Google Chrome";v="110", "Not=A?Brand";v="24"';
				$heads[]='X-User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop';
				$heads[]='Content-Type: application/json';
				$heads[]='sec-ch-ua-mobile: ?0';
				$heads[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36';
				$heads[]='sec-ch-ua-platform: "Windows"';
				$heads[]='Accept: */*';
				$heads[]='x-ab-experiments: {"pay_on_delivery":1,"phonepe_quick_checkout":1,"BNPL_Preferred":1,"upi_experiment":1,"Silent_auth_test":1,"payOnDelivery":1,"gpay_integration":2,"enableIcons":2,"phone_auth_experiment":1,"wallet_experiment":1,"saved_nudge":2,"cod_nudge_experiment":1,"new_cust_pref":1,"pay_on_delivery_1":1,"pay_on_delivery_2":1,"NU_COD_DEFAULT":1,"digital_india_nudge":1,"safety_messaging_nudge":1,"vpa_payments_page":1,"OFFER_NUDGE":1,"SOCIAL_PROOF_NUDGE":1,"card_eligibility_check":1,"NB_NUDGE":1,"COD_captcha_1":1,"cod_captcha_2":1,"VSC_SECURITY":1,"SC_PAY":1,"tokenisation_enabled":1,"fpl_split_payments":2,"egv_travel_enabled":1,"scpay_v2_experiment":2,"paytm_postpaid_enabled":2,"showRevampedFailTransaction":false,"remove_cod_captcha":"show_confirmation_bs","ismvpdodlive":1,"paymentsCTACopyChangeContinueSecurely":false,"paymentsCTACopyChangePaySecurely":false,"CTABasisUPIOption":false,"highLight_best_discount":false,"visible_option_collapse":false,"vernac_cod_default":true,"nac_cod_default":true,"RoutingExp":false,"pbo_fee_enabled":false,"emi_v3_stack":false,"emi_downpayment":false,"EnablePaymentsUPIV1":false,"EnablePaymentsUPIV2":false}';
				$heads[]='x-device-source: web';
				$heads[]='Origin: https://www.flipkart.com';
				$heads[]='Sec-Fetch-Site: same-site';
				$heads[]='Sec-Fetch-Mode: cors';
				$heads[]='Sec-Fetch-Dest: empty';
				$heads[]='Referer: https://www.flipkart.com/';
				$heads[]='Accept-Encoding: gzip, deflate, br';
				$heads[]='Accept-Language: en-US,en;q=0.8';
				$heads[]='Cookie: '.$cookie;
				//$post='{"token":"PN180910140124796a876bea84b4b0174537b4daf915d0051e68a0d20fa97122bed4fd9cd951221_v3_UNCRN","device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false},"payment_instrument":"COD","captcha_text":{"id":"/V5fPioThwF9Y5tZQPRvI48YhV0HC50v5sHXGXMJZ2CGQejomRC3z3HCNxxbK/0pk0dwOBxXSMzRUGjVkSj/DZkEwCGanYCLLaoMNl44sGnJ0TKMBZSU262oGx/Fk2Ve","text":"740"}}';
				
				//$post='{"payment_instrument":"COD","token":"'.$token.'","captcha_text":{"id":"'.$cap_id.'","text":"'.$cap_code.'"}}';
				$post='{"token":"'.$token.'","device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false,"nda_enabled":false,"upi_enabled":false,"phonepe_sdk_version":null,"phonepe_device_id":null},"payment_instrument":"COD","is_diff_shown_to_user":false,"captcha_text":null,"remove_captcha_page":true}';
				//$post='{"token":"'.$token.'","device_capabilities":{"read_sms":false,"phonepe_sdk":false,"juspay_sdk":false},"payment_instrument":"COD","captcha_text":{"id":"'.$cap_id.'","text":"'.$code.'"}}';
				
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
					$m=json_decode($html,1);
					
					$str="";
					$txnid=$m["primary_action"]["parameters"]["merchant_transaction_id"];
					$responseurl=$m["primary_action"]["url"];
					$response=$m["response_status"];
					$parameters=$m["primary_action"]["parameters"];
					foreach($parameters as $key=>$val)
						$str.="$key=".urlencode($val)."&";
					
					$post=rtrim($str,"&");
					

				if(!stristr($html,'"response_status":"SUCCESS"'))
				{
					//telegram(urlencode($_SERVER['PHP_SELF']." --- ".$_REQUEST["filename"]."\n<b>"."\n$accmail ($pin_code): $discountpercent %</b>\n $maintitle \n<b>$price</b>\n ".$_REQUEST["url"]." \n checkoutfailed=".$m["messages"][0]["message"]),true);
					echo $html;
					goto gpay;
				}
					
				if(empty($txnid))
				{
					echo "Empty TXNID <br>$html";
					goto gpay;
				}
				
				unset($heads);
				
				$heads[]='Connection: keep-alive';
				$heads[]='sec-ch-ua: "Chromium";v="110", "Google Chrome";v="110", "Not=A?Brand";v="24"';
				$heads[]='X-User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop';
				$heads[]='Content-Type: application/x-www-form-urlencoded';
				$heads[]='sec-ch-ua-mobile: ?0';
				$heads[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36';
				$heads[]='sec-ch-ua-platform: "Windows"';
				$heads[]='Accept: */*';
				//$heads[]='x-ab-experiments: {"pay_on_delivery":1,"phonepe_quick_checkout":1,"BNPL_Preferred":1,"upi_experiment":1,"Silent_auth_test":1,"payOnDelivery":1,"gpay_integration":2,"enableIcons":2,"phone_auth_experiment":1,"wallet_experiment":1,"saved_nudge":2,"cod_nudge_experiment":1,"new_cust_pref":1,"pay_on_delivery_1":1,"pay_on_delivery_2":1,"NU_COD_DEFAULT":1,"digital_india_nudge":1,"safety_messaging_nudge":1,"vpa_payments_page":1,"OFFER_NUDGE":1,"SOCIAL_PROOF_NUDGE":1,"card_eligibility_check":1,"NB_NUDGE":1,"COD_captcha_1":1,"cod_captcha_2":1,"VSC_SECURITY":1,"SC_PAY":1,"tokenisation_enabled":1,"fpl_split_payments":2,"egv_travel_enabled":1,"scpay_v2_experiment":2,"paytm_postpaid_enabled":2,"showRevampedFailTransaction":false,"remove_cod_captcha":"show_confirmation_bs","ismvpdodlive":1,"paymentsCTACopyChangeContinueSecurely":false,"paymentsCTACopyChangePaySecurely":false,"CTABasisUPIOption":false,"highLight_best_discount":false,"visible_option_collapse":false,"vernac_cod_default":true,"nac_cod_default":true,"RoutingExp":false,"pbo_fee_enabled":false,"emi_v3_stack":false,"emi_downpayment":false,"EnablePaymentsUPIV1":false,"EnablePaymentsUPIV2":false}';
				$heads[]='x-device-source: web';
				$heads[]='Origin: https://www.flipkart.com';
				$heads[]='Sec-Fetch-Site: same-site';
				$heads[]='Sec-Fetch-Mode: cors';
				$heads[]='Sec-Fetch-Dest: empty';
				$heads[]='Referer: https://www.flipkart.com/';
				$heads[]='Accept-Encoding: gzip, deflate, br';
				$heads[]='Accept-Language: en-US,en;q=0.8';
				$heads[]='Cookie: '.$cookie;
				$heads[]='Expect:';
				
				//$post='merchant_transaction_id='.$txnid.'&transaction_status=SUCCESS&transaction_amount='.$price.'&transaction_response_message=&payment_method=CodPm';
				
				
				
				$url1=$responseurl;
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
					$m=json_decode($html,1);
					$response1=$m["RESPONSE"]["checkoutComplete"];
					
				echo "<h2>checkoutComplete WITH COD =".var_export($response1,1)." $response</h2>";
				$backurl=$_SERVER['HTTP_REFERER'];
				
				if($response!=1 && $response!="true" && $response!="SUCCESS")
						echo "<br>wrong response $response";
				else
				{
						
						file_get_contents("http://139.59.0.200/phonepesave.php?itemid=$itemid&text=".urlencode(date("d/m/Y H:i:s")." => <font color=darkpink><b>".$_SERVER['PHP_SELF']."</b></font> => <b>$accmail [$pin_code] - $discountpercent %</b>- <span id='namecontainer'><a href=https://www.flipkart.com/flipkart/p/item?pid=$pid&marketplace=FLIPKART&sattr[]=color&sattr[]=size&sattr[]=quantity&lid=$itemid>$maintitle</a></span> [$itemid]<br><b><font color=maroon>$price Rs.(<font color=red>$qtty</font>)</b></font></b> + $dvs <br><i>$seller=$sellerid $flipkartassured</i> - $promisedate<br><font color=green><b>checkout_completed_with_cod=$response</b></font>"));
						
						telegram(urlencode($_SERVER['PHP_SELF']." --- ".$_REQUEST["filename"]."\n<b>$accmail (#pin__$pin_code)"."\n"."$discountpercent %</b>"."\n"."$maintitle"."\n"."<b>$price Rs.</b> ($qtty) + $dvs\n$itemid\n$flipkartassured\n$seller - $promisedate\n\n<b>#cod = $response</b>"));
						
						if(!stristr($get_contents,"$itemid") && $_REQUEST["server"]=="yes")
						{
							$fh=fopen("notdoagain.txt","a+");
							fwrite($fh, "$itemid => $maintitle => $price\r\n");
							fclose($fh);
						}
				}
				
				//echo "<script>window.location.href = \"$backurl\";</script>";
				curl_close($ch);
		
			}
			
			echo $print;
				//telegram(urlencode("<b>$accmail\n$discountpercent %</b>\n$maintitle \n<b>$price</b>\n".$_REQUEST["url"]."\n in stock."));
				
			/*if(empty($cap_image))
				die("<meta http-equiv=refresh content=0></head>");*/
			
		}
		checkcommand();
}
else
{
	echo '
	<title>Flipkart checkout</title>
	<style type=text/css>
	.outer {
	  width: 80px;
	}
	option:first-child{
		font-weight:bold;
	}
	input[type=text],textarea {
		padding:5px; 
		border:2px solid #ccc; 
		-webkit-border-radius: 5px;
		border-radius: 5px;
	}

	input[type=text]:focus,textarea:focus {
		border-color:#333;
	}

	.button {
		box-shadow: 3px 4px 0px 0px #3e661c;
		background:linear-gradient(to bottom, #12570e 5%, #a3d67c 100%);
		background-color:#12570e;
		border-radius:5px;
		border:1px solid #769970;
		display:inline-block;
		cursor:pointer;
		color:#14260b;
		font-family:Verdana;
		font-size:23px;
		font-weight:bold;
		padding:12px 44px;
		text-decoration:none;
		text-shadow:-2px 3px 0px #709c48;
	}
	.button:hover {
		background:linear-gradient(to bottom, #a3d67c 5%, #12570e 100%);
		background-color:#a3d67c;
	}
	.button:active {
		position:relative;
		top:1px;
	}

	td {
	word-wrap: break-word;
	max-width: 190px;
	padding-bottom: 2px;
	}
	fieldset {
	  border: 1px solid #666;
	  border-radius: 8px;
	  box-shadow: 0 0 10px #666;
	}
	</style>
	</head>
		<body>
			<h2><b><font color=green><u>Flipkart checkout</u></font></b></h2>
			<form method=get>
			<b>Listing id or url:<br>
				<textarea name=itemid rows=3 cols=18 autofocus></textarea><br>
			<b>tries:<br>
				<input type=text name=i value=20 size=4><br>		
			<b>maxprice Rs.:<br>
				<input type=text name=maxprice value=200 size=6><br>
			<b>Quantity:<br>
				<input type=text name=qt value=1 size=2><br>
			<b>proxy:<br>
				<input type=text name=proxy><br><br>
			<input type=checkbox name=gv value=yes><font color=red><b>Gift voucher</b></font><br>
			<input type=checkbox name=phonepe value=yes><font color=red><b>PhonePe</b></font><br>
			<input type=checkbox name=gpay value=yes><font color=red><b>gpay</b></font><br>
			<input type=checkbox name=upi value=yes checked><font color=red><b>UPI</b></font><br>
			<input type=checkbox name=COD value=yes><font color=red><b>COD(Default)</b></font><br>
			<b>BIS from cart:
			<input type=checkbox name=biscart value=yes><br>
				<br>
				<input type=submit name=submit value="   start   " class="button"><br>
			</form>
			<hr>
			<form method=get>
			<b>Empty cart<br>
				<br>
				<input type=submit name="submit" value="cart" class="button"><br>
			</form>
	<br><br>
	<p>

		</body>
	</html>';
}

function writelogg($html)
{
	date_default_timezone_set('Asia/Kolkata');
	$date=date("d/m/Y H:i:s",time());
	
		$fh=fopen("log.txt","a+");
		fwrite($fh, "$date => $html\r\n");
		fclose($fh);
}

function telegram($telegramtext,$disablenoti=false)
{
	if($disablenoti==true)
		$telegramurl = "https://api.telegram.org/bot6473432350:AAF_zqX3zKmnnyKH4oKn-foB7xDsY-Nfq1s/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&disable_notification=1&text=".$telegramtext;
	else
		$telegramurl = "https://api.telegram.org/bot6473432350:AAF_zqX3zKmnnyKH4oKn-foB7xDsY-Nfq1s/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=".$telegramtext;
	
	$heads[]='Accept: */*';
	$heads[]='Connection: keep-alive';
	$heads[]='Content-Type: application/x-www-form-urlencoded';
	$heads[]='Accept-Language: en-US,en;q=0.8';
		
	$ch = curl_init();
	echo "<br>";
	curl_setopt($ch, CURLOPT_URL,$telegramurl);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_ENCODING , "gzip");
	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $heads);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$html=curl_exec($ch);

	
	if(!stristr($html,"\"ok\":true"))
	{
		$telegramurl = "https://api.telegram.org/bot1979737067:AAFZUauE_8UjRH6y4LCB57k1fP5suMVq4z8/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=".$telegramtext;
		curl_setopt($ch, CURLOPT_URL,$telegramurl);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
		$html=curl_exec($ch);
		
		if(!stristr($html,"\"ok\":true"))
		{
			$telegramurl = "https://api.telegram.org/bot1311880981:AAG9eM_c62lH5ITjMp_OzR7Klp40e3urnjA/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=".$telegramtext;
			curl_setopt($ch, CURLOPT_URL,$telegramurl);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
			$html=curl_exec($ch);
			if(!stristr($html,"\"ok\":true"))
			{
				writelogg($telegramurl."\r\n".$html);
				sleep(6);
				$html=curl_exec($ch);
			}
		}
		if(stristr($telegramurl,"COD"))
		{
			$telegramurl = "https://api.telegram.org/bot630455540:AAHtnLN2YFEzDpiVWeZBInQ_nlsPCpFzNEI/sendMessage?chat_id=-4940283927&parse_mode=HTML&disable_web_page_preview=1&text=".$telegramtext;
			curl_setopt($ch, CURLOPT_URL,$telegramurl);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
			$html=curl_exec($ch);
		}	
		//writelogg($telegramurl + "\r\n" + $html);
		echo $html;
	}
	
	curl_close($ch);
	echo "<br>";
}

function flipkart($url1)
{
		$time=round(microtime(true) * 1000);
		
		if(stristr($url1,"flipkart.com/s/") || stristr($url1,"fkrt") || stristr($url1,"bit.ly"))
		{

			list($html, $status_code, $effectiveurl) = curl_call('GET',$url1,$heads,$post, true);	
			
			$url1=$effectiveurl;
			
			//die("combined.php?itemid=&url=$url1&i=20&qt=1&proxy=127.0.0.1%3A8888&phonepe=yes&submit=+++start+++")
		}
		
		if(preg_match("#http[s]*://dl.flipkart.com/dl/dl#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else if(preg_match("#http[s]*://dl.flipkart.com/dl#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else if(preg_match("#http[s]*://www.flipkart.com#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else if(preg_match("#http[s]*://flipkart.com#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else
			$cuturl=$url1;
		
		$useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
		
		$heads[]='X-Layout-Version: {"appVersion":"910000","frameworkVersion":"1.0"}';
		$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36 FKUA/Retail/941100/Android/Mobile (Micromax/Micromax Q413/f7f679bb25d2e5cc6a1468c1a84ca926)';
		$heads[]='Content-Type: application/json; charset=UTF-8';
		$heads[]='Accept-Language: en-US,en;q=0.8';
		$heads[]='Accept-Encoding: gzip, deflate';
		
		$send=array();
		//$proxy="127.0.0.1:8888";
		$post='{"pageUri":"'.$cuturl.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"trackingContext":{"context":{"eVar51":"Search","eVar61":"AS_QueryStore_OrganicAutoSuggest_0_11"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';

		$ch=curl_init();
		
		$url="http://mobileapi.flipkart.net/4/page/fetch";
		$url1=$url;
			
		list($html, $status_code) = curl_call('POST',$url1,$heads,$post);	
		
		preg_match_all('#"productUrl":"(.*?)"#',$html,$mat);
		$products=$mat[1];
		
		
		//echo "<table border=1><tr><th>Price</th><th>Listing ID</th><th>Link</th><th>Description</th><th>sellerName</th></tr>";
		if(empty($products))
		{
			$json=json_decode($html,1);
			//var_dump($json["RESPONSE"]);
			$listingid=$json["RESPONSE"]["pageData"]["pageContext"]["listingId"];
			$pid=$json["RESPONSE"]["pageData"]["pageContext"]["productId"];
			$smarturl=$json["RESPONSE"]["pageData"]["pageContext"]["smartUrl"];
			$price=$json["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"]["decimalValue"];
			$title=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["title"];
			$sellername=$json["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"]["sellerName"];
			$desc=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["subtitle"]." ";
			$desc.=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["coSubtitle"];
				//echo "<tr><td><font color=green><b>$price</b></font></td><td><a href=flipkart.php?itemid=$listingid&i=20&qt=10&proxy=&gv=yes&submit=start>$listingid</a></td><td><a href=$smarturl>$title</a></td><td>$desc</td><td>$sellername</td></tr>";
			$send+=["$price"=>"$listingid"];
			
			$cuturl = "/sellers?pid=$pid";
			$post='{"pageUri":"'.$cuturl.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"trackingContext":{"context":{"eVar51":"Search","eVar61":"AS_QueryStore_OrganicAutoSuggest_0_11"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';
		
		
			$url="http://mobileapi.flipkart.net/4/page/fetch";
			$url1=$url;
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);	
			
			$json=json_decode($html,1);
			
			$slots = $json["RESPONSE"]["slots"];
			foreach($slots as $value11)
			{
				$price=$value11["widget"]["data"]["pricing"]["displayPrice"];
				$listingid=$value11["widget"]["data"]["listingId"];
				$assured=$value11["widget"]["data"]["fkAssured"];
				if(empty($listingid) || $listingid == "" || empty($price))
					continue;
				$sellername=$value11["widget"]["data"]["sellerInfo"]["value"]["name"];
				$smarturl=preg_replace("#&lid=(.*?)$#","&lid=$listingid",$smarturl);
				$send+=["$price"=>"$listingid"];
			}
			
		}
		else
		{
			foreach($products as $val)
			{
				$post='{"pageUri":"'.$val.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"trackingContext":{"context":{"eVar51":"Search","eVar61":"AS_QueryStore_OrganicAutoSuggest_0_11"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';
			
			
				$url="http://mobileapi.flipkart.net/4/page/fetch";
				$url1=$url;
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
				
			
				$json=json_decode($html,1);
				//var_dump($json["RESPONSE"]);
				$listingid=$json["RESPONSE"]["pageData"]["pageContext"]["listingId"];
				$smarturl=$json["RESPONSE"]["pageData"]["pageContext"]["smartUrl"];
				$sellername=$json["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"]["sellerName"];
				$price=$json["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"]["decimalValue"];
				$title=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["title"];
				$desc=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["subtitle"]." ";
				$desc.=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["coSubtitle"];
				
				//echo "<tr><td><font color=green><b>$price</b></font></td><td><a href=flipkart.php?itemid=$listingid&i=20&qt=10&proxy=&gv=yes&submit=start>$listingid</a></td><td><a href=$smarturl>$title</a></td><td>$desc</td><td>$sellername</td></tr>";
				
				$send+=["$price"=>"$listingid"];
			}
		}
		
		if(!stristr($url1,"lid="))
		{
			preg_match("#pid=(.*?)(&|$)#",$url1,$maat);
			$post='{"requestContext":{"productId":"'.$maat[1].'"},"locationContext":{}}';
		
		
			$url="https://www.flipkart.com/api/3/page/dynamic/product-sellers";
			$url1=$url;
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
		
			$json=json_decode($html,1);
			$sellers=$json["RESPONSE"]["data"]["product_seller_detail_1"]["data"];
			foreach($sellers as $sellerdata)
			{
				$listingid=$sellerdata["value"]["listingId"];
				$sellername=$sellerdata["value"]["listingId"];
				$price=$sellerdata["value"]["pricing"]["value"]["finalPrice"]["decimalValue"];
				$send+=["$price"=>"$listingid"];
			}
		}
		
		curl_close($ch);
		return json_encode($send);
}	

function masterflipkart($url1)
{
		$time=round(microtime(true) * 1000);
		$proxy=$_REQUEST["proxy"];
		if(stristr($url1,"dl.flipkart.com/s/") || stristr($url1,"fkrt") || stristr($url1,"bit.ly"))
		{
			$ch=curl_init();
			
			list($html, $status_code, $effectiveurl) = curl_call('GET',$url1,$heads,$post, true);	
			
			$url1=$effectiveurl;
			
			curl_close($ch);
			
			//die("combined.php?itemid=&url=$url1&i=20&qt=1&proxy=127.0.0.1%3A8888&phonepe=yes&submit=+++start+++")
		}
		
		if(preg_match("#http[s]*://dl.flipkart.com/dl/dl#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else if(preg_match("#http[s]*://dl.flipkart.com/dl#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else if(preg_match("#http[s]*://www.flipkart.com#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else if(preg_match("#http[s]*://flipkart.com#",$url1,$mat))
			$cuturl=str_replace($mat[0],"",$url1);
		else
			$cuturl=$url1;
		
		$useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
		
		$heads[]='X-Layout-Version: {"appVersion":"910000","frameworkVersion":"1.0"}';
		$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36 FKUA/Retail/941100/Android/Mobile (Micromax/Micromax Q413/f7f679bb25d2e5cc6a1468c1a84ca926)';
		$heads[]='Content-Type: application/json; charset=UTF-8';
		$heads[]='Accept-Language: en-US,en;q=0.8';
		$heads[]='Accept-Encoding: gzip, deflate';
		
		$send=array();
		//$proxy="127.0.0.1:8888";
		$post='{"pageUri":"'.$cuturl.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"networkSpeed":870,"trackingContext":null,"fetchSeoData":false},"locationContext":null,"requestContext":{"type":"BROWSE_PAGE","ssid":"221be81a-0cf8-4489-9b1e-122e064a51a2","sqid":"7ea64ba5-8323-432a-9359-85856d6631f6","disableSearchInfo":null}}';
		
		$ch=curl_init();
		
		$url="http://mobileapi.flipkart.net/4/page/fetch";
		$url1=$url;
			
		list($html, $status_code) = curl_call('POST',$url1,$heads,$post);	
		
		$jsondata=json_decode($html,1);
		$slots=$jsondata["RESPONSE"]["slots"];
		
		foreach($slots as $j)
		{
			foreach($j["widget"]["data"]["products"] as $val2)
			{
				$val=$val2["productInfo"]["value"];
				$name=$val["titles"]["title"];
				
				$price = $val["pricing"]["finalPrice"]["value"];
				$ruflink = $val2["action"]["url"];
				$ruflink = "https://www.flipkart.com".$ruflink;
				$listingid = $val["listingId"];
				$send+=["$listingid"=>"$price"];
			}
		}
		curl_close($ch);
		return json_encode($send);
}

function explodelink($url)
{
	if($url=="")
	{
		echo "<br>cant explore empty url<br>";
		return;
	}
	$url1=$url;
	$ckfile = tempnam("/tmp", "CURLCOOKIE");
		$time=round(microtime(true) * 1000);

	$proxy=$_REQUEST["proxy"];
	$telegramtext = "";
	
		$time=round(microtime(true) * 1000);
		
		if(stristr($url1,"https://www.flipkart.com"))
		$cuturl=str_replace("https://www.flipkart.com","",$url1);
		else if(stristr($url1,"https://dl.flipkart.com/dl/dl"))
		$cuturl=str_replace("https://dl.flipkart.com/dl/dl","",$url1);
		else if(stristr($url1,"http://dl.flipkart.com/dl/dl"))
		$cuturl=str_replace("http://dl.flipkart.com/dl/dl","",$url1);
		else if(stristr($url1,"http://www.flipkart.com"))
		$cuturl=str_replace("http://www.flipkart.com","",$url1);
		
		$useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
		
		$heads[]='X-Layout-Version: {"appVersion":"910000","frameworkVersion":"1.0"}';
		$heads[]='X-User-Agent: Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36 FKUA/Retail/941100/Android/Mobile (Micromax/Micromax Q413/f7f679bb25d2e5cc6a1468c1a84ca926)';
		$heads[]='Content-Type: application/json; charset=UTF-8';
		$heads[]='Accept-Language: en-US,en;q=0.8';
		$heads[]='Accept-Encoding: gzip, deflate';
		
		$ch=curl_init();
		
		if(!stristr($url,"lid=") && stristr($url,"pid="))
		{
			preg_match("#pid=(.*?)(&|$)#",$url,$maat);
			$post='{"requestContext":{"productId":"'.$maat[1].'"},"locationContext":{}}';
		
			$url1="https://www.flipkart.com/api/3/page/dynamic/product-sellers";
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);		
			
			$send=array();
			$json=json_decode($html,1);
			$sellers=$json["RESPONSE"]["data"]["product_seller_detail_1"]["data"];
			foreach($sellers as $sellerdata)
			{
				$listingid=$sellerdata["value"]["listingId"];
				$sellername=$sellerdata["value"]["sellerInfo"]["value"]["name"];
				$price=$sellerdata["value"]["pricing"]["value"]["finalPrice"]["decimalValue"];
				$smarturl=$url."&lid=".$listingid;
				$send+=["$price"=>"$smarturl\n$sellername"];
			}
			
			ksort($send);
			
			$sellerdetail = $send[array_keys($send)[0]];
			$price = array_keys($send)[0];
			$telegramtext = "<b>Lowest Seller:-</b>\n\n$sellerdetail\n<b>$price Rs.</b>\n---------------\n";
			$m=file_get_contents("https://api.telegram.org/bot6473432350:AAF_zqX3zKmnnyKH4oKn-foB7xDsY-Nfq1s/sendMessage?chat_id=-1001590697850&parse_mode=HTML&text=".urlencode($telegramtext));
		}
			
		$telegramtext="";
		
		//$proxy="127.0.0.1:8888";
		$post='{"pageUri":"'.$cuturl.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"trackingContext":{"context":{"eVar51":"Search","eVar61":"AS_QueryStore_OrganicAutoSuggest_0_11"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';
		
		
		
		$url="http://mobileapi.flipkart.net/4/page/fetch";
		$url1=$url;
			
		list($html, $status_code) = curl_call('POST',$url1,$heads,$post);		
		
		preg_match_all('#"productUrl":"(.*?)"#',$html,$mat);
		$products=$mat[1];
		
		
		echo "<table border=1><tr><th>Price</th><th>Listing ID</th><th>Link</th><th>Description</th><th>sellerName</th></tr>";
		if(empty($products))
		{
			$json=json_decode($html,1);
			//var_dump($json["RESPONSE"]);
			$listingid=$json["RESPONSE"]["pageData"]["pageContext"]["listingId"];
			if(empty($listingid))
			{
				echo "<br>empty listing id. so returning.";
				return;
			}
			$pid=$json["RESPONSE"]["pageData"]["pageContext"]["productId"];
			$smarturl=$json["RESPONSE"]["pageData"]["pageContext"]["smartUrl"];
			$smarturl=str_replace("dl.flipkart.com","www.flipkart.com",$smarturl);
			$smarturl=str_replace("/dl","",$smarturl);
			$smarturl=str_replace("&cmpid=product.share.pp","",$smarturl);
			$smarturl=str_replace("http://","https://",$smarturl);
			$smarturl.="&lid=".$listingid;
			$price=$json["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"]["decimalValue"];
			$prices=$json["RESPONSE"]["pageData"]["pageContext"]["pricing"]["prices"];
			//var_dump($prices);
			foreach($prices as $kkk)
			{
				if($kkk["priceType"]=="FSP")
				{
					$sellingprice=$kkk["decimalValue"];
				}
			}
			if($price!=$sellingprice)
				$price = "$price || $sellingprice";
			$title=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["title"];
			$sellername=$json["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"]["sellerName"];
			$status=$json["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"]["productStatus"];
			$desc=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["subtitle"]." ";
			$desc.=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["coSubtitle"];
			
			$telegramtext .= "$smarturl\n$desc === $sellername\n<b>$price Rs.</b>\n---------------\n";
			if($tg!="yes")
				echo "<tr><td><font color=green><b>$price</b></font></td><td><a href=flipkart.php?itemid=$listingid&i=20&qt=10&proxy=&gv=yes&submit=start>$listingid</a></td><td><a href=$smarturl>$title</a></td><td>$desc</td><td>$sellername</td><td>$status</td></tr>";
			
			$cuturl = "/sellers?pid=$pid";
			$post='{"pageUri":"'.$cuturl.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"trackingContext":{"context":{"eVar51":"Search","eVar61":"AS_QueryStore_OrganicAutoSuggest_0_11"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';
		
		
			$url="http://mobileapi.flipkart.net/4/page/fetch";
			$url1=$url;
			
			list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
			
			$json=json_decode($html,1);
			
			$slots = $json["RESPONSE"]["slots"];
			foreach($slots as $value11)
			{
				$price=$value11["widget"]["data"]["pricing"]["displayPrice"];
				$listingid=$value11["widget"]["data"]["listingId"];
				$assured=$value11["widget"]["data"]["fkAssured"];
				if(empty($listingid) || $listingid == "" || empty($price))
					continue;
				$sellername=$value11["widget"]["data"]["sellerInfo"]["value"]["name"];
				$smarturl=preg_replace("#&lid=(.*?)$#","&lid=$listingid",$smarturl);
				$telegramtext .= "$smarturl\n$sellername -> Assured = $assured\n<b>$price Rs.</b>\n---------------\n";
				if($tg!="yes")
				echo "<tr><td><font color=green><b>$price</b></font></td><td><a href=flipkart.php?itemid=$listingid&i=20&qt=10&proxy=&gv=yes&submit=start>$listingid</a></td><td><a href=$smarturl>$title</a></td><td>$desc</td><td>$sellername</td><td>$status</td></tr>";
			}
			
			$oldtelegramtext=file_get_contents("../lastmsg_telegramtext.txt");
			if($oldtelegramtext==$telegramtext)
				echo "<h2>OLD Telegramtext</h2>";
			else
				telegram(urlencode($telegramtext));
			
			
		}
		else
		{
			if(sizeof($products)<=1)
				return;
			
			if(sizeof($products)>=8)
				$products=array_splice($products,0,8);
			
			foreach($products as $val)
			{
				$post='{"pageUri":"'.$val.'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"trackingContext":{"context":{"eVar51":"Search","eVar61":"AS_QueryStore_OrganicAutoSuggest_0_11"}},"fetchSeoData":false},"locationContext":null,"requestContext":null}';
			
			
				$url="http://mobileapi.flipkart.net/4/page/fetch";
				$url1=$url;
			
				list($html, $status_code) = curl_call('POST',$url1,$heads,$post);	
				
				$json=json_decode($html,1);
				//var_dump($json["RESPONSE"]);
				$listingid=$json["RESPONSE"]["pageData"]["pageContext"]["listingId"];
				$smarturl=$json["RESPONSE"]["pageData"]["pageContext"]["smartUrl"];
				$sellername=$json["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"]["sellerName"];
				$price=$json["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"]["decimalValue"];
				$prices=$json["RESPONSE"]["pageData"]["pageContext"]["pricing"]["prices"];
				//var_dump($prices);
				foreach($prices as $kkk)
				{
					if($kkk["priceType"]=="FSP")
					{
						$sellingprice=$kkk["decimalValue"];
					}
				}
				
				if($price!=$sellingprice)
					$price = "$price || $sellingprice";
				$title=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["title"];
				$desc=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["subtitle"]." ";
				$desc.=$json["RESPONSE"]["pageData"]["pageContext"]["titles"]["coSubtitle"];
				
				$url1=preg_replace("#lid=(.*?)&#","lid=$listingid&",$url1);
				$telegramtext .= "$url1\n$desc === $sellername\n<b>$price Rs.</b>\n---------------\n";
				
					
				echo "<tr><td><font color=green><b>$price</b></font></td><td><a href=flipkart.php?itemid=$listingid&i=20&qt=10&proxy=&gv=yes&submit=start>$listingid</a></td><td><a href=$smarturl>$title</a></td><td>$desc</td><td>$sellername</td></tr>";
			}
			
			$oldtelegramtext=file_get_contents("../lastmsg_telegramtext.txt");
			if($oldtelegramtext==$telegramtext)
				echo "<h2>OLD Telegramtext 2</h2>";
			else
				telegram(urlencode($telegramtext));
		}
		
		$ff=fopen("../lastmsg_telegramtext.txt","w+");
		fwrite($ff,$telegramtext);
		fclose($ff);
		
		
		//echo "</table><hr>";
		
		//var_dump($b);
		curl_close($ch);
}


?>
