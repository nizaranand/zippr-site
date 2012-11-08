<?php


$publisher_id = $_GET["pubid"];
$app_id = $_GET["appid"];
$campaign_id = $_GET["campaignid"];
$campaign_url_id = $_GET["campaignurlid"];
$tracking_id = $_GET["trackingid"];
$publisher_sub1 = $_GET["pubsub1"];
$publisher_sub2 = $_GET["pubsub2"];
$publisher_sub3 = $_GET["pubsub3"];
$publisher_sub4 = $_GET["pubsub4"];
$publisher_sub5 = $_GET["pubsub5"];
$publisher_ref_id = $_GET["pubrefid"];
$user_id = $_GET["userid"];
$device_ip = $_GET["deviceip"];
$mac_address = $_GET["macaddress"];
$open_udid = $_GET["openudid"];
$conversion_status = $_GET["conversionstatus"];
$package_app_name = $_GET["packageappname"];

$gpost="https://docs.google.com/a/thetravis.com/spreadsheet/formResponse?formkey=dGliOEtwZ096cVNxX3BKNnlBZGJxQ1E6MQ";
$params = "&entry.0.single=$publisher_id" .
	"&entry.1.single=$app_id" .
	"&entry.2.single=$campaign_id" .
	"&entry.3.single=$campaign_url_id" . 
	"&entry.4.single=$tracking_id" .
	"&entry.5.single=$publisher_sub1" .
	"&entry.6.single=$publisher_sub2" .
	"&entry.7.single=$publisher_sub3" .
	"&entry.8.single=$publisher_sub4" . 
	"&entry.9.single=$publisher_sub5" .
	"&entry.10.single=$publisher_ref_id" .
	"&entry.11.single=$user_id" .
	"&entry.12.single=$device_ip" .
	"&entry.13.single=$mac_address" .
	"&entry.14.single=$open_udid" .
	"&entry.15.single=$conversion_status" . 
	"&entry.16.single=$package_app_name"
	;

$url  =  $gpost . $params;
#print $url;
$result  = file_get_contents($url);
print "thank you";
#print "Result = " . $result;
?>
