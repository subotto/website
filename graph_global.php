<?php

include_once 'inclusions.php';

require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_bar.php');

$type = "participants";
if ( array_key_exists("type", $_GET) ) {
	$type = $_GET["type"];
}

$stats = new GeneralStatistics();

$stats->load_graph_data($type);


// Create the graph. These two calls are always required

$width = 450;
$height = 300;

/*
$margin = 0;
if ( $type == "participants" ) $margin = 120;


$width += $margin;
*/

$timeout = 180;	// Tempo (in minuti) di utilizzo del grafico in cache. 0 = infinito.

$graph = new Graph($width, $height, 'auto', $timeout);
$graph->SetScale("textlin");

$theme_class = new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->SetBox(false);

$graph->ygrid->SetFill(false);

$xlabels = array();
foreach ( $stats->graph_data as $tid => $team ) {
	foreach ( $team->graph_data as $year => $val ) {
		$xlabels[] = $year;
	}
	break;
}

$graph->xaxis->SetTickLabels( $xlabels );
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// Create the bar plots



$plots = array();
foreach ( $stats->graph_data as $team ) {
	$temp = array();
	foreach ( $team->graph_data as $val ) $temp[] = $val;
	$plot = new BarPlot( $temp );
	$plots[] = $plot;
}

// Create the grouped bar plot
$gbplot = new GroupBarPlot( $plots );
$gbplot->SetWidth(0.37);
// ...and add it to the graPH
$graph->Add($gbplot);

$colors = array( "#cc1111", "blue", "#1111cc" );
$cont = 0;

foreach ( $plots as $plot ) {
	$plot->SetColor("white");
	$plot->SetFillColor( $colors[ $cont++ ] );
}

$title = "";
if ( $type == "participants" ) $title = "Numero di partecipanti             ";
else $title = "Gol";

$graph->title->Set( $title );

/*
if ( $type == "participants" ) {
	// Aggiungo la legenda
	
	$cont = 0;
	foreach ( $stats->graph_data as $team ) {
		$plots[ $cont++ ]->SetLegend( $team->name );
	}
	
	//$graph->legend->SetReverse();
	
	// Adjust the legend position
	$graph->legend->SetPos(0.01, 0.5,'right','center');
	$graph->legend->SetColumns(1);
	$graph->legend->SetShadow();
}

// Adjust the margin
$graph->SetMargin(50,$margin,40,40);
*/

// Display the graph
$graph->Stroke();


?>
