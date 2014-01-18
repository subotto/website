<?php

require_once ('../jpgraph/src/jpgraph.php');
require_once ('../jpgraph/src/jpgraph_line.php');

include_once 'inclusions.php';


// CARICAMENTO DATI DAL DB

$dbconn = connetti();


// Squadre
$teams = get_teams();

// Inizio e ora attuale
$time = get_time();

$inizio = $time["begin"];
$adesso = $time["now"];

$minutocorrente = NULL;
$firstgoals = NULL;
$secondgoals = NULL;

if ( $time["begin"] ) {
	$minutocorrente = ( $adesso - $inizio )/60 + 1;
	
	// comment!!!!!!!!!
	//$minutocorrente = 200;
	//$adesso = $time["begin"]+$minutocorrente;
	
	// Prima squadra
	$firstgoals = get_graph_data( $teams[0]["id"], $inizio, $adesso, $minutocorrente );
	
	
	// Seconda squadra
	$secondgoals = get_graph_data( $teams[1]["id"], $inizio, $adesso, $minutocorrente );
}


$firstcolor = "red";
$secondcolor = "blue";

$firstoldcolor = "lightred";
$secondoldcolor = "lightblue";

$colors = get_colors();
if ( !is_null($colors) ) {
	if ( $colors["red"] != $teams[0]["id"] ) {
		list($firstcolor,$secondcolor) = array($secondcolor,$firstcolor);
		list($firstoldcolor,$secondoldcolor) = array($secondoldcolor,$firstoldcolor);
	}
}

disconnetti($dbconn);

// FINE CARICAMENTO DATI


// CARICAMENTO DATI ANNO SCORSO



$y3data = array();
$y4data = array();

if ( $time["begin"] ) {
	
	$olddata = get_old_graph_data($minutocorrente);
	
	$y3data = $olddata[0];
	$y4data = $olddata[1];
}



// FINE CARICAMENTO DATI ANNO SCORSO




$ydata = $firstgoals;
$y2data = $secondgoals;

//if ( count($firstgoals) == 1 ) die("ahia");

if ( !$time["begin"] ) {
	$ydata = array(0,0);
	$y2data = array(0,0);
	$y3data = array(0,0);
	$y4data = array(0,0);
}


$tempo = array();
$n = count($ydata);
for ($i=0; $i<$n; $i++) {
	$minuti = $i % 60;
	$ore = (int)($i/60);
	
	$tempo[] = sprintf("%02d:%02d",$ore,$minuti);
}

// Create the graph and specify the scale for Y-axis
$width=750;$height=500;


$graph = new Graph($width,$height);
$graph->SetScale('textint');
$graph->SetShadow();
 
// Adjust the margin
$graph->SetMargin(65,180,20,0);

// Create the two linear plot
$lineplot=new LinePlot($ydata);
$lineplot2=new LinePlot($y2data);
$lineplot3=new LinePlot($y3data);
$lineplot4=new LinePlot($y4data);
 
// Add the plot to the graph
$graph->Add($lineplot4);
$graph->Add($lineplot3);
$graph->Add($lineplot2);
$graph->Add($lineplot);
 
// Adjust the axis color
$graph->yaxis->SetColor('black');
 
//$graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
//$graph->title->Set('Punteggi della 24 ore');
//$graph->title->SetMargin(10);
 
//$graph->subtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
//$graph->subtitle->Set('(common objects)');
 

//$graph->xaxis->title->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->xaxis->title->Set('Tempo');
//$graph->yaxis->title->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->yaxis->title->Set('Gol');
$graph->yaxis->SetTitleMargin(40);


$graph->xaxis->SetTickLabels($tempo);
$interval = max( (int)($n/8), 1 );
if ( ( $n >= 12 ) && ( $n < 16 ) ) $interval = 2;
if ( $n >= 80 ) {
	$interval = $interval - ( $interval % 10 );
}
$graph->xaxis->SetTextTickInterval($interval,0);


// Set the colors for the plots
$lineplot->SetColor($firstcolor);
$lineplot->SetWeight(2);
$lineplot2->SetColor($secondcolor);
$lineplot2->SetWeight(2);
$lineplot3->SetColor($firstoldcolor);
$lineplot3->SetWeight(2);
$lineplot4->SetColor($secondoldcolor);
$lineplot4->SetWeight(2);


 
// Set the legends for the plots
$lineplot->SetLegend('Matematici');
$lineplot2->SetLegend('Fisici');
$lineplot3->SetLegend('Matematici (2011)');
$lineplot4->SetLegend('Fisici (2011)');

$graph->legend->SetReverse();

// Set the colors of the grid
$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#BBCCFF@0.5');
 
// Adjust the legend position
$graph->legend->SetPos(0.05,0.5,'right','center');
$graph->legend->SetColumns(1);

 
// Display the graph
$graph->Stroke('graph/graph.png');

?>
