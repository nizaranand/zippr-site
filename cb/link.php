<html>
<?php
print "<center>Select your name for a custom link<br>";
print "<a href=$php_self?user=kevin>Kevin</a><br>";
print "<a href=$php_self?user=travis>Travis</a><br>";
print "</center><hr>";
if ($_GET){
	print "Your Unique Links:<br>";
	$user = $_GET['user'];
	$url = "http://hastrk2.com/serve?action=click&publisher_id=7781&site_id=2498&campaign_id=242102";
	$rand = rand();
	$unique = $url . "&ref_id=$rand&sub1=$user";
	print "Viaden Link: <a href=$unique>$unique</a><br>";
}
?>

