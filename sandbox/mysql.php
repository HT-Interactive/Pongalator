<?php

// Connect to DB
  //$db = mysql_connect("localhost", "evosecom_jeff", "jmev0203") or die('Could not connect: ' . mysql_error());;
  //mysql_select_db("evosecom_pong_sb",$db);
  $db=mysqli_connect("localhost", "evosecom_jeff", "jmev0203","evosecom_pong_sb");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

?>