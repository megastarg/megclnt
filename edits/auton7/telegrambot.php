<?php
function checkcommand()
{
	$filename="stop.txt";
	$get_contents=file_get_contents($filename);
	$get_json=json_decode($get_contents,1);
	
	$url = "https://api.telegram.org/bot923259452:AAG1tBRBM7PIIYUL1g789IP4tBMgsI8uOJg/getUpdates?offset=-10";
	
	$headers[]='Accept-Language: en-GB,en-US;q=0.9,en;q=0.8';
	$headers[]='Accept: */*';
	
	$useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36";
	
	$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url");
		curl_setopt($ch, CURLOPT_USERAGENT, "$useragent" ); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html=curl_exec($ch);
		
	$messages=json_decode($html,1);
	
	foreach($messages["result"] as $val)
	{
		$senderid=$val["message"]["from"]["id"];
		$text=$val["message"]["text"];
		
		/*if(!stristr($text,"keyword") && !stristr($text,"asin") && !stristr($text,"delete") && !stristr($text,"block") && !stristr($text,"specialcase"))
		{
            $url = "https://api.telegram.org/bot923259452:AAG1tBRBM7PIIYUL1g789IP4tBMgsI8uOJg/sendMessage?chat_id=$senderid&parse_mode=HTML&text=".urlencode("<b>You wrote wrong pattern.Please retry with \"keyword KEYWORD\" or \"asin KEYWORD\" or \"delete KEYWORD\"</b>");
			curl_setopt($ch, CURLOPT_URL, "$url");
			$html=curl_exec($ch);
		}*/
		if(stristr($text,"stopflipkart"))
		{
			$text=str_ireplace("stopflipkart ","",trim($text));
			$ffname="stop.txt";
			$ffget_contents=file_get_contents($ffname);
			
			if(!stristr($ffget_contents,"$text"))
			{
				$f=fopen($ffname,"w+");
				// exclusive lock
				if (flock($f,LOCK_EX)) {
				  fwrite($f,$text);
				  fflush($f);
				  // release lock
				  flock($f,LOCK_UN);
				} else {
				  echo "Error locking file!";
				}
				fclose($f);
				
				$ffget_contents=file_get_contents($ffname);
				
				$url = "https://api.telegram.org/bot1311880981:AAG9eM_c62lH5ITjMp_OzR7Klp40e3urnjA/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=".urlencode("<b>stopFlipkart Command = $ffget_contents saved</b>");
				curl_setopt($ch, CURLOPT_URL, "$url");
				$html=curl_exec($ch);
			}	
		}
	
	}
}

function stopcheckout()
{
	$get_contents=file_get_contents("stop.txt");
	if($get_contents=="true" || $get_contents=="1" || $get_contents=="stop" || $get_contents=="yes")
		return 1;
	else
		return 0;
}

function checkstop()
{
	$proceed_or_not=file_get_contents("stop.txt");
		if(stristr($proceed_or_not,"stop") && $_REQUEST["server"]=="yes")
		{
			if(stopcheckout()==1)
				die(telegram(urlencode($_SERVER['REQUEST_URI']."\n<b>checkout stopped</b>")));
		}
}
?>