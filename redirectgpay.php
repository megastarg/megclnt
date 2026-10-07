<?php
error_reporting(0);
$url=$_REQUEST["url"];
$ip=$_SERVER['REMOTE_ADDR'];

echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

$ff=file_get_contents("linkclickedby.txt");
$fh=fopen("linkclickedby.txt","a+");
fwrite($fh,$url." => ".$ip."\r\n");
fclose($fh);

if(stristr($url,"upi://"))
{
	//if((time()-(int)$_REQUEST["ts"])>=720)
	//	die("<h1><font color=red>12 minutes over</font><br><br>$url</h1>");
	if(!stristr($ff,"$url"))
		echo "<script>window.location = \"".$url."\";</script>";
	else
		echo "<hr><font color=red>link clicked by someone</font><br><br>";
	echo "<a href=$url>$url</a>";
die;
}

$acsurl=$_REQUEST["acsurl"];
$ch=curl_init();
		
$proxy="";

$heads[]='cache-control: max-age=0';
$heads[]='sec-ch-ua: "Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"';
$heads[]='sec-ch-ua-mobile: ?1';
$heads[]='sec-ch-ua-platform: "Android"';
$heads[]='upgrade-insecure-requests: 1';
$heads[]='origin: https://www.flipkart.com';
$heads[]='content-type: application/x-www-form-urlencoded';
$heads[]='user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36';
$heads[]='accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
$heads[]='sec-fetch-site: cross-site';
$heads[]='sec-fetch-mode: navigate';
$heads[]='sec-fetch-user: ?1';
$heads[]='sec-fetch-dest: document';
$heads[]='referer: https://www.flipkart.com/';
$heads[]='accept-encoding: gzip, deflate, br';
$heads[]='accept-language: en-US,en;q=0.9';
		
		$post='acsurl='.urlencode($acsurl);
		
		
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $heads);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html=curl_exec($ch);

/*
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($statusCode == 301 || $statusCode == 302 || $statusCode == 303)
{
    $headerLength = curl_getinfo($curlHandle, CURLINFO_HEADER_SIZE);
    $responseHeaders = substr($redirectResponse, 0, $headerLength);
    $redirectUrl = getLocationHeader($responseHeaders);
}
function getLocationHeader($responseHeaders)
    {
        if (preg_match('/Location:(.+)Vary/is', $redirectResponse, $loc))
        {
            $location = trim($loc[1]);
            return $location;
        }
        return FALSE;
    }

*/

$headerSize = curl_getinfo( $ch , CURLINFO_HEADER_SIZE );
$headerStr = substr( $html , 0 , $headerSize );
$bodyStr = substr( $html , $headerSize );
$headers = headersToArray( $headerStr );
$location=$headers["location"];
if(empty($location))
{
	$location=$headers["Location"];
}
if(empty($location))
{
	preg_match("#location: (.*?)$#i",$html,$mat);
	$location=$mat[1];
}

if($location!="")
{
//header("location: ".trim($headers["Location"]));
if(!stristr($ff,"$url"))
	echo "<script>window.location = \"".$location."\";</script>";
else
	echo "<hr><font color=red>link clicked by someone</font><br><a href=$location>$location</a>";

echo 'setTimeout(callBack_func, 6000);
function callBack_func() {
   document.location.href = '.$location.';
}';
}
else
{
    echo $bodyStr;
}


function headersToArray( $str )
{
    $headers = array();
    $headersTmpArray = explode( "\r\n" , $str );
    for ( $i = 0 ; $i < count( $headersTmpArray ) ; ++$i )
    {
        // we dont care about the two \r\n lines at the end of the headers
        if ( strlen( $headersTmpArray[$i] ) > 0 )
        {
            // the headers start with HTTP status codes, which do not contain a colon so we can filter them out too
            if ( strpos( $headersTmpArray[$i] , ":" ) )
            {
                $headerName = substr( $headersTmpArray[$i] , 0 , strpos( $headersTmpArray[$i] , ":" ) );
                $headerValue = substr( $headersTmpArray[$i] , strpos( $headersTmpArray[$i] , ":" )+1 );
                $headers[$headerName] = $headerValue;
            }
        }
    }
    return $headers;
}

?>