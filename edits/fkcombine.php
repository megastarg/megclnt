<?php

set_time_limit(0);
error_reporting(0);
ob_implicit_flush(true);
ob_end_flush();


echo '
<html>
<head>
<style type=text/css>
option:first-child{
    font-weight:bold;
}</style><title>rajat</title>
    ';
	flush();
					ob_flush();
					
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

date_default_timezone_set('Asia/Kolkata');

$url=$_REQUEST["url"];
$minx=$_REQUEST["i"];
$maxx=$_REQUEST["j"];
$qt=$_REQUEST["qt"];
$refresh=$_REQUEST["refresh"];

		$dataa = array(
					"https://cart.folioalert.in/fkart/megaSeth/q12/combined.php?i=2&qt=2&proxy=&submit=start&COD=yes&&url=".urlencode($url),
					"https://cart.folioalert.in/fkart/megaSeth/q12/np.php?i=2&qt=1&proxy=&submit=start&phonepe=yes&&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/q34/combined.php?i=2&qt=2&proxy=&submit=start&phonepe=yes&&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/q34/np.php?i=2&qt=1&proxy=&submit=start&phonepe=yes&&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/q56/combined.php?i=2&qt=1&proxy=&submit=start&phonepe=yes&&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/q56/np.php?i=2&qt=1&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/s12/np.php?i=2&qt=2&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/s12/combined.php?i=2&qt=2&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/s34/np.php?i=2&qt=1&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/s34/combined.php?i=2&qt=1&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/s56/combined.php?i=2&qt=1&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/u6/combined.php?i=2&qt=1&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),
          "https://cart.folioalert.in/fkart/megaSeth/amzuma/combined.php?i=2&qt=2&proxy=&phonepe=yes&submit=start&phonepe=yes&url=".urlencode($url),

									);						
		$r = multiRequest($dataa);	
						
						print_r($r);
						
						//"http://134.209.150.2/pctestc.php?i=$minx&j=99999&qt=$qt&proxy=&autocheckout=yes&refresh=1000&submit=start&url=$url"
						?>
