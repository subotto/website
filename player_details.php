<?php

// Pagina che viene caricata all'interno di player.php

include_once "inclusions.php";

$player = NULL;
$match_id = NULL;
$match = NULL;

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
			
			if ( array_key_exists("match_id", $_GET) ) {
				$mid = $_GET["match_id"];
				if ( is_numeric($mid) ) $match_id = (int)$mid;
			}
			
			if ( !is_null($match_id) ) {
				$match = new Edition($match_id);
			
				if ( ! ($match->load()) ) {
					$match_id = NULL;
					$match = NULL;
				}
				else {
					$match->load_more_information();
				}
			}
			
			$player->load_participations();	// Questo sarebbe un po' ridondante, visto che viene già fatto in player.php...
											// ma non dovrebbe essere troppo pesante rispetto al resto.
			$player->load_friends_and_enemies($match_id);
		}
	}
}

if ( is_null($player) ) {
	paragraph(bold("Giocatore non trovato."));
}
else {
	
	echo '<table><tr><td>';
	
	if ( is_null($match_id) ) echo "<h3>Statistiche generali</h3>";
	else echo '<h3><a href="statistics.php?match_id='.$match->id.'">Edizione '.$match->year.'</a></h3>';
	
	// Informazioni generali
	
	$seconds = $player->seconds;
	$pos_goals = $player->pos_goals;
	$neg_goals = $player->neg_goals;
	
	if ( !is_null($match_id) ) {
		$seconds = $player->participations[ $match_id ][2]->seconds;
		$pos_goals = $player->participations[ $match_id ][2]->pos_goals;
		$neg_goals = $player->participations[ $match_id ][2]->neg_goals;
	}
	
	
	echo '<p><table id="tabella_informazioni">';
	echo '<col width="130" /><col width="230" />';
	
	if ( is_null($match_id) ) {
		$numpart = count( $player->participations );
		echo "<tr><td>Partecipazioni:</td><td>".$numpart."</td></tr>";
	}
	
	if ( !is_null($match_id) ) {
		$team = $player->participations[ $match_id ][1];
		echo "<tr><td>Squadra:</td><td>".$team->name."</td></tr>";
	}
	
	echo "<tr><td>Tempo di gioco:</td><td>".format_time($seconds)."</td></tr>
			<tr><td>Gol fatti:</td><td>".$pos_goals." (".sprintf("%.2f", $pos_goals/$seconds*60)." / min)</td></tr>";
	
	if ( $pos_goals > $neg_goals ) {
		echo "<tr><td>Gol subiti:</td><td>".$neg_goals." (".sprintf("%.2f", $neg_goals/$seconds*60)." / min)</td></tr>
				<tr><td>Differenza reti:</td><td>".($pos_goals - $neg_goals)." (".sprintf("%.2f", ($pos_goals-$neg_goals) / $seconds*60)." / min)</td></tr>";
	}
	
	if ( !is_null($match_id) ) {
		// Note
		$info = array();
		
		if ( $match->captains[0][0]->id == $player->id || $match->captains[1][0]->id == $player->id ) $info[] = "Capitano dei ".$team->name;
		if ( $match->captains[0][1]->id == $player->id || $match->captains[1][1]->id == $player->id ) $info[] = "Vicecapitano dei ".$team->name;
		for ($i=0; $i<2; $i++) {
			for ($j=0; $j<2; $j++) {
				if ( $match->first_players[$i][$j]->id == $player->id ) $info[] = "titolare insieme a ".$match->first_players[$i][1-$j]->link($match_id);
			}
		}
		
		$n = count($info);
		if ( $n > 0 ) {
			echo "<tr><td>Note:</td><td>";
			for ( $i=0; $i<$n; $i++ ) {
				echo $info[$i];
				if ( $i<$n-1 ) echo ", ";
			}
			echo "</td></tr>";
		}
	}
	
	echo "</table></p>";
	
	// Grafico degli orari di gioco!
	$mid = 0;
	if ( !is_null($match_id) ) $mid = $match_id;
	echo '</td><td><img src="graph_time.php?player_id='.$player->id.'&match_id='.$mid.'" width="520" height="250"/></td></tr></table>';
	
	
	// Tabella con i compagni
	
	echo "<table id=\"tabella_semplice\"><tr>";
	echo "<th>Compagni</th><th>Avversari</th></tr><tr>";
	
	$name_width = 220;
	$time_width = 160;
	
	echo "<td>";
	$head = array("<a href='javascript:void(0)'>Nome</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>");
	$table = array();
	
	$mid = 0;	// Per i link ai giocatori
	if ( !is_null($match_id) ) $mid = $match_id;
	
	foreach ( $player->friends as $friend ) {
		$table[] = array( $friend->link($mid), return_hidden_key($friend->seconds).format_time($friend->seconds) );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width) ) );
	
	echo "</td><td>";
	
	
	// Tabella con gli avversari
	
	$head = array("<a href='javascript:void(0)'>Nome</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>");
	$table = array();
	
	foreach ( $player->enemies as $enemy ) {
		$table[] = array( $enemy->link($mid), return_hidden_key($enemy->seconds).format_time($enemy->seconds) );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width) ) );
	
	echo "</td>";
	
	echo "</tr></table>";
}


?>
