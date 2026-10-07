<?php
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_implicit_flush(true);
ob_end_flush();


echo '
<html>
<head>
<style type=text/css>
option:first-child{
    font-weight:bold;
}</style><title>plus</title>';

flush();
ob_flush();
					
date_default_timezone_set('Asia/Kolkata');

function usemethod($phonepe)
{
	$availablemethods=array("cod=yes","upi=yes","gpay=yes","upi=yes","gpay=yes");
	if($phonepe=="yes")
	{
		$random_key=array_rand($availablemethods,1);
		return $availablemethods[$random_key];
	}
	else
		return "cod=yes";
}


$url=$_REQUEST["url"];
$minx=$_REQUEST["i"];
$maxx=$maxe=$_REQUEST["maxe"];
$qt=$_REQUEST["qt"];
$server=isset($_REQUEST['server']) ? $_REQUEST['server'] : 'no';
$refresh=$_REQUEST["refresh"];
$filename=isset($_REQUEST['filename']) ? $_REQUEST['filename'] : 'app';
if($_REQUEST["bis"]=="yes")
	$filename = "bis";
$sellerid=$_REQUEST["sellerid"];
$itemid=$_REQUEST["itemid"];
//$phonepe=$_REQUEST["phonepe"];
//$cod=$_REQUEST["cod"];
$cod="no";
$phonepe="yes";
$discount=$_REQUEST["discount"];
$name=urlencode($_REQUEST["name"]);
$pid=$_REQUEST["pid"];
//$phonepe="gpay";

$hour=date('H');
if($hour>=2 && $hour<=7)
{
	$phonepe="no";
	$cod="yes";
}

$content_of_checkoutfile=file_get_contents("stopcheckout.txt");
$servername_checkoutfile=explode("-",$content_of_checkoutfile);
array_pop($servername_checkoutfile);
$servername_fromfilename=explode("/",$filename)[0]."/";


if(stristr($content_of_checkoutfile,"stop") && stristr($content_of_checkoutfile,"bis"))
{
   telegram(urlencode("bis checkout stopped ".file_get_contents("http://157.230.47.101/ip.php")));
   die("bis checkout stopped");
}

if(stristr($content_of_checkoutfile,"stop") && $_REQUEST["bis"]!="yes")
{
    if(stristr($content_of_checkoutfile,"ALL"))
    {
        telegram(urlencode("ALL checkout stopped ".file_get_contents("http://157.230.47.101/ip.php")));
        die("ALL checkout stopped");
    }
    else if(stristr($content_of_checkoutfile,$servername_fromfilename))
    {
        telegram(urlencode($servername_fromfilename." checkout stopped ".file_get_contents("http://157.230.47.101/ip.php")));
        die($servername_fromfilename." checkout stopped");
    }
}
else if(stristr($content_of_checkoutfile,"start") && $_REQUEST["bis"]!="yes")
{
    if(!stristr($content_of_checkoutfile,"ALL"))
    {
        if(!stristr($content_of_checkoutfile,$servername_fromfilename))
        {
            telegram(urlencode($servername_fromfilename." checkout stopped ".file_get_contents("http://157.230.47.101/ip.php")));
            die($servername_fromfilename." checkout stopped");
        }
    }
}

