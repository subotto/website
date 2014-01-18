<?php

// Pagina di statistiche delle passate 24 ore

include_once "inclusions.php";

$edition = NULL;


if ( array_key_exists("match_id", $_GET) ) {
	$match_id = $_GET["match_id"];
	if ( is_numeric($match_id) ) {
		$match_id = (int)$match_id;
		$edition = new Edition($match_id);
		if ( ! ($edition->load()) ) {
			$edition = NULL;
		}
		else {
			// Carico anche il resto
			$edition->load_participants();
		}
	}
}


start_html("Statistiche");
make_header();

if ( is_null($edition) ) {
	
	// Statistiche generali di tutte le 24 ore
	make_title("Statistiche generali");
	
	/*
	
	Tabella con le due squadre ([per ora no] numero di vittorie, numero di gol fatti in totale, numero di partecipanti, capitani
	Tabella con le migliori statistiche positive
	Tabella con i giocatori (tempo di gioco totale, gol fatti totali, partecipazioni con squadra/e)
	
	*/
	
	$stats = new GeneralStatistics();
	$stats->load_teams();
	$stats->load_participants();
	$stats->load_couples();
	
	// Tabella con le due squadre
	
	echo "<h3>Squadre</h3>";
	
	$head = array();
	$head[] = "";
	foreach ( $stats->teams as $team ) {
		$head[] = $team->name;
	}
	
	$table = array();
	
	$row = array();
	$row[] = "Gol totali";
	foreach ( $stats->teams as $team ) {
		$row[] = "&#160;".$team->score;
	}
	$table[] = $row;
	
	$row = array();
	$row[] = "Partecipanti";
	foreach ( $stats->teams as $team ) {
		$row[] = "&#160;".$team->num_participants;
	}
	$table[] = $row;
	
	$row = array();
	$row[] = "Capitani e vicecapitani";
	foreach ( $stats->teams as $team ) {
		$captains = $team->captains;
		
		$subtable = array();
		foreach ( $captains as $match_id => $couple ) {
			$subtable[] = array( $couple[0]->link().", ".$couple[1]->link(), " (".$stats->editions[$match_id]->link().")" );
		}
		
		$row[] = return_table($subtable, FALSE, FALSE, FALSE, array("td" => "class=\"zero\"") );
	}
	$table[] = $row;
	
	$first_width = 120;
	$second_width = 350;
	
	$col_widths = array( $first_width );
	foreach ( $stats->teams as $team ) {
		$col_widths[] = $second_width;
	}
	
	make_table($table, "tabella", $head, FALSE, array("col_widths" => $col_widths ) );
	
	
	echo "<br />";
	
	$table = array( array( "<div class=\"selected_choice\">Partecipanti</div>", "<div class=\"choice\">Statistiche individuali</div>", "<div class=\"choice\">Statistiche per coppie</div>" ) );
	make_table( $table, "tabella", NULL, FALSE, array("col_widths" => array(300, 300, 300) ) );
	
	
	
	// Tabella con tutti i partecipanti
	
	echo "<h3>Partecipanti</h3>";
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	$part_width = 300;
	
	$head = array("<a>Nome</a>", "<a>Tempo di gioco</a>", "<a>Gol fatti</a>", "<a>Squadra e partecipazioni</a>");
	$table = array();
	
	foreach ( $stats->participants as $player ) {
		$seconds = $player->seconds;
		$pos_goals = $player->pos_goals;
		$num_participations = count($player->participations);
		
		// Scrivo le partecipazioni
		$part = array();
		foreach ( $player->participations as $p ) {
			$edition = $p[0];
			$team = $p[1];
			
			if (!array_key_exists($team->name,$part)) {
				$part[$team->name] = $team->name." (".$edition->link();
			}
			else {
				$part[$team->name] .= ", ".$edition->link();
			}
		}
		
		$participations = "";
		
		$flag = TRUE;
		foreach ( $part as $p ) {
			if ( $flag ) $flag = FALSE;
			else $participations .= ", ";
			
			$participations .= $p.")";
		}
		
		$table[] = array( $player->link(), return_hidden_key($seconds).format_time($seconds), $player->pos_goals, return_hidden_key($num_participations).return_hidden_key($seconds).$participations );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width, $part_width) ) );
	
	
	// Statistiche migliori individuali
	
	/*
	Consideriamo solo i giocatori che hanno giocato per >=30min*num_partite e con differenza reti positiva.
	
	- Tempo giocato
	- Gol fatti
	- Gol subiti
	- Differenza reti
	- Differenza reti / tempo
	- Gol fatti / tempo
	- Gol subiti / tempo
	Stessa cosa per le coppie
	*/
	
	echo "<h3>Statistiche individuali</h3>";
	paragraph("Partecipanti con almeno 2 ore di gioco totali e differenza reti positiva.");
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	$part_width = 300;
	
	$head = array("<a>Nome</a>", "<a>Tempo di gioco</a>", "<a>Gol fatti</a>", "<a>Gol subiti</a>", "<a>Diff. reti</a>", "<a>GF/minuto</a>", "<a>GS/minuto</a>", "<a>Diff/minuto</a>", "<a>Squadra e partecipazioni</a>");
	$table = array();
	
	foreach ( $stats->participants as $player ) {
		
		$seconds = $player->seconds;
		$pos_goals = $player->pos_goals;
		$neg_goals = $player->neg_goals;
		$diff = $pos_goals - $neg_goals;
		
		$num_participations = count($player->participations);
		$num_editions = count($stats->editions);
		
		// Check per sapere se mettere questo giocatore o no
		if ( $seconds < 30*60*$num_editions || $diff <= 0 ) continue;
		
		$minutes = $seconds / 60;
		$pos_rate = $pos_goals / $minutes;
		$neg_rate = $neg_goals / $minutes;
		$diff_rate = $diff / $minutes;
		
		// Scrivo le partecipazioni
		$part = array();
		foreach ( $player->participations as $p ) {
			$edition = $p[0];
			$team = $p[1];
			
			if (!array_key_exists($team->name,$part)) {
				$part[$team->name] = $team->name." (".$edition->link();
			}
			else {
				$part[$team->name] .= ", ".$edition->link();
			}
		}
		
		$participations = "";
		
		$flag = TRUE;
		foreach ( $part as $p ) {
			if ( $flag ) $flag = FALSE;
			else $participations .= ", ";
			
			$participations .= $p.")";
		}
		
		$table[] = array( $player->link(), return_hidden_key($seconds).format_time($seconds), $pos_goals, $neg_goals, $diff, sprintf("%.2f",$pos_rate), sprintf("%.2f",$neg_rate), sprintf("%.2f",$diff_rate), return_hidden_key($num_participations).return_hidden_key($seconds).$participations );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $part_width) ) );
	
	
	// Statistiche migliori per coppie
	
	echo "<h3>Statistiche per coppie</h3>";
	paragraph("Prime 60 coppie per tempo giocato.");
	
	$name_width = 400;
	$time_width = 160;
	$goals_width = 100;
	$part_width = 300;
	
	$head = array("<a>Coppia</a>", "<a>Tempo di gioco</a>", "<a>Gol fatti</a>", "<a>Gol subiti</a>", "<a>Diff. reti</a>", "<a>GF/minuto</a>", "<a>GS/minuto</a>", "<a>Diff/minuto</a>");
	$table = array();
	
	foreach ( $stats->couples as $couple ) {
		
		$seconds = $couple->seconds;
		$pos_goals = $couple->pos_goals;
		$neg_goals = $couple->neg_goals;
		$diff = $pos_goals - $neg_goals;
		
		$minutes = $seconds / 60;
		$pos_rate = $pos_goals / $minutes;
		$neg_rate = $neg_goals / $minutes;
		$diff_rate = $diff / $minutes;
		
		$table[] = array( $couple->link(), return_hidden_key($seconds).format_time($seconds), $pos_goals, $neg_goals, $diff, sprintf("%.2f",$pos_rate), sprintf("%.2f",$neg_rate), sprintf("%.2f",$diff_rate) );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width) ) );
	
}




