<?php // Pongalator 4000, Index

  $by = "Jeff Moreland";
  $contact = "jeff@evose.com";
  $on = "24 Sep 2014";
  $updated = "24 Sep 2014";
  $version = "0.1";

// Load DB
  require("mysql.php");


  if(isset($_REQUEST['log'])) {
 
    print_r($_REQUEST);
    //extract($_REQUEST);
    //$sql = "INSERT INTO game VALUES (NULL,'$winner','$loser','$loser_score','$deuce',NULL,'$date')";
    //$null_sql = str_replace("''","NULL",$sql);
    //echo "$null_sql <br />";
    //mysql_query($null_sql) or die(mysql_error());

  } 

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width" />
	<meta name="apple-mobile-web-app-capable" content="yes" />

	<link rel="stylesheet" href="spinningwheel.css" type="text/css" media="all" />
	<script type="text/javascript" src="spinningwheel.js?v=1.4"></script>

	<title>Pongalator 4000</title>

<script type="text/javascript">
function openWinner() {
	
	var numbers = { 1: 'Jeff', 2: 'Tim' };
	SpinningWheel.addSlot(numbers, 'right shrink');
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(doneWinner);
	SpinningWheel.open();
}

function openLoser() {
	
	var numbers = { 1: 'Jeff', 2: 'Tim' };
	SpinningWheel.addSlot(numbers, 'right shrink');
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(doneLoser);
	SpinningWheel.open();
}

function openScore() {
	
	var numbers = { 0: 0, 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6, 7: 7, 8: 8, 9: 9, 10: 10 };
	SpinningWheel.addSlot(numbers, 'right shrink');
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(doneScore);
	SpinningWheel.open();
}

function doneScore() {
	var results = SpinningWheel.getSelectedValues();
	document.getElementById('score').value = results.keys[0];
}
function doneWinner() {
	var results = SpinningWheel.getSelectedValues();
	document.getElementById('winner').value = results.keys[0];
}
function doneLoser() {
	var results = SpinningWheel.getSelectedValues();
	document.getElementById('loser').value = results.keys[0];
}

function cancel() {
	document.getElementById('result').innerHTML = 'cancelled!';
}

window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

</script>


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
<table align="center">
<tr>
<td>
<select name="winner">
  <option>Winner</option>
  <option value="1">Jeff</option>
  <option value="2">Tim</option>
</select>
</td>
<td>
<select name="loser">
  <option>Loser</option>
  <option value="1">Jeff</option>
  <option value="2">Tim</option>
</select>
</td>
<td>
Deuce<input type="Checkbox" name="deuce" value="TRUE">
</td>
<td>
<select name="score">
  <option>Losing Score</option>
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
</td>
</tr>
</table>
<form>
<input type="submit" name="log" value="Log Game">
</form>
<?php

  $games_result = mysql_query("SELECT * FROM games WHERE winner IS 1",$db);
  while($this_game = mysql_fetch_array($games_result)) {
    extract($this_game,EXTR_PREFIX_ALL,"that");
    echo "<tr>
          <td>$that_id</td>
          <td>$that_value</td>
          <td>$that_start_date</td>
          <td>$that_end_date</td>
          </tr>";
  }

?>

<?php

  echo "<p>Created by $by ($contact) on $on. Version $version updated on $updated.</p>";

?>

</body>
</html>
