<?php // Pongalator 4000, Index

  $by = "Jeff Moreland";
  $contact = "jeff@evose.com";
  $on = "24 Sep 2014";
  $updated = "30 Apr 2015";
  $version = "0.421";

/* Change Log
0.3			Added deuce feature to input winner score, otherwise winner defaults to score of 11
			Now shows total points, points per game, and point total differential (need to make function for this)
0.4   		Results function added. Now shows results of all matches instead of just last one.	
0.41  12 Nov 2014	Historic matches can be hidden by clicking header. Graph changes from month results to all-time after clicking
      		Name changed from PongUlator to PongAlator
0.42	30-Apr-2015	Configured github.
0.421	30-Apr-2014	Deploy script test.	
*/

// Load DB
  require("mysql.php");
  $now = date("Y-m-d H:i:s");

  function matchResults($m,$p) {
  // pass: match_id, player_id
  // return: games_won,total_score,point_per_game
    global $db;
    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $m AND winner = $p");
    $p_games_won = mysqli_num_rows($result);
    //echo $p_games_won." wins";

    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $m AND loser = $p");
    $p_games_lost = mysqli_num_rows($result);
    //echo $p1_games_lost;
    $p_total_games = $p_games_won+$p_games_lost;

    $result = mysqli_query($db,"SELECT SUM(winner_score) AS winner_score_sum FROM games WHERE `match` = $m AND winner = $p"); 
    $row = mysqli_fetch_assoc($result); 
    $p_winner_score = $row['winner_score_sum'];

    $result = mysqli_query($db,"SELECT SUM(loser_score) AS loser_score_sum FROM games WHERE `match` = $m AND loser = $p"); 
    $row = mysqli_fetch_assoc($result); 
    $p_loser_score = $row['loser_score_sum'];
  
    $p_total_score = $p_winner_score + $p_loser_score; 

    $p_ppg = round($p_total_score/$p_total_games,1);
    
    $match_results = array(
      "wins" => $p_games_won,
      "score" => $p_total_score,
      "ppg" => $p_ppg,
    );
    return $match_results;


  }

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
winner_scoreint(11) 	Yes  	NULL  	 	  	 
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

      if(isset($deuce)) {
   
      } else {
        $deuce = NULL;
      }
      $sql = "INSERT INTO games VALUES (NULL,'$match','$winner','$winner_score','$loser','$loser_score','$deuce',NULL,NULL)";
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

<script language="javascript" type="text/javascript">
	function showScore () {
		if (document.getElementById('winner_score').style.display == '') {
			document.getElementById('winner_score').style.display = 'none';
			document.getElementById('winner_score_label').style.display = 'none';
		} else {
			document.getElementById('winner_score').style.display = '';
			document.getElementById('winner_score_label').style.display = '';
		}
	}
      function showArchive() {
            if (document.getElementById('matchArchive').style.display == '') {
			document.getElementById('matchArchive').style.display = 'none';
		} else {
			document.getElementById('matchArchive').style.display = '';
		}
      }
	function changeImage() {
		if (document.getElementById('imgPongTrack').src == 'http://evose.com/pong/pong_track.php?date') {
			document.getElementById('imgPongTrack').src = 'http://evose.com/pong/pong_track.php';
		} else {
			document.getElementById('imgPongTrack').src = 'http://evose.com/pong/pong_track.php?date';
		}
	}
</script>

</head>
<body>
<P>
Pongalator 4000
<br>
<form method="post">
<table align="center" border="1px">
<tr>
<td><br /></td>
<td>Jeff</td>
<td>Tim</td>
<td>Deuce</td>
<td>Score</td>
</tr>
<tr>
<td>
Select Winner:
</td>
<td>
<input type="Radio" id="winner" name="winner" value="Jeff">
</td>
<td>
<input type="Radio" id="winner" name="winner" value="Tim">
</td>
<td>
<input type="checkbox" id="deuce" name="deuce" value="TRUE" onchange="showScore()">
</td>
<td>
<label id="winner_score_label" for="winner_score" style="display:none;">Winner</label>
<select name="winner_score" id="winner_score" style="display:none;">
  <option>21</option>
  <option>20</option>
  <option>19</option>
  <option>18</option>
  <option>17</option>
  <option>16</option>
  <option>15</option>
  <option>14</option>
  <option>13</option>
  <option>12</option>
  <option selected>11</option>
