<?php
// Version 0.2

// Load DB
  require("mysql.php");
  $now = date("Y-m-d H:i:s");

// Graphing
  require_once ('jpgraph/jpgraph.php');
  require_once ('jpgraph/jpgraph_line.php');

//Get Variables or default to all
if(isset($_REQUEST['date'])) {
  $start_date = date("Y-m-d H:i:s",strtotime("last day of last month"));
  //$start_date = date("Y-m-d H:i:s",strtotime($_REQUEST['date']));
} else {
  $start_date = "2014-01-01 00:00:00";
}
  $p1_total_score = 0;
  $p1_scores = array();
  $p2_total_score = 0;
  $p2_scores = array();
  //$sql = "SELECT * FROM `games` WHERE `winner` = 1 OR `loser` = 1";
  //echo "check db.";
  $sql = "SELECT * FROM `games` WHERE `date` >= '$start_date'";

  $p_result = mysqli_query($db,$sql) or die(mysql_error());
  while($games = mysqli_fetch_array($p_result)) {
    //echo "inside result loop.";
    extract($games,EXTR_PREFIX_ALL,"this");
    if($this_winner==1) {
      $p1_total_score += $this_winner_score;
    } elseif($this_winner==2) {
      $p2_total_score += $this_winner_score;
    }
    if($this_loser==1) {
      $p1_total_score += $this_loser_score;
    } elseif($this_loser==2) {
      $p2_total_score += $this_loser_score;
    }
    $p1_scores[] = $p1_total_score;
    $p2_scores[] = $p2_total_score;
    //print_r($p1_scores);
  }

  
// Setup the graph
  $graph = new Graph(300,250);
  $graph->SetScale("textlin");

  $graph->img->SetAntiAliasing(false);
  $graph->title->Set('Pong-Track(R) Beta');
  $graph->subtitle->Set('since '.$start_date);
  $graph->SetBox(false);

  $graph->img->SetAntiAliasing();

  //$graph->yaxis->HideZeroLabel();
  $graph->yaxis->HideLine(true);
  $graph->yaxis->HideTicks(true,true);
 
  //$graph->yaxis->HideLabel();
  $graph->xaxis->Hide();
  //$graph->xaxis->HideTicks(true,true);
  //$graph->xgrid->Show();
  //$graph->xgrid->SetLineStyle("solid");
  //$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
  $p1 = new LinePlot($p1_scores);
  $graph->Add($p1);
  $p1->SetColor("#6495ED");
  //$p1->SetFillColor("#6495ED@0.6");
  //$p1->SetFillColor("yellow@0.6");
  $p1->SetLegend('Jeff');

// Create the second line
  $p2 = new LinePlot($p2_scores);
  $graph->Add($p2);
  $p2->SetColor("#B22222");
  //$p2->SetFillColor("#B22222@0.6");
  //$p2->SetFillColor("red@0.6");
  $p2->SetLegend('Tim');

  $graph->legend->SetPos(0,0.5);
  $graph->legend->SetFrameWeight(1);

// Output line
  $graph->Stroke();

?>