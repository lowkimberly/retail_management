<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Show</title>
</head>
<body>

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$conn = dbConnect(); //taken from demo code. See bottom of php.

//EMPLOYEES
if (isset($_POST['show_employees'])){
	$sql = 'call show_employees();';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Show Employees failed: ' . mysql_error());
	}

	echo "
<h3>Employees</h3>
<table width=400px border=1>
	<tr><td>EID</td><td> EName</td><td>City</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td></tr>";	 
	} 

	echo "</table>";
}
//Customers
else if (isset($_POST['show_customers']))  {
	$sql = 'call show_customers();';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Show Customers failed: ' . mysql_error());
	}

	echo "
<h3>Customers</h3>
<table width=500px border=1>
	<tr><td>CID</td><td> CName</td><td>City</td><td>Visits Made</td><td>Last Visit Time</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td></tr>";	 
	} 

	echo "</table>";
}

//Products
else if (isset($_POST['show_products'])) {
	$sql = 'call show_products();';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Show Products failed: ' . mysql_error());
	}

	echo "
<h3>Products</h3>
<table width=700px border=1>
	<tr><td>PID</td><td> PName</td><td>QOH</td><td>QOH Threshold</td><td>Original Price</td><td>Discount Rate</td><td>SID</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td><td>{$row[6]}</td></tr>";	 
	} 

	echo "</table>";
}
//Suppliers
else if(isset($_POST['show_suppliers'])) {
	$sql = 'call show_suppliers();';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Show Suppliers failed: ' . mysql_error());
	}

	echo "
<h3>Suppliers</h3>
<table width=400px border=1px>
	<tr><td>SID</td><td> SName</td><td>City</td><td>Telephone No.</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td></tr>";	 
	} 

	echo "</table>";
}
//Purchases
else if(isset($_POST['show_purchases'])) {
	$sql = 'call show_purchases();';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Show Purchases failed: ' . mysql_error());
	}

	echo "
<h3>Purchases</h3>
<table width=600px border=1px>
	<tr><td>Pur#</td><td>CID</td><td>EID</td><td>PID</td><td>QTY</td><td>Ptime</td><td>Total Price</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td><td>{$row[6]}</td></tr>";	 
	} 

	echo "</table>";

}	
//Logs
else if(isset($_POST['show_logs'])) {
	$sql = 'call show_logs();';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Show Logs failed: ' . mysql_error());
	}

	echo "
<h3>Logs</h3>
<table width=600px border=1px>
	<tr><td>LogID</td><td> Who</td><td>Time</td><td>Table Name</td><td>Operation</td><td>key_value</td></tr>"; //heading

	while($row = mysql_fetch_array($retval)) {
		echo "
<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td></tr>";	 
	} 

	echo "</table>";
}
else {
	echo '<p>How did you get to this page?</p>';
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