else {
	
	// Statistiche relative ad una singola edizione della 24 ore
	make_title("24 ore ".$edition->year);
	
	
	// Informazioni generali
	$table = array(	array( "Inizio:", $edition->begin->format_ita() ),
					array( "Fine:",   $edition->end->format_ita() ),
					array( "Luogo:",  $edition->place ) );
	paragraph(return_table($table));
	
	
	// Tabella delle due squadre
	
	$fwidth = 380;
	$swidth = 60;
	
	echo "<table id=\"tabella\"><col width=\"" . $fwidth . "\"><col width=\"" . $swidth . "\"><col width=\"" . $swidth . "\"><col width=\"" . $fwidth . "\">\n";
	
	echo "<tr><th class=\"center\">" . $edition->teams[0]->name . "</th><td class=\"center\">" . $edition->score[0] . "</td><td class=\"center\">" . $edition->score[1] . "</td><th class=\"center\">" . $edition->teams[1]->name . "</th></tr>\n";
	
	echo "<tr>";
	for ($i=0; $i<=1; $i++) {
		echo "<td colspan=\"2\">";
		
		$table = array();
		$table[] = array( "Capitano:", $edition->captains[$i][0]->link() );
		$table[] = array( "Vicecapitano:", $edition->captains[$i][1]->link() );
		$table[] = array( "Prima coppia:", $edition->first_players[$i][0]->link().", ".$edition->first_players[$i][1]->link() );
		$table[] = array( "Partecipanti:", count($edition->teams[$i]->participants) );
		
		make_table($table, FALSE, FALSE, FALSE, array("td" => "class=\"zero\"") );
		
		echo "</td>";
	}
	echo "</tr>\n";
	
	echo "</table>";
	
	
	// Tabella delle statistiche
	
	/*
	- Tempo giocato
	- Gol fatti
	- Differenza reti / tempo
	- Gol fatti / tempo
	- Gol subiti / tempo
	
	Stessa cosa per le coppie
	*/
	
	echo "<h3>Statistiche</h3>";
	
	$head = array("", $edition->teams[0]->name, $edition->teams[1]->name);
	$table = array();
	
	make_table($table, "tabella", $head, FALSE);
	
	
	// Tabella dei partecipanti
	
	echo "<h3>Partecipanti</h3>";
	echo "<table id=\"tabella_semplice\"><tr>";
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	
	for ($i=0; $i<=1; $i++) {
		echo "<td>";
		
		$head = array("<a>Nome</a>", "<a>Tempo di gioco</a>", "<a>Gol fatti</a>");
		$table = array();
		
		foreach ( $edition->teams[$i]->participants as $player ) {
			$seconds = $player->seconds;
			$pos_goals = $player->pos_goals;
			$table[] = array( $player->link(), return_hidden_key($seconds).format_time($seconds), $player->pos_goals );
		}
		
		make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width) ) );
		
		echo "</td>";
	}
	
	echo "</tr></table>";
	
}

end_html();

?>
