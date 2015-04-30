<?php
// Load DB
  require("mysql.php");
  $now = date("Y-m-d H:i:s");

// Graphing
  require_once ('jpgraph/jpgraph.php');
  require_once ('jpgraph/jpgraph_line.php');

  $total_score = 0;
  $p1_result = mysqli_query($db,"SELECT * FROM games WHERE winner=1 OR loser=1");
  while($games = mysqli_fetch_array($p1_result)) {
    extract($games,EXTR_PREFIX_ALL,"this");
    if($this_winner==1) {
      $total_score += $this_winner_score;
    } elseif($this_loser==1) {
      $total_score += $this_loser_score;
    }
    $p1_scores[] = $total_score;
  }

  $total_score = 0;
  $p2_result = mysqli_query($db,"SELECT * FROM games WHERE winner=2 OR loser=2");
  while($games = mysqli_fetch_array($p2_result)) {
    extract($games,EXTR_PREFIX_ALL,"this");
    if($this_winner==2) {
      $total_score += $this_winner_score;
    } elseif($this_loser==2) {
      $total_score += $this_loser_score;
    }
    $p2_scores[] = $total_score;
  }
  
// Setup the graph
  $graph = new Graph(300,250);
  $graph->SetScale("textlin");

  $graph->img->SetAntiAliasing(false);
  $graph->title->Set('Pong-Track(R) Beta');
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
  $p1->SetLegend('Jeff');

// Create the second line
  $p2 = new LinePlot($p2_scores);
  $graph->Add($p2);
  $p2->SetColor("#B22222");
  $p2->SetLegend('Tim');

  $graph->legend->SetPos(0,0.5);
  $graph->legend->SetFrameWeight(1);

// Output line
  $graph->Stroke();

?>