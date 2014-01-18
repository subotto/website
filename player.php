<?php

// Pagina di statistiche di un giocatore

include_once "inclusions.php";

$player = NULL;
$match_id = NULL;	// Partita da visualizzare per prima

if ( array_key_exists("id", $_GET) ) {
	$player_id = $_GET["id"];
	if ( is_numeric($player_id) ) {
		$player_id = (int)$player_id;
		$player = new Player($player_id);
		if ( ! ($player->load()) ) {
			$player = NULL;
		}
		else {
			// Carico anche il resto
			$player->load_participations();
			
			if ( array_key_exists("match_id", $_GET) ) {
				$mid = $_GET["match_id"];
				if ( is_numeric($mid) ) $match_id = (int)$mid;
			}
			
			if ( !array_key_exists( $match_id, $player->participations ) ) $match_id = NULL;
		}
	}
}

$name = "";
if ( ! is_null($player) ) $name = $player->fname." ".$player->lname;

start_html("Statistiche - ".$name);
make_header(true, "history");

if ( is_null($player) ) {
	echo "<h3>Giocatore inesistente.</h3>";
}
else {

	start_box($name);
	
	
	echo '<table id="tabella_top"><tr><td>';
	
	// Menu con le scelte
	
	//make_title("Partecipazioni", 3);
	
	echo '<p><table id="tabella_scelte"><col width="100" />';
	echo '<tr><th id="spart0" class="selected_choice"><a href=\'javascript:change_choice("0")\'>Globale</a></th>';
	//echo '<td id="spart0.1" class="highlighted">'.format_time($player->seconds).'</td><td id="spart0.2" class="highlighted">'.$player->pos_goals.' gol</td>';
	echo '</tr>';
	
	foreach ( $player->participations as $part ) {
		$id = $part[0]->id;
		echo '<tr><th id="spart'.$id.'" class="choice" ><a href=\'javascript:change_choice("'.$id.'")\'>'.$part[0]->year.'</a></th>';
		//echo '<td id="spart'.$id.'.1">'.format_time($part[2]->seconds).'</td><td id="spart'.$id.'.2">'.$part[2]->pos_goals.' gol</td>';
		echo '</tr>';
	}
	
	echo '</table></p>';
	
	echo '</td><td class="side_padding">';
	
	
	// Cose a sinistra
	
	echo '<div id="part0"></div>';
	foreach ( $player->participations as $part ) {
		echo '<div id="part'.$part[0]->id.'"></div>';
	}
	
	echo '</td></tr></table>';
	
	
	// Codice javascript
	
	echo '<script type="text/javascript">';
	
	foreach ( $player->participations as $part ) {
		echo 'hide("part'.$part[0]->id.'");';
	}
	echo 'var pid = "'.$player->id.'";';
	if ( !is_null($match_id) ) echo 'var mid = "'.$match_id.'";';
	else echo 'var mid = "0";';
	
	?>
	
		var current_displayed = "0";
		var loaded = {};
		
		function change_choice(x) {
			if ( !(x in loaded) ) {
				loadXMLDoc( "part".concat( x ), "player_details.php?id=".concat( pid ).concat( "&match_id=" ).concat( x ) );
				/*loadXMLDoc( "graph_time".concat( x ), "graph_time.php?player_id=".concat( pid ).concat( "&match_id=" ).concat( x ) );*/
			}
			
			var s = "spart";
			document.getElementById( s.concat( current_displayed ) ).setAttribute("class", "choice");
			/*
			document.getElementById( s.concat( current_displayed ).concat( ".1" ) ).setAttribute("class", "");
			document.getElementById( s.concat( current_displayed ).concat( ".2" ) ).setAttribute("class", "");
			*/
			document.getElementById( s.concat( x ) ).setAttribute("class", "selected_choice");
			/*
			document.getElementById( s.concat( x ).concat( ".1" ) ).setAttribute("class", "highlighted");
			document.getElementById( s.concat( x ).concat( ".2" ) ).setAttribute("class", "highlighted");
			*/
			
			var p = "part";
			hide( p.concat( current_displayed ) );
			show( p.concat( x ) );
			
			
			
			current_displayed = x;
		}
		
		change_choice( mid );
		
	<?php
	
	echo '</script>';
	
}
end_box();
end_html();

?>
