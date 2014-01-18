<?php

// Grafico con gli orari di gioco, che viene caricato all'interno di player.php

include_once "inclusions.php";

require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_bar.php');

$player = NULL;
$match_id = NULL;
$match = NULL;

if ( array_key_exists("player_id", $_GET) ) {
	$player_id = $_GET["player_id"];
	if ( is_numeric($player_id) ) {
		$player_id = (int)$player_id;
		$player = new Player($player_id);
		
		if ( array_key_exists("match_id", $_GET) ) {
			$mid = $_GET["match_id"];
			if ( is_numeric($mid) ) $match_id = (int)$mid;
		}
		if ( $match_id == 0 ) $match_id = NULL;
		
		$player->load_play_time($match_id);
		//var_dump($player->play_time);
		
		
		$datay = $player->play_time;
		
		// Create the graph
		//TODO: settare un timeout giusto
		$timeout = 180; // in minuti, tempo massimo entro il quale usa la cache del grafico
		$graph = new Graph(520, 250, 'auto', $timeout);
		$graph->SetScale("textlin");
		
		$theme_class=new UniversalTheme;
		$graph->SetTheme($theme_class);

		//$graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
		$graph->SetBox(false);

		$graph->ygrid->SetFill(false);
		$graph->xaxis->SetTickLabels(array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'));
		
		// Altro tentativo, per avere le ore segnate tra una barra e l'altra - Fallito
		//$graph->SetScale('intlin',0,0,0,24);
		//$graph->xaxis->scale->ticks->Set(1,1);
		
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// Create the bar plots
		$bplot = new BarPlot($datay);

		// ...and add it to the graPH
		$graph->Add($bplot);

		$bplot->SetColor("white");
		$bplot->SetFillColor("#cc1111");
		
		// Titoli
		$graph->title->Set("Orari di gioco");
		$graph->yaxis->title->Set("Tempo (minuti) ");
		
		// Margini
		$graph->SetMargin(45,15,40,30);
		
		// Background image
		$graph->SetBackgroundImage("images/giorno.png",BGIMG_FILLPLOT);
		$graph->SetBackgroundImageMix(100);
		
		// Display the graph
		$graph->Stroke();
	}
}


?>