$dataa=array();
if(empty($_REQUEST["checkouton"]) || $_REQUEST["checkouton"]=="secondtype")
{	
	array_push($dataa,
	"http://139.59.0.200/fkart/auton5/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton5/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton6/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton6/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton7/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton7/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton8/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton8/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/megaSeth/d12/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d1819/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d45/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d45/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d20/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d67/np.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);
}
else if($_REQUEST["checkouton"]=="auto")
{
	array_push($dataa,
	"http://139.59.0.200/fkart/auto8/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto7/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto10/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto11/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto12/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto13/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto14/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);
}
else if($_REQUEST["checkouton"]=="none")
{
}
else if($_REQUEST["checkouton"]=="mega")
{
	array_push($dataa,
	"http://139.59.0.200/fkart/mega1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);

}
else if($_REQUEST["checkouton"]=="rajat")
{
	array_push($dataa,
	"http://139.59.0.200/fkart/rajat1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);

}
else if($_REQUEST["checkouton"]=="kumar")
{
	array_push($dataa,
	"http://139.59.0.200/fkart/kumar1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);

}
else if($_REQUEST["checkouton"]=="sabka")
{
	array_push($dataa,
	"http://139.59.0.200/fkart/auto1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto5/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto6/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto7/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);

}
else
{
	array_push($dataa,
	"http://139.59.0.200/fkart/auto1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto3/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto5/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto6/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto7/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);
}

$r = multiRequest($dataa);	
				
print_r($r);

$ttf=filemtime("lastres.html");
$ct=time();
if(abs($ct-$ttf)<=14400)
	$fh=fopen("lastres.html","a+");
else
	$fh=fopen("lastres.html","w+");
fwrite($fh, date("d/m/Y H:i:s")."<br>".$itemid."<br>");
fwrite($fh, str_replace("content=0","content=1000000",print_r($r, TRUE)));
//fwrite($fh, '<script>window.stop();document.execCommand("Stop");</script>');
fclose($fh);



//if((int)$maxe<20 || stristr($filename,"mob") || stristr($filename,"lap") || stristr($filename,"tv") || stristr($filename,"AC_"))
{
	if(stristr(join(" ",$r),"https://mercury") || stristr(join(" ",$r),"http://139"))
	{
		$txtx=urlencode("payment karo <b>$maxe</b> Rs. $filename");
		$dataa = array(
			"https://api.callmebot.com/start.php?user=@Sandeep88822&text=$txtx",
			"https://api.callmebot.com/start.php?user=@myself0011&text=$txtx",
			"https://api.callmebot.com/start.php?user=@karna_009&text=$txtx",
			"https://api.callmebot.com/start.php?user=@KumarRaja143&text=$txtx",
			"https://api.callmebot.com/start.php?user=@rajatk596&text=$txtx",
			"https://api.callmebot.com/start.php?user=@Viraj9334&text=$txtx"
		);
		exec("python3 ./pytgcalls/main1.py @karna_009 > opt.txt 2>&1 &");
		exec("python3 ./pytgcalls1/main1.py @myself0011 > opt1.txt 2>&1 &");
	}
	else
	{
		$dataa = array();
		/*if(stristr($filename,"bis"))
		{
			$txtx=urlencode("link not generated but may be loot. use BIS <b>$maxe</b> Rs.   $filename");
			$dataa = array(
				"https://api.callmebot.com/start.php?user=@myself0011&text=$txtx",
				"https://api.callmebot.com/start.php?user=@karna_009&text=$txtx",
				"https://api.callmebot.com/start.php?user=@rajatk596&text=$txtx"
				);
		}*/
	}
	
	$r = multiRequest($dataa);
}



function multiRequest($data, $options = array()) 
{
  $curly = array();

  $result = array();
 

  $mh = curl_multi_init();
 

  foreach ($data as $id => $d) {
 
    $curly[$id] = curl_init();
 
    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
    curl_setopt($curly[$id], CURLOPT_URL,            $url);
    curl_setopt($curly[$id], CURLOPT_HEADER,         0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
 

    if (is_array($d)) {
      if (!empty($d['post'])) {
        curl_setopt($curly[$id], CURLOPT_POST,       1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
    }

    if (!empty($options)) {
      curl_setopt_array($curly[$id], $options);
    }
 
    curl_multi_add_handle($mh, $curly[$id]);
  }

  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
 

  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
 
  curl_multi_close($mh);
 
  return $result;
}

function telegram($telegramtext)
{
	if(stristr($telegramtext," "))
		$telegramtext=urlencode($telegramtext);

	$telegramurl = "https://api.telegram.org/bot923259452:AAG1tBRBM7PIIYUL1g789IP4tBMgsI8uOJg/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=".$telegramtext;
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
	//curl_setopt($ch, CURLOPT_PROXY, $proxy);
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
			$telegramurl = "https://api.telegram.org/bot630455540:AAHtnLN2YFEzDpiVWeZBInQ_nlsPCpFzNEI/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=".$telegramtext;
			curl_setopt($ch, CURLOPT_URL,$telegramurl);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36" ); 
			$html=curl_exec($ch);
			if(!stristr($html,"\"ok\":true"))
			{
				//writelogg($telegramurl."\r\n".$html);
				sleep(6);
				$html=curl_exec($ch);
			}
		}
		//writelogg($telegramurl."\r\n".$html);
		echo $html;
	}
	
	curl_close($ch);
	echo "<br>";
}



?>