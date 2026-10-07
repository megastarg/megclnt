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
$lid=$itemid=$_REQUEST["itemid"];
$phonepe=$_REQUEST["phonepe"];
$cod=$_REQUEST["cod"];
//$cod="yes";
//$phonepe="no";
$discount=$_REQUEST["discount"];
$name=urlencode($_REQUEST["name"]);
$pid=$_REQUEST["pid"];
//$phonepe="gpay";

preg_match("#Size: (.*?)#",urldecode($name),$mmmat);
$mmmat=$mmmat[1];
if(stristr($mmmat,"xl") || stristr($mmmat,"S") || stristr($mmmat,"10") || stristr($mmmat,"11") || stristr($mmmat,"12") || $mmmat=="6" || stristr($mmmat,"100") || stristr($mmmat,"105") || stristr($mmmat,"75") || stristr($mmmat,"80") || stristr($mmmat,"95") || $mmmat=="3" || stristr($mmmat,"xs") || stristr($mmmat,"36") || stristr($mmmat,"38") || stristr($mmmat,"42") || stristr($mmmat,"44"))
	list($pid,$itemid) = selectvariant($pid,$lid,$price,urldecode($name));


$hour=date('H');
if($hour>=2 && $hour<=7)
{
	$phonepe="no";
	$cod="yes";
}


if(stristr($filename,"mob") && !stristr($itemid,"MOB") && !stristr($itemid,"ACC") && !stristr($itemid,"TAB") && !stristr($itemid,"COM"))
	die(telegram("wrong product in MOB"));
else if(stristr($filename,"router") && !stristr($itemid,"RTR"))
	die(telegram("wrong product in RTR"));
else if(stristr($filename,"camera") && !stristr($itemid,"DLL") && !stristr($itemid,"TDC") && !stristr($itemid,"SAY"))
	die(telegram("wrong product in DLL or TDC or SAY"));
else if(stristr($filename,"graphic") && !stristr($itemid,"GRC"))
	die(telegram("wrong product in GRC"));
else if(stristr($filename,"desktop") && !stristr($itemid,"PC"))
	die(telegram("wrong product in PC"));
else if(stristr($filename,"AC_") && !stristr($itemid,"ACN"))
	die(telegram("wrong product in ACN"));
else if(stristr($filename,"tab") && !stristr($itemid,"MOB") && !stristr($itemid,"TAB"))
	die(telegram("wrong product in TAB"));
else if(stristr($filename,"cool") && !stristr($itemid,"AIC"))
	die(telegram("wrong product in AIC"));
else if(stristr($filename,"lap") && !stristr($itemid,"COM") && !stristr($itemid,"DPC"))
	die(telegram("wrong product in COM or DPC"));
else if(stristr($filename,"tshirt") && !stristr($itemid,"TSH"))
	die(telegram("wrong product in TSH"));
else if(stristr($filename,"earphone") && !stristr($itemid,"ACC"))
	die(telegram("wrong product in ACC"));
else if(stristr($filename,"cycle") && !stristr($itemid,"CCE"))
	die(telegram("wrong product in CCE"));
else if(stristr($filename,"chimney") && !stristr($itemid,"CHY"))
	die(telegram("wrong product in CHY"));
else if(stristr($filename,"cctv") && !stristr($itemid,"HSA"))
	die(telegram("wrong product in HSA"));




$content_of_checkoutfile=file_get_contents("stopcheckout.txt");
$servername_checkoutfile=explode("-",$content_of_checkoutfile);
array_pop($servername_checkoutfile);
$servername_fromfilename=explode("/",$filename)[0]."/";


if(stristr($content_of_checkoutfile,"stop") && stristr($content_of_checkoutfile,"bis"))
{
   telegram(urlencode("bis checkout stopped ".file_get_contents("http://rdptv.lalbox.tech/ip.php")));
   die("bis checkout stopped");
}

