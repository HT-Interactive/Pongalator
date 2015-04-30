<?php // Pongalator 4000, Index

  $by = "Jeff Moreland";
  $contact = "jeff@evose.com";
  $on = "24 Sep 2014";
  $updated = "26 Sep 2014";
  $version = "0.2";

// Load DB
  require("mysql.php");
  $now = date("Y-m-d H:i:s");

  if(isset($_REQUEST['start_match'])) {

/*
Column 	Type 	Null 	Default 	Comments
id 		int(11) 	No  	  	 
winner 	int(11) 	No  	  	 
loser 	int(11) 	No  	  	 
winner_steps int(11) 	Yes  	NULL  	 
loser_steps int(11) 	Yes  	NULL  	 
start_time 	timestamp 	No  	CURRENT_TIMESTAMP  	 
end_time 	timestamp 	Yes  	0000-00-00 00:00:00 
*/
    $sql = "INSERT INTO matches VALUES (NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
    $null_sql = str_replace("''","NULL",$sql);
    //echo "$null_sql <br />";
    mysqli_query($db, $null_sql) or die(mysql_error());
    $current_match_id = mysqli_insert_id($db);
  } else {

    $current_match_result = mysqli_query($db,"SELECT * FROM matches WHERE end_time IS NULL");
    if($current_match = mysqli_fetch_array($current_match_result)) {
      extract($current_match,EXTR_PREFIX_ALL,"current_match");
      //echo $current_match_id;
    } else {
       echo "No current match";
    }
  }
  
  if(isset($_REQUEST['end_match'])) {
    $match = $_REQUEST['match'];
    $sql = "UPDATE `matches` ".
           "SET end_time = '$now' ".
           "WHERE id = $match";
    //echo $sql;
    mysqli_query($db, $sql) or die(mysql_error());
    unset($current_match_id);
  }

  $last_match_result = mysqli_query($db,"SELECT * FROM matches WHERE end_time IS NOT NULL ORDER BY start_time DESC");
  if($last_match = mysqli_fetch_array($last_match_result)) {
    extract($last_match,EXTR_PREFIX_ALL,"last_match");
    //echo $last_match_id;
  } else {
    echo "No previous match";
  }


/*
Column 	Type 		Null 	Default
id 		int(11) 	No  	  	 
match 	int(11) 	No  	  	 
winner 	int(11) 	No  	  	 
loser 	int(11) 	No  	  	 
loser_score	int(11) 	Yes  	NULL  	 
deuce 	tinyint(1) 	Yes  	NULL  	 
steps 	int(11) 	Yes  	NULL  	 
date 		timestamp 	No  	CURRENT_TIMESTAMP 
*/ 	 
  if(isset($_REQUEST['log'])) {
 
    //print_r($_REQUEST);
    extract($_REQUEST);
    if(isset($match)) {
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
      $sql = "INSERT INTO games VALUES (NULL,'$match','$winner','$loser','$score','$deuce',NULL,NULL)";
      $null_sql = str_replace("''","NULL",$sql);
      //echo "$null_sql <br />";
      mysqli_query($db, $null_sql) or die(mysql_error());
    } else {
      echo "Please start a new match first. Game not logged.";
    }
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
  <option>9</option>
  <option>8</option>
  <option>7</option>
  <option>6</option>
  <option>5</option>
  <option>4</option>
  <option>3</option>
  <option>2</option>
  <option>1</option>
  <option>0</option>
</select> 
<?php 
  if(isset($current_match_id)) {
    echo "<input type=\"hidden\" name=\"match\" value=\"$current_match_id\" />";
  }
?>
<input type="submit" name="log" value="Log Game">
</td>
</tr>
<t
<?php
  if(isset($current_match_id)) {
    echo "<tr><td><input type=\"submit\" name=\"end_match\" value=\"End Current Match\" /></td>";
    $p1_result = mysqli_query($db,"SELECT * FROM games WHERE winner = 1 AND `match` = $current_match_id");
    $p1_wins = mysqli_num_rows($p1_result);
    echo "<td>$p1_wins</td>";

    //$p2_result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2 AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $p2_result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2 AND `match` = $current_match_id");
    $p2_wins = mysqli_num_rows($p2_result);
    echo "<td>$p2_wins</td>";

    $p1_score = 0;
    while($p1_results = mysqli_fetch_array($p1_result)) {
      extract($p1_results,EXTR_PREFIX_ALL,"this");
      $p1_score += (11-$this_loser_score);
    }
    $p1_score_diff = $p1_score / $p1_wins;

    $p2_score = 0;
    while($p2_results = mysqli_fetch_array($p2_result)) {
      extract($p2_results,EXTR_PREFIX_ALL,"this");
      $p2_score += (11-$this_loser_score);
    }
    $p2_score_diff = $p2_score / $p2_wins;
    $score_diff = max($p1_score_diff,$p2_score_diff); 
    echo "<td>+$score_diff</td>";
  } else {
    echo "<tr><td><input type=\"submit\" name=\"start_match\" value=\"Start New Match\" /></td><td></td><td></td>";
  }
?>
</tr>
<tr>
<td>
Previous Match
</td>
<td>
<?php
  
  if(isset($last_match_id)) {
    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $last_match_id AND winner = 1");
    echo mysqli_num_rows($result);
  }
  
?>
</td>
<td>
<?php

  if(isset($last_match_id)) {
    //$p2_result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2 AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2 AND `match` = $last_match_id");
    echo mysqli_num_rows($result);
  }

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