</select> 
<label for="loser_score">Loser</label>
<select name="loser_score">
  <option>21</option>
  <option>20</option>
  <option>19</option>
  <option>18</option>
  <option>17</option>
  <option>16</option>
  <option>15</option>
  <option>14</option>
  <option>13</option>
  <option>12</option>
  <option>11</option>
  <option>10</option>
  <option selected>9</option>
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
<tr>
<?php
  if(isset($current_match_id)) {
    echo "<tr><td><input type=\"submit\" name=\"end_match\" value=\"End Current Match\" /></td>";

    echo "<td>";
    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $current_match_id AND winner = 1");
    $p1_games_won = mysqli_num_rows($result);
    echo $p1_games_won." wins";

    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $current_match_id AND loser = 1");
    $p1_games_lost = mysqli_num_rows($result);
    //echo $p1_games_lost;
    $p1_total_games = $p1_games_won+$p1_games_lost;

    $result = mysqli_query($db,"SELECT SUM(winner_score) AS winner_score_sum FROM games WHERE `match` = $current_match_id AND winner=1"); 
    $row = mysqli_fetch_assoc($result); 
    $p1_winner_score = $row['winner_score_sum'];

    $result = mysqli_query($db,"SELECT SUM(loser_score) AS loser_score_sum FROM games WHERE `match` = $current_match_id AND loser=1"); 
    $row = mysqli_fetch_assoc($result); 
    $p1_loser_score = $row['loser_score_sum'];
  
    $p1_total_score = $p1_winner_score + $p1_loser_score; 

    $p1_ppg = round($p1_total_score/$p1_total_games,1);
    echo "<small><br />".$p1_total_score."pts"."<br />$p1_ppg"."ppg</small>";
    echo "</td>";

    echo "<td>";
    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $current_match_id AND winner = 2");
    $p2_games_won = mysqli_num_rows($result);
    echo $p2_games_won." wins";

    $result = mysqli_query($db,"SELECT * FROM games WHERE `match` = $current_match_id AND loser = 2");
    $p2_games_lost = mysqli_num_rows($result);
    //echo $p1_games_lost;
    $p2_total_games = $p2_games_won+$p2_games_lost;

    $result = mysqli_query($db,"SELECT SUM(winner_score) AS winner_score_sum FROM games WHERE `match` = $current_match_id AND winner=2"); 
    $row = mysqli_fetch_assoc($result); 
    $p2_winner_score = $row['winner_score_sum'];

    $result = mysqli_query($db,"SELECT SUM(loser_score) AS loser_score_sum FROM games WHERE `match` = $current_match_id AND loser=2"); 
    $row = mysqli_fetch_assoc($result); 
    $p2_loser_score = $row['loser_score_sum'];
  
    $p2_total_score = $p2_winner_score + $p2_loser_score; 

    $p2_ppg = round($p2_total_score/$p1_total_games,1);
    echo "<small><br />".$p2_total_score."pts"."<br />$p2_ppg"."ppg</small>";
    echo "</td>";
    echo "<td colspan=\"2\">Dscore ".abs($p1_total_score-$p2_total_score)."</td>";

  } else {
    echo "<tr><td colspan=\"5\"><input type=\"submit\" name=\"start_match\" value=\"Start New Match\" /></td>";
  }