if(stristr($content_of_checkoutfile,"stop") && $_REQUEST["bistar"]!="yes")
{
    if(stristr($content_of_checkoutfile,"ALL"))
    {
        telegram(urlencode("ALL checkout stopped ".file_get_contents("http://rdptv.lalbox.tech/ip.php")));
        die("ALL checkout stopped");
    }
    else if(stristr($content_of_checkoutfile,$servername_fromfilename))
    {
        telegram(urlencode($servername_fromfilename." checkout stopped ".file_get_contents("http://rdptv.lalbox.tech/ip.php")));
        die($servername_fromfilename." checkout stopped");
    }
}
else if(stristr($content_of_checkoutfile,"start") && $_REQUEST["bistar"]!="yes")
{
    if(!stristr($content_of_checkoutfile,"ALL"))
    {
        if(!stristr($content_of_checkoutfile,$servername_fromfilename))
        {
            telegram(urlencode($servername_fromfilename." checkout stopped ".file_get_contents("http://rdptv.lalbox.tech/ip.php")));
            die($servername_fromfilename." checkout stopped");
        }
    }
}




if($maxe<=50)
	$qt=rand(1,10);
else
	$qt="1";

if(stristr($filename,"mobacc"))
$filename=str_replace("mobacc","accessories",$filename);


$catprices=array();

$catprices["apple"]=30000;
$catprices["accessories"]=30;
$catprices["mbc"]=25;
$catprices["mob"]=38000;
$catprices["5g"]=38000;
$catprices["tablet"]=36000;
$catprices["tabU"]=36000;
$catprices["i9"]=140000;
$catprices["lap"]=120000;
$catprices["earphone"]=200;
$catprices["watch"]=500;
$catprices["pc"]=116000;
$catprices["monitor"]=12000;
$catprices["audio"]=100;
$catprices["pdcapacity"]=200;
$catprices["router"]=2000;
$catprices["speaker"]=2000;
$catprices["50inch"]=22000;
$catprices["4k8k"]=23000;
$catprices["tv"]=14000;
$catprices["sofa"]=4000;
$catprices["furniture"]=1500;
$catprices["oven"]=3000;
$catprices["food"]=3000;
$catprices["AC_"]=23000;
$catprices["ac_"]=23000;
$catprices["geyser"]=1700;
$catprices["WM"]=6000;
$catprices["Washing"]=6000;
$catprices["vac"]=1000;
$catprices["printer"]=4000;
$catprices["freezer"]=4000;
$catprices["recliner"]=3000;
$catprices["frige"]=8000;
$catprices["dslr"]=8000;
$catprices["gopro"]=8000;
$catprices["gam"]=8000;
$catprices["chair"]=800;
$catprices["bldc"]=1500;
$catprices["fan"]=800;
$catprices["smart"]=100;
$catprices["bulb"]=70;
$catprices["dslr"]=10000;
$catprices["cam"]=8000;
$catprices["tread"]=5000;
$catprices["cross"]=5000;
$catprices["cooler"]=5000;
$catprices["iron"]=500;
$catprices["dry"]=500;
$catprices["bike"]=2500;
$catprices["cyc"]=2100;
$catprices["appliance"]=700;
$catprices["kappliance"]=3000;
$catprices["storage"]=20;
$catprices["kitchen"]=1000;
$catprices["air"]=3000;
$catprices["hand"]=500;
$catprices["chimney"]=3000;
$catprices["induction"]=700;
$catprices["mix"]=500;
$catprices["juicer"]=700;
$catprices["innerware"]=20;
$catprices["innerwear"]=20;
$catprices["grind"]=500;
$catprices["toast"]=500;
$catprices["ssd"]=1500;
$catprices["hdd"]=500;
$catprices["key"]=300;
$catprices["furnish"]=40;
$catprices["decor"]=20;
$catprices["mouse"]=100;
$catprices["diapers"]=500;
$catprices["gas"]=500;
$catprices["bag"]=150;
$catprices["luggage"]=500;
$catprices["cook"]=100;
$catprices["fitness"]=50;
$catprices["/men"]=50;
$catprices["clothes"]=500;
$catprices["jacket"]=200;

$allow=false;
$reason="NA";
foreach($catprices as $key => $value){
	if(stristr($filename, $key) && (int)$maxe<=(int)$value)
	{
		$allow=true;
		break;
	}
	else if(stristr($filename, $key) && (int)$maxe>=(int)$value)
	{
		$reason = "$key $maxe > $value";
	}
}


