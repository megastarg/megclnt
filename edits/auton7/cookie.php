<?php
	set_time_limit(0);
error_reporting(0);
$print='<html>
<head>
<style type=text/css>
option:first-child{
    font-weight:bold;
}</style>
    ';

date_default_timezone_set('Asia/Kolkata');

function curl_call($method, $url, $headers, $data, $onlyget=false)
{
	if(stristr(php_uname(),"Windows"))
	{
		global $proxy;
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36"); 
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
			$cmd.='../curl_chrome110 -w "\nRESPONSE_CODE->%{http_code}" -X POST '.$url.' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
			$cmd.='-d \''.$data.'\'';
		}
		else if($onlyget==true)
		{
			$cmd.='../curl_chrome110 -L -w "\nRESPONSE_CODE->%{http_code}==>%{url_effective}" '.$url.' ';
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
			$cmd.='../curl_chrome110 -w "\nRESPONSE_CODE->%{http_code}" '.$url.' ';
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



if($_REQUEST["submit"]=="update")
{
	$ff=$_REQUEST["file"];
	echo "<h3>Before:</h3>",file_get_contents("$ff"),"<hr>";
	
	$ck=$_REQUEST["coki"];
	
	$fh=fopen("$ff","w+");
	fwrite($fh, "$ck");
	fclose($fh);
	
	echo "<h3>After:</h3>",file_get_contents("$ff");
}
else if($_REQUEST["test"]=="yes")
{
	$ff=$_REQUEST["file"];
	$cookie=file_get_contents("$ff");
	$heads[]='X-User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop';
	$heads[]='Accept: */*';
	$heads[]='Cookie: '.$cookie;
	$heads[]='Connection: keep-alive';
	$heads[]='Origin: https://www.flipkart.com';
	$heads[]='Content-Type: application/json';
	$heads[]='Accept-Language: en-US,en;q=0.8';
		
	$url1="https://www.flipkart.com/api/2/wallet/balance";
		
	
		
	list($html, $status_code) = curl_call('GET',$url1,$heads,null,true);
	echo $status_code."<br>";
	$jss=json_decode($html,1);
	$accmail=$jss["SESSION"]["email"] . " " . $jss["SESSION"]["accountId"];

	echo $accmail;

}
else
{			   

echo '
<title>cookie</title>
<script>
function test()
{
var text=document.getElementById("file").value;
console.log(text);
var win = window.open("cookie.php?file="+text+"&test=yes", "_blank");
}
</script>
</head>
    <body>
        <h2><b><font color=green><u>cookie</u></font></b></h2>
        <form method=post>
		<b>Enter cookie:<br>
				<textarea name=coki rows=8 cols=20></textarea><br>
		<b>Select File to update:<br>';
$filelist = glob("*.txt");
//$filelist = array_merge($filelist,glob("np*.txt"));
//$filelist = array_merge($filelist,"notdoagain.txt");
echo "<select name=file id=file>";
foreach($filelist as $val)
echo "<option>$val</option>";
echo "</select>";
		
			echo '<br><br>
            <input type=submit name=submit value="update">&nbsp;<button type="button" onclick="test()">test</button> <br>
        </form>
<br><br>
<p>

    </body>
</html>';
}