?>
</tr>
<tr onclick="showArchive()"><td colspan="5"><b>Historic Matches</b></td></tr>
<tbody id="matchArchive" style="display:none;">
<?php
  
  if(isset($last_match_id)) {

    $result = mysqli_query($db,"SELECT * FROM matches WHERE `id` <= $last_match_id ORDER BY `id` DESC");
    while($matches = mysqli_fetch_array($result)) {
      extract($matches,EXTR_PREFIX_ALL,"this");
      // Player 1 Results
      echo "<tr><td>Match $this_id<br /><small>$this_start_time</small></td>";

      $d_score = 0;
      for($i=1;$i<=2;$i++) {
        echo "<td>";
        $match_results = matchResults($this_id,$i);
        echo "<b>{$match_results['wins']}</b> wins";
        echo "<small><br />{$match_results['score']}pts<br />{$match_results['ppg']}ppg</small>";
        echo "</td>";
        $d_score = $match_results['score'] - $d_score;
      }
      echo "<td colspan=\"2\">Dscore ".abs($d_score)."</td></tr>";
    }
  }
?>
</tbody>
<tr>
<td>
Overall Record
</td>
<td>
<?php

  $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 1");
  $p1_games_won = mysqli_num_rows($result);
  echo $p1_games_won." wins";

  $result = mysqli_query($db,"SELECT * FROM games WHERE loser = 1");
  $p1_games_lost = mysqli_num_rows($result);
  //echo $p1_games_lost;
  $p1_total_games = $p1_games_won+$p1_games_lost;

  $result = mysqli_query($db,"SELECT SUM(winner_score) AS winner_score_sum FROM games WHERE winner=1"); 
  $row = mysqli_fetch_assoc($result); 
  $p1_winner_score = $row['winner_score_sum'];

  $result = mysqli_query($db,"SELECT SUM(loser_score) AS loser_score_sum FROM games WHERE loser=1"); 
  $row = mysqli_fetch_assoc($result); 
  $p1_loser_score = $row['loser_score_sum'];
  
  $p1_total_score = $p1_winner_score + $p1_loser_score; 

  $p1_ppg = round($p1_total_score/$p1_total_games,1);
  echo "<small><br />".$p1_total_score."pts"."<br />$p1_ppg"."ppg</small>";

?>
</td>
<td>
<?php

  $result = mysqli_query($db,"SELECT * FROM games WHERE winner = 2");
  $p2_games_won = mysqli_num_rows($result);
  echo $p2_games_won." wins";

  $result = mysqli_query($db,"SELECT * FROM games WHERE loser = 2");
  $p2_games_lost = mysqli_num_rows($result);
  //echo $p2_games_lost;
  $p2_total_games = $p2_games_won+$p2_games_lost;

  $result = mysqli_query($db,"SELECT SUM(winner_score) AS winner_score_sum FROM games WHERE winner=2"); 
  $row = mysqli_fetch_assoc($result); 
  $p2_winner_score = $row['winner_score_sum'];

  $result = mysqli_query($db,"SELECT SUM(loser_score) AS loser_score_sum FROM games WHERE loser=2"); 
  $row = mysqli_fetch_assoc($result); 
  $p2_loser_score = $row['loser_score_sum'];
  
  $p2_total_score = $p2_winner_score + $p2_loser_score; 

  $p2_ppg = round($p2_total_score/$p2_total_games,1);
  echo "<small><br />".$p2_total_score."pts"."<br />$p2_ppg"."ppg</small>";

?>
</td>
<td colspan="2">
<?php
echo "Dscore ".abs($p1_total_score-$p2_total_score);
?>
</td>
</tr>
</table>
</form>

<img src="pong_track.php?date" id="imgPongTrack" onclick="changeImage()" /><br />

<?php
  
  //echo "<img src=\"pong_track.php?date=this_month\" id=\"imgPongTrack\" onclick=\"changeImage()\" /><br />";

  echo "<small>Created by $by ($contact) on $on as homage to St. Probaloni. Version $version updated on $updated.<a href=\"https://just69.justhost.com:2083/cpsess1570049879/3rdparty/phpMyAdmin/index.php?input_username=evosecom\">DB</a></small>";

?>

</body>
</html>
