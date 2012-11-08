<html>
<head>
<style>
th{
height:25px;
background-color:green;
color: white;
}
td{
vertical-align:bottom;
text-align:right;
padding:2px;
}
table, td, th{
border:1px solid green;
}
tr:hover{
background-color:blue;
}
</style>
</head>
<?php

$host =  "c3c7e769d2aaba43cb6589b6996da9e4bd117287.rackspaceclouddb.com";
$username = "perf_track";
$password = "Zippr2012";
$db_name = "performance_track";

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

print "<center>Select a report to see its data<br>";
print "<a href=$php_self?report=last10>Last 10 requests</a><br>";
print "<a href=$php_self?report=30day_perf>Daily Conversions (Last 30days)</a><br>";
print "</center><hr>";
if ($_GET){
	switch($_GET['report']){
		case "last10":
			$table = "Last 10 Requests";
			$sql = "SELECT * FROM `hasoffers` order by ho_id DESC limit 10"; 
			break;
		case "30day_perf":
			$table = "Conversions per day (Last 30days)";
			$sql= "SELECT DATE(  `ho_ts` ) AS DAY , COUNT(  `ho_id` ) AS CONVERSIONS FROM  `hasoffers` WHERE DATE(  `ho_ts` ) > DATE_SUB( CURDATE( ) , INTERVAL 30 DAY ) GROUP BY DAY ORDER BY  `ho_ts` ASC LIMIT 0 , 30";
			break;
	};
	// sending query
	$result = mysql_query($sql);
	if (!$result) {
	    die("Query to show fields from table failed");
	}
	$fields_num = mysql_num_fields($result);

	echo "<h1>$table</h1>";
	echo "<table border='1'><tr>";
	// printing table headers
	for($i=0; $i<$fields_num; $i++)
	{
	    $field = mysql_fetch_field($result);
	    echo "<th>{$field->name}</th>";
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
}
?>

