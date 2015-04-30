<?php // Voucher Profit Optimization System, Index

  $by = "Jeff Moreland";
  $contact = "jeff@evose.com";
  $on = "24 Apr 2013";
  $updated = "25 Apr 2013";
  $version = "0.1";

// Load DB
  require("mysql.php");


  if(isset($_REQUEST['add'])) {
  /*
  Column 		Type 		Null 	
  id 			int(11) 	No  	  	 
  value 		double 	No  	  	 
  start_date 	date 		No  	  	 
  end_date 		date 		No  	  	 
  mystery 		int(11) 	Yes  	 
  used 		int(11) 	Yes 
*/	 
    //print_r($_REQUEST);
    extract($_REQUEST);
    $sql = "INSERT INTO vouchers VALUES (NULL,'$value','$start','$end','$mystery',NULL)";
    $null_sql = str_replace("''","NULL",$sql);
    //echo "$null_sql <br />";
    mysql_query($null_sql) or die(mysql_error());

  } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Voucher Profit Optimization System (VPOS)</title>
  <meta name="generator" content="NoteTab Light 7.1 (www.notetab.com)">
  <meta name="created" content="2013-04-24">

  <style type="text/css">
    body{font-family:Verdana,Geneva,sans-serif;font-size:16px;line-height:1.4em;color:#333;background-color:White;}
  </style>

  <link rel="stylesheet" type="text/css" href="vouchers.css">

  <!-- Adds support for HTML5 elements in IE6-8 -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>

<h1>Voucher Profit Optimization System (<a href="index.php">VPOS</a>)</h1>
<hr>
<form>
Rate of Return (0-1)<br><input type="range" name="margin" min="0" max="1" value="0.75"><br>
Cost per trip ($)<br><input type="number" name="trip_cost" value="40"><br>
Max number of trips (1-4)<br><input type="number" name="trips" min="1" max="4" value="4"><br>
First Travel Date<br>
<input type="date" name="first" value="<?php echo date("Y-m-d"); ?>"><br>
Last Travel Date<br>
<input type="date" name="last" value="<?php echo date("Y-m-d",strtotime("+1 month")); ?>"><br>
Weekends Only (Fri, Sat, Sun)<input type="checkbox" name="weekend"><br>
<input type="submit" name="run" value="Run Algorithm">
</form>
<?php

  if(isset($_REQUEST['run'])) {

    include("algorithm.php");

  }//endif
?>
<hr>
<form>
<p>Add a new voucher to the system</p>
<hr>
<table>
  <tr>
    <td>Value</td>
    <td>Start Date</td>
    <td>End Date</td>
  </tr>
  <tr>
    <td><input type="text" name="value"><br>or Mystery?<input type="checkbox" name="mystery" /></td>
    <td><input type="date" name="start" value="<?php echo date("Y-m-d"); ?>"></td>
    <td><input type="date" name="end"><input type="submit" name="add" value="Add Voucher" /></td>
  </tr>
</table>
</form>

<hr>
<p>Current Unused Vouchers in the System</p>
<table>
<tr>
<td>ID</td>
<td>Value</td>
<td>Start</td>
<td>End</td>
</tr>
<?php

  $voucher_result = mysql_query("SELECT * FROM vouchers WHERE used IS NULL",$db);
  while($this_voucher = mysql_fetch_array($voucher_result)) {
    extract($this_voucher,EXTR_PREFIX_ALL,"that");
    echo "<tr>
          <td>$that_id</td>
          <td>$that_value</td>
          <td>$that_start_date</td>
          <td>$that_end_date</td>
          </tr>";
  }

?>
</table>
<hr>
<p>Previously Used Vouchers</p>
<hr>
<?php

  echo "<p>Created by $by ($contact) on $on. Version $version updated on $updated.</p>";

?>

</body>
</html>
