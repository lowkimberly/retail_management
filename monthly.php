<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monthly Sale Values</title>
</head>
<body>

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$conn = dbConnect(); //taken from demo code. See bottom of php.

if (isset($_POST['show_sales']) && isset($_POST['pid'])){ //if came from form and there was a pid
	$sql = 'call report_monthly_sale(\''.$_POST['pid'].'\');'; //needs quotes
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Report Monthly Sales failed: ' . mysql_error());
	}

	echo "
<h3>Monthly Sales</h3>
<table width=400px border=1>
	<tr><td>Pname</td><td>Month</td><td>Year</td><td>Total Qty</td><td>Total Amount</td><td>Average Price</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td></tr>";	 
	} 

	echo "</table>";
} else {
	echo '<p>You need to enter a PID!</p>'; //if you get to the page any other way
}


//connection function from example.
function dbConnect() {
	$dbhost = 'localhost:3306';
	$dbuser = 'root';
	$dbpass = 'rootabega';
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);

	if(! $conn )
	{
	  die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db('rbms3');
	return $conn;
}
mysql_close($conn);


?>

<a href="index.php">Go back</a>
</body>
</html>
