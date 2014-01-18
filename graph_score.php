<?php

include_once 'inclusions.php';

require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');


$match_id = NULL;
$match = NULL;

if ( array_key_exists("match_id", $_GET) ) {
	$match_id = $_GET["match_id"];
	if ( is_numeric($match_id) ) {
		$match_id = (int)$match_id;
		$match = new Edition($match_id);
		
		if ( $match->load() ) {
			$match->load_graph_data();
			
				
			$firstcolor = "red";
			$secondcolor = "blue";
			
			$ydata = $match->graph_data[0];
			$y2data = $match->graph_data[1];
			
			$tempo = array();
			$n = count($ydata);
			for ($i=0; $i<$n; $i++) {
				$minuti = $i % 60;
				$ore = (int)($i/60);
	
				$tempo[] = sprintf("%02d:%02d",$ore,$minuti);
			}

			// Create the graph and specify the scale for Y-axis
			$width=750;$height=500;
			
			//TODO: settare un timeout giusto
			$timeout = 0;	// in minuti, tempo massimo entro il quale usa la cache del grafico
							// 0 dovrebbe voler dire infinito

			$graph = new Graph($width, $height, 'auto', 0);
			$graph->SetScale('textint');
			$graph->SetShadow();
			 
			// Adjust the margin
			$graph->SetMargin(65,150,60,0);

			// Create the two linear plot
			$lineplot = new LinePlot($ydata);
			$lineplot2 = new LinePlot($y2data);
			 
			// Add the plot to the graph
			$graph->Add($lineplot2);
			$graph->Add($lineplot);
			 
			// Adjust the axis color
			//$graph->yaxis->SetColor('black');
			 
			//$graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
			$graph->title->Set('Andamento del punteggio                        ');
			$graph->title->SetMargin(35);
			
			$graph->xaxis->title->Set('Tempo');
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
			$lineplot->SetWeight(1.5);
			$lineplot2->SetColor($secondcolor);
			$lineplot2->SetWeight(1);
			 
			// Set the legends for the plots
			$lineplot->SetLegend( $match->teams[0]->name );
			$lineplot2->SetLegend( $match->teams[1]->name );

			$graph->legend->SetReverse();

			// Set the colors of the grid
			//$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#BBCCFF@0.5');
			 
			// Adjust the legend position
			$graph->legend->SetPos(0.25,0.5,'right','center');
			$graph->legend->SetColumns(1);
			$graph->legend->SetShadow();
			
			// Display the graph
			$graph->Stroke();
		}
	}
}

?>