if($allow==true && stristr($filename,"brand"))
{
	$catprices1=array();
	$catprices1["cloth"]=300;
	$catprices1["men"]=300;
	$catprices1["bulb"]=70;
	$catprices1["smartwatch"]=300;

	foreach($catprices1 as $key => $value)
	{
		if(stristr($filename, $key) && (int)$maxe>(int)$value)
		{
			$allow=false;
			$reason = "$key $maxe > $value";
			break;
		}
	}
}

if(stristr($filename, "/men") && ((int)$discount>=96 || (int)$maxe<=20))
	$allow=true;
else if((int)$maxe<=20 || (int)$discount>=95 || $_REQUEST["bis"]=="yes" || $_REQUEST["app"]=="yes")
	$allow=true;

if((int)$discount>=60 && $reason=="NA")
	$allow=true;

if((int)$discount>=80 && (int)$maxe<1000 && (stristr($filename, "luggage")))
	$allow=true;

if((int)$discount>=45 && (stristr($filename, "5g") || stristr($filename, "lap") || stristr($filename, "samsung") || stristr($filename, "apple") || stristr($filename, "tab") || stristr($filename, "mob")))
	$allow=true;

if((stristr($filename, "fashion") || stristr($filename, "wear") || stristr($filename, "cloth") || stristr($filename, "men") || stristr($filename, "man")) && ((int)$discount<93 || (int)$maxe>30))
$allow=false;



if(stristr($filename, "calf") || stristr($filename, "bar") || stristr($filename, "ankle") && ((int)$discount<98 || (int)$maxe>10))
$allow=false;



/*
if(((int)$discount<75 || ((int)$maxe>7000 && (int)$discount<70)) && (stristr($filename, "mob")))
	$allow=false;

if(((int)$discount<60 || ((int)$maxe>14000 && (int)$discount<55)) && (stristr($filename, "lap")))
	$allow=false;

if(((int)$discount<80 || ((int)$maxe>6000 && (int)$discount<70)) && (stristr($filename, "tv")))
	$allow=false;


if(stristr($filename, "b313") || stristr($filename, "airfryer") || stristr($filename, "b310") || stristr($filename, "guru") || stristr($filename, "24 inch"))
die;


*/


if($allow!=true)
{
	telegram(urlencode("S1 exp $filename filtered by ".strval($reason)));
	die("condition violation");
}

//die("completed");

//die(var_dump($allow).$reason);

