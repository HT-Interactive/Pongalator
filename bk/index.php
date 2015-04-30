<?php // Pongalator 4000, Index

  $by = "Jeff Moreland";
  $contact = "jeff@evose.com";
  $on = "24 Sep 2014";
  $updated = "24 Sep 2014";
  $version = "0.1";

// Load DB
  require("mysql.php");
  $today = date("Y-m-d H:i:s");

  if(isset($_REQUEST['log'])) {
 
    //print_r($_REQUEST);
    extract($_REQUEST);
    if($winner=='Jeff') {
      $winner=1;
      $loser=2;
    } elseif($winner=='Tim') {
      $winner=2;
      $loser=1;
    } else {
      break;
    }

    if($score=='Losing Score') {
      $score = NULL;
      $deuce = NULL;
    } elseif ($score=='Deuce') {
      $score = 10;
      $deuce = TRUE;
    } else {
      $deuce = NULL;
    }
    $sql = "INSERT INTO games VALUES (NULL,'$winner','$loser','$score','$deuce',NULL,NULL)";
    $null_sql = str_replace("''","NULL",$sql);
    echo "$null_sql <br />";
    mysqli_query($db, $null_sql) or die(mysql_error());

  } 

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width" />
	<meta name="apple-mobile-web-app-capable" content="yes" />

	<link rel="stylesheet" href="spinningwheel.css" type="text/css" media="all" />
	<script type="text/javascript" src="spinningwheel.js?v=1.4"></script>

	<title>Pongalator 4000</title>


<style type="text/css">
body { text-align:center; font-family:helvetica; }
button { font-size:16px; }
.submit { font-size:16px; }
#result { margin:10px; background:#aaa; -webkit-border-radius:8px; padding:8px; font-size:18px; }
</style>

</head>
<body>
<P>
Pongulator 4000
<br>
<form method="post">
<table align="center">
<tr>
<td>
Select Winner:
</td>
<td>
Jeff<input type="Radio" id="winner" name="winner" value="Jeff">
</td>
<td>
Tim<input type="Radio" id="winner" name="winner" value="Tim">
</td>
<td>
<select name="score">
  <option>Losing Score</option>
  <option>Deuce</option>
  <option>0</option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
<input type="submit" name="log" value="Log Game">
</td>
</tr>
<tr>
<td>
Current Match
</td>
<td>
<?php

  $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 1 AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)");
  echo mysqli_num_rows($result);

?>
</td>
<td>
<?php

  $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2 AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)");
  echo mysqli_num_rows($result);

?>
</td>
<td>
Mean Score Differential
</td>
</tr>
<tr>
<td>
Overall Record
</td>
<td>
<?php

  $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 1");
  echo mysqli_num_rows($result);

?>
</td>
<td>
<?php

  $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2");
  echo mysqli_num_rows($result);

?>
</td>
<td>
Mean Score Differential
</td>
</tr>
</table>
</form>



<?php

  echo "<small>Created by $by ($contact) on $on as homage to St. Probaloni. Version $version updated on $updated.</small>";

?>

</body>
</html>
