<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add item</title>
</head>
<body>

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$conn = dbConnect(); //taken from demo code. See bottom of php.

if (isset($_POST['add_purchase'])){ //if came from form and there was a pid
	$sql = 'call add_purchase(\''.$_POST['pur_no'].'\', \''.$_POST['cid'].'\', \''.$_POST['eid'].'\', \''.$_POST['pid'].'\', \''.$_POST['qty'].'\');'; //needs quotes
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Add Purchase failed: ' . mysql_error());
	}

	echo "
<h3>Purchase successfully added!</h3>"; //heading to make it stand out

	$myvar = mysql_query( 'select @old_qty,@inc_qty' );
	$row = mysql_fetch_row($myvar);

	if (!is_null($row[0]) && $row[0] != -1) {
		echo "<br>Current qoh: {$row[0]}"."<br>Increased by : {$row[1]}";

	}
}

else if (isset($_POST['add_product']) && 
	(isset($_POST['pid']) && isset($_POST['pname']) && isset($_POST['qoh']) && isset($_POST['qoh_threshold']) && isset($_POST['original_price']) && isset($_POST['discnt_rate']) && isset($_POST['sid'] )) ){ //product fields

	$sql = 'call add_product(\''.$_POST['pid'].'\', \''.$_POST['pname'].'\', \''.$_POST['qoh'].'\', \''.$_POST['qoh_threshold'].'\', \''.$_POST['original_price'].'\', \''.$_POST['discnt_rate'].'\', \''.$_POST['sid'].'\');';
	$retval = mysql_query( $sql, $conn );

	if(!$retval) {
		die('Add Product failed: ' . mysql_error());
	}

	echo "
<h3>Product successfully added!</h3>"; //heading to make it stand out

} else {
	echo '<p>Go back and use the form.</p>'; //if you get to the page any other way
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