$dataa=array();
if(stristr($filename,"mob") || stristr($filename,"lap") || $_REQUEST["bis"]=="yes")
{	
	//if($_REQUEST["bis"]=="yes")
        //	$phonepe="gpay";

	array_push($dataa,
	"http://139.59.0.200/fkart/auton5/np.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton5/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton6/np.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton6/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton7/np.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton7/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton8/np.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auton8/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/megaSeth/d12/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d1819/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d1819/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/megaSeth/d45/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d45/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d20/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d67/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/megaSeth/q1415/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/megaSeth/d1617/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/megaSeth/n67/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/n67/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1617/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d89/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1011/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1011/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1213/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1213/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1415/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/d1415/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/n1011/np.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        //"http://139.59.0.200/fkart/megaSeth/n1011/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);
}
else if($_REQUEST["bis"]=="yes")
{
	array_push($dataa,
	"http://139.59.0.200/fkart/auto11/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto12/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto13/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto14/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto15/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto10/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto6/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);

}
else
{
	array_push($dataa,
	"http://139.59.0.200/fkart/auto10/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto11/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto12/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto13/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto14/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto15/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto7/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto8/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto9/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega2/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega3/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega4/combined.php?i=10&qt=3&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"

	);
}

/*if($_REQUEST["bis"]!="yes")
{
array_push($dataa,
	"http://139.59.0.200/fkart/rajat1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat2/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat3/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/rajat4/combined.php?i=10&qt=3&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega2/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mega3/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/mega4/combined.php?i=10&qt=3&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mks/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/mks1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
        "http://139.59.0.200/fkart/kumar1/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar2/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar3/combined.php?i=10&qt=2&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/kumar4/combined.php?i=10&qt=3&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);
}*/

if($reason=="NA" && $allow==false)
{
$filename.=urlencode(" <b>NA</b>");
$dataa=array();
array_push($dataa,
	"http://139.59.0.200/fkart/auto4/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name",
	"http://139.59.0.200/fkart/auto11/combined.php?i=10&qt=1&proxy=&server=yes&".usemethod($phonepe)."&cod=$cod&submit=start&itemid=$itemid&maxe=$maxe&filename=$filename&pid=$pid&name=$name"
	);
}

//die(print_r($dataa));
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
			"https://api.callmebot.com/start.php?user=@sandeep88822&text=$txtx",
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


function curl_call($method, $url, $headers, $data, $onlyget=false)
{
	if(stristr(php_uname(),"Windows"))
	{
		global $proxy;
		$proxy="127.0.0.1:8888";
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
			$cmd.='./curl_chrome_android -w "\nRESPONSE_CODE->%{http_code}" -X POST \''.$url.'\' ';
			foreach($headers as $h)
				$cmd.='-H \''.$h.'\' ';
			$cmd.='-d \''.$data.'\'';
		}
		else if($onlyget==true)
		{
			$cmd.='./curl_chrome_android -L -w "\nRESPONSE_CODE->%{http_code}==>%{url_effective}" \''.$url.'\' ';
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
			$cmd.='./curl_chrome_android -w "\nRESPONSE_CODE->%{http_code}" \''.$url.'\' ';
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


function getallvariants($pid,$lid)
{
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
		
	$allvariants=array();
	$post='{"pageUri":"https://www.flipkart.com/flipkart/p/item?marketplace=FLIPKART&pid='.$pid.'&lid='.$lid.'","pageContext":{"trackingContext":{"context":{"eVar61":"direct_product"}},"networkSpeed":6550},"locationContext":{"pincode":132106,"changed":false}}';
	$html=curl_call("POST","https://www.flipkart.com/api/4/page/fetch?cacheFirst=false",$heads,$post)[0];
	$m=json_decode($html,1);
	$slots=$m["RESPONSE"]["slots"];
	foreach($slots as $slot)
	{
		if($slot["widget"]["type"]!="COMPOSED_SWATCH")
		{
			continue;
		}
		else
		{
			$products=$slot["widget"]["data"]["swatchComponent"]["value"]["products"];
			foreach($products as $pid=>$product)
			{
				$producturl=$product["productUrl"];
				$listingid=$product["listingId"];
				$subtitle=$product["titles"]["coSubtitle"];
				$variantsize=$product["tracking"]["contentTitle"];
				
				if($listingid==$lid)
				{
					$price = $m["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"]["decimalValue"];
					$disc  = $m["RESPONSE"]["pageData"]["pageContext"]["pricing"]["totalDiscount"];
				}
				else
					$price=$disc=null;
				
				
				if(empty($price))
				{
					$postx='{"pageUri":"'.$producturl.'","pageContext":{"trackingContext":{"context":{"eVar61":"direct_product"}},"networkSpeed":6550},"locationContext":{"pincode":132103,"changed":false}}';
					$htmlx=curl_call("POST","https://www.flipkart.com/api/4/page/fetch?cacheFirst=false",$heads,$postx)[0];
					$mx=json_decode($htmlx,1);					
					$price=$mx["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"]["decimalValue"];
					$disc=$mx["RESPONSE"]["pageData"]["pageContext"]["pricing"]["totalDiscount"];
				}
				$allvariants[$variantsize]=array($producturl,$pid,$listingid,$price,$disc);
			}
		}
	}
	return $allvariants;
}


function selectvariant($pid,$lid,$price,$name,$disc)
{
	$variants = getallvariants($pid,$lid);
	var_dump($variants);
	
	if(array_key_exists("M",$variants) && ($variants["M"][4]>=$disc-5 || $variants["M"][3]<=$price+30))
	{
		echo "<br>Selected M<br>";
		return array($variants["M"][1],$variants["M"][2]);
	}
	else if(array_key_exists("L",$variants) && ($variants["L"][4]>=$disc-5 || $variants["L"][3]<=$price+30))
	{
		echo "<br>Selected L<br>";
		return array($variants["L"][1],$variants["L"][2]);
	}
	else if(array_key_exists("S",$variants) && ($variants["S"][4]>=$disc-5 || $variants["S"][3]<=$price+30))
	{
		echo "<br>Selected S<br>";
		return array($variants["S"][1],$variants["S"][2]);
	}
	else if(array_key_exists("90",$variants) &&  ($variants["90"][4]>=$disc-5 || $variants["90"][3]<=$price+30))
	{
		echo "<br>Selected 90<br>";
		return array($variants["90"][1],$variants["90"][2]);
	}
	else if(array_key_exists("85",$variants) &&  ($variants["85"][4]>=$disc-5 || $variants["85"][3]<=$price+30))
	{
		echo "<br>Selected 85<br>";
		return array($variants["85"][1],$variants["85"][2]);
	}
	else if(array_key_exists("95",$variants) &&  ($variants["95"][4]>=$disc-5 || $variants["95"][3]<=$price+30))
	{	
		echo "<br>Selected 95<br>";
		return array($variants["95"][1],$variants["95"][2]);
	}
	else if(array_key_exists("5",$variants) &&  ((stristr($name,"women") || stristr($name,"girl")) && ($variants["5"][4]>=$disc-5 || $variants["5"][3]<=$price+30)))
	{
		echo "<br>Selected 5<br>";
		return array($variants["5"][1],$variants["5"][2]);
	}
	else if(array_key_exists("4",$variants) &&  ((stristr($name,"women") || stristr($name,"girl")) && ($variants["4"][4]>=$disc-5 || $variants["4"][3]<=$price+30)))
	{
		echo "<br>Selected 4<br>";
		return array($variants["4"][1],$variants["4"][2]);
	}
	else if(array_key_exists("8",$variants) && !stristr($name,"women") && ($variants["8"][4]>=$disc-5 || $variants["8"][3]<=$price+30))
	{
		echo "<br>Selected 8<br>";
		return array($variants["8"][1],$variants["8"][2]);
	}
	else if(array_key_exists("9",$variants) && !stristr($name,"women") && ($variants["9"][4]>=$disc-5 || $variants["9"][3]<=$price+30))
	{
		echo "<br>Selected 9<br>";
		return array($variants["9"][1],$variants["9"][2]);
	}
	else if(array_key_exists("7",$variants) && !stristr($name,"women") && ($variants["7"][4]>=$disc-5 || $variants["7"][3]<=$price+30))
	{
		echo "<br>Selected 7<br>";
		return array($variants["7"][1],$variants["7"][2]);
	}
	else if(array_key_exists("34",$variants) && !stristr($name,"women") && stristr($name,"jeans") && ($variants["34"][4]>=$disc-5 || $variants["34"][3]<=$price+30))
	{
		echo "<br>Selected 34<br>";
		return array($variants["34"][1],$variants["34"][2]);
	}
	else if(array_key_exists("32",$variants) && !stristr($name,"women") && stristr($name,"jeans") && ($variants["32"][4]>=$disc-5 || $variants["32"][3]<=$price+30))
	{
		echo "<br>Selected 32<br>";
		return array($variants["32"][1],$variants["32"][2]);
	}
	else if(array_key_exists("40",$variants) && !stristr($name,"women") && stristr($name,"shirt") && ($variants["40"][4]>=$disc-5 || $variants["40"][3]<=$price+30))
	{
		echo "<br>Selected 40<br>";
		return array($variants["40"][1],$variants["40"][2]);
	}
	else if(array_key_exists("39",$variants) && !stristr($name,"women") && stristr($name,"shirt") && ($variants["39"][4]>=$disc-5 || $variants["39"][3]<=$price+30))
	{
		echo "<br>Selected 39<br>";
		return array($variants["39"][1],$variants["39"][2]);
	}
	else if(array_key_exists("42",$variants) && !stristr($name,"women") && stristr($name,"shirt") && ($variants["42"][4]>=$disc-5 || $variants["42"][3]<=$price+30))
	{
		echo "<br>Selected 42<br>";
		return array($variants["42"][1],$variants["42"][2]);
	}
	else
	{
		echo "<br>unable to select anything<br>";
		var_dump($variants);
		echo "<br>";
		return array($pid,$lid);
	}
}



?>