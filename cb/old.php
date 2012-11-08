<?php
$host =  "c3c7e769d2aaba43cb6589b6996da9e4bd117287.rackspaceclouddb.com";
$username = "perf_track";
$password = "Zippr2012";
$db_name = "performance_track";

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");


// For Reference
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

$sql= "INSERT INTO `performance_track`.`hasoffers` (`ho_pubid`, `ho_appid`, `ho_campaignid`, `ho_campaignurlid`, `ho_trackingid`, `ho_pubsub1`, `ho_pubsub2`, `ho_pubsub3`, `ho_pubsub4`, `ho_pubsub5`, `ho_pubrefid`, `ho_userid`, `ho_deviceip`, `ho_macaddress`, `ho_openudid`, `ho_packageappname`) VALUES ('$publisher_id', '$app_id', '$campaign_id', '$campaign_url_id', '$tracking_id', '$publisher_sub1', '$publisher_sub2', '$publisher_sub3', '$publisher_sub4', '$publisher_sub5', '$publisher_ref_id', '$user_id', '$device_ip', '$mac_address', '$open_udid', '$package_app_name')";

$sqlresult=mysql_query($sql);
if ($sqlresult){	
	
}else{ print "error"; };

$fields_num = mysql_num_fields($result);

echo "<h1>Table: {$table}</h1>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>$cell</td>";

    echo "</tr>\n";
}
mysql_free_result($result);



?>
