<html>
<title >Retail Business Management System </title>
<body >

<table>
	<form action="show.php" method="post"><br>
	<tr>
		<td><p>What do you want to see?</p></td>
		<td><input type="submit" name="show_employees" value="Employees"></td>
	</tr><tr>
		<td></td>
		<td><input type="submit" name="show_customers" value="Customers"></td>
	</tr><tr>
		<td></td>
		<td><input type="submit" name="show_products" value="Products"> </td>
	</tr><tr>
		<td></td>
		<td><input type="submit" name="show_suppliers" value="Suppliers"> </td>
	</tr><tr>
		<td></td>
		<td><input type="submit" name="show_purchases" value="Purchases"> </td>
	</tr><tr>
		<td></td>
		<td><input type="submit" name="show_logs" value="Logs"> </td>
	</tr>
</form>
</table>

<hr>

<p>To see monthly sale values, enter a product id (see products table for valid ids):</p>
<table>
	<form method="post" action="monthly.php">
        <tr>
          <td>Product ID:</td>
          <td><input type="text" name="pid" size="20">
          </td>
        </tr>
		<tr>
          <td></td>
          <td align="right">
		  <input type="submit" name="show_sales" value="Show Sales">
		  </td>
        </tr>
		</form>
     </table>
	 
<hr>
	 
<table>
	<form method="post" action="add_item.php">
	<tr><td>Add a purchase:</td></tr>
	<tr>
		<td>Purchase #:</td>
		<td><input type="text" name="pur_no" size="4"></td>
	</tr>
	<tr>
		<td>Customer ID:</td>
		<td><input type="text" name="cid" size="4"></td>
	</tr>
	<tr>
		<td>Employee ID:</td>
		<td><input type="text" name="eid" size="3"></td>
	</tr>
	<tr>
		<td>Product ID:</td>
		<td><input type="text" name="pid" size="4"></td>
	</tr>
	<tr>
		<td>Quantity bought:</td>
		<td><input type="text" name="qty" size="5"></td>
	</tr>
	<tr>
          <td><input type="submit" name="add_purchase" value="Add Purchase"></td></td>
        </tr>
		</form>
     </table>
<hr>

	<table>
		<form method="post" action="add_item.php">
		<tr><td>Add a product:</td></tr>
		<tr>
			<td>Product ID:</td>
			<td><input type="text" name="pid" size="4"></td>
		</tr>
		<tr>
			<td>Product Name:</td>
			<td><input type="text" name="pname" size="15"></td>
		</tr>
		<tr>
			<td>Initial QOH:</td>
			<td><input type="text" name="qoh" size="5"></td>
		</tr>
		<tr>
			<td>QOH Threshold:</td>
			<td><input type="text" name="qoh_threshold" size="5"></td>
		</tr>
		<tr>
			<td>Original Price:</td>
			<td><input type="text" name="original_price" size="9"></td>
		</tr>
		<tr>
			<td>Discount rate:</td>
			<td><input type="text" name="discnt_rate" size="6"></td>
		</tr>
		<tr>
			<td>Supplier ID:</td>
			<td><input type="text" name="sid" size="2"></td>
		</tr>
		<tr>
		<td><input type="submit" name="add_product" value="Add Product"></td>
	        </tr>
		</form>
     </table>
</body>
</html>
