<?php

if ($_GET){
	$host =  "c3c7e769d2aaba43cb6589b6996da9e4bd117287.rackspaceclouddb.com";
	$username = "perf_track";
		$password = "Zippr2012";
	$db_name = "performance_track";

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");

	$publisher_id = $_GET["pubid"];
	$app_id = $_GET["appid"];
	$tracking_id = $_GET["trackingid"];
	$publisher_sub1 = $_GET["pubsub1"];
	$publisher_sub2 = $_GET["pubsub2"];
	$publisher_sub3 = $_GET["pubsub3"];
	$publisher_sub4 = $_GET["pubsub4"];
	$publisher_sub5 = $_GET["pubsub5"];
	$publisher_ref_id = $_GET["pubrefid"];
	$device_ip = $_GET["deviceip"];
	$mac_address = $_GET["macaddress"];
	$open_udid = $_GET["openudid"];
	$conversion_status = $_GET["conversionstatus"];
	$package_app_name = $_GET["packageappname"];

	$sql= "INSERT INTO `performance_track`.`hasoffers` (`ho_pubid`, `ho_appid`, `ho_trackingid`, `ho_pubsub1`, `ho_pubsub2`, `ho_pubsub3`, `ho_pubsub4`, `ho_pubsub5`, `ho_pubrefid`, `ho_deviceip`, `ho_macaddress`, `ho_openudid`, `ho_packageappname`) VALUES ('$publisher_id', '$app_id', '$tracking_id', '$publisher_sub1', '$publisher_sub2', '$publisher_sub3', '$publisher_sub4', '$publisher_sub5', '$publisher_ref_id', '$device_ip', '$mac_address', '$open_udid', '$package_app_name')";

	$sql2= "INSERT INTO `performance_track`.`callback` (`clickid`, `type`, `macaddress`, `trackingid`, `ref_id`) VALUES ('$publisher_ref_id', 'hasoffers', '$mac_address', '$tracking_id', '$open_udid
')";
	$sqlresult=mysql_query($sql);
	$sqlresult=mysql_query($sql2);
	if ($sqlresult){	
		print "thank you" ;
	}else{ print "error"; };

}else{
	print "Params missing!";
}	

?>
