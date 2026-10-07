<?php
function curl_call($method, $url, $headers, $data, $onlyget=false)
{
	if(stristr(php_uname(),"Windows"))
	{
		global $proxy;
		//$proxy="127.0.0.1:8888";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 5.1; Micromax Q413 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.91 Mobile Safari/537.36"); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
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
		return array($html, $code);
	}
	else
	{
		$cmd='';
		if($method=="POST")
		{
			$cmd.='../curl_chrome_android -w "\nRESPONSE_CODE->%{http_code}" -X POST \''.$url.'\' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
			$cmd.='-d \''.$data.'\'';
		}
		else if($onlyget==true)
		{
			$cmd.='../curl_chrome_android -L -w "\nRESPONSE_CODE->%{http_code}==>%{url_effective}" \''.$url.'\' ';
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
			$cmd.='../curl_chrome_android -w "\nRESPONSE_CODE->%{http_code}" \''.$url.'\' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
		}
		
		$cmd.=' --compressed';
		//echo "<p><code>$url<br>$data</code></p>";
		$output = shell_exec($cmd);
		$data=explode("\nRESPONSE_CODE->",$output);
		return array($data[0],$data[1]);
	}
}

if($_REQUEST["submit"])
{	
	$sellerid=$_REQUEST["sellerid"];
	$itemid=$_REQUEST["itemid"];
	$pid=$_REQUEST["pid"];
	$cookie=file_get_contents("flip.txt");
	$get_contents=file_get_contents("sellerrecords.txt");
	if(stristr($get_contents,$sellerid))
	{
		$myrecord1=json_decode($get_contents,1);
		//echo "found in record<br>";
		echo json_encode($myrecord1["sellers"][$sellerid]);
		die;
	}

$heads[]='X-User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop';
		$heads[]='Accept: */*';
		$heads[]='Cookie: '.$cookie;
		$heads[]='Connection: keep-alive';
		$heads[]='Origin: https://www.flipkart.com';
		$heads[]='Content-Type: application/json';
		$heads[]='Accept-Language: en-US,en;q=0.8';
		
		$url1="https://www.flipkart.com/api/4/page/fetch";
		
		$post='{"pageUri":"/seller-details?sellerId='.$sellerid.'&pid='.$pid.'&marketplace=FLIPKART&lid='.$itemid.'"}';
		
		list($html, $status_code) = curl_call('POST',$url1,$heads,$post);
		
		$html=json_encode(json_decode($html), JSON_PRETTY_PRINT);
		//echo $html;
		$json1=json_decode($html,1);
		$myrecord=array();
		$myrecord["productrating"]=$json1["RESPONSE"]["slots"][0]["widget"]["data"]["parameterizedScoreComponent"][0]["value"]["score"];
		$myrecord["servicerating"]=$json1["RESPONSE"]["slots"][0]["widget"]["data"]["parameterizedScoreComponent"][1]["value"]["score"];
		$myrecord["rating"]=$json1["RESPONSE"]["slots"][0]["widget"]["data"]["ratingsComponent"]["value"]["score"];
		$myrecord["age"]=$json1["RESPONSE"]["slots"][0]["widget"]["data"]["tableComponents"]["value"]["values"][0]["value"];
		$myrecord["sellername"]=$json1["RESPONSE"]["slots"][0]["widget"]["data"]["titleComponent"]["value"]["text"];
		
		$myrecord1=json_decode($get_contents,1);
		//print_r($myrecord1);
		$myrecord1["sellers"][$sellerid]=$myrecord;
		$fh=fopen("sellerrecords.txt","w+");
		fwrite($fh,json_encode($myrecord1, JSON_PRETTY_PRINT));
		fclose($fh);
		
		$myrecord=json_encode($myrecord, JSON_PRETTY_PRINT);
		echo $myrecord;
		
}
else{
	echo '<html><body>
			<h2><b><font color=green><u>Flipkart sellerinfo</u></font></b></h2>
			<form method=get>
			<b>sellerid:<br>
				<textarea name=sellerid rows=3 cols=18 autofocus></textarea><br>
			<b>pid:<br>
				<input type=text name=pid><br>		
			<b>itemid:<br>
				<input type=text name=itemid><br><br>
			
				<input type=submit name=submit value="   start   " class="button"><br>
			</form>
			</body></html>';
}