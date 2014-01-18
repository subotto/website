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
			$edition->load_more_information();
			$edition->load_participants();
			$edition->load_couples();
		}
	}
}


start_html("Statistiche");
make_header(true, "history");

if ( is_null($edition) ) {
	
	// Statistiche generali di tutte le 24 ore
	start_box("Statistiche generali");
	
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
	$second_width = 380;
	
	$col_widths = array( $first_width );
	foreach ( $stats->teams as $team ) {
		$col_widths[] = $second_width;
	}
	
	make_table($table, "tabella", $head, FALSE, array("col_widths" => $col_widths ) );
	
	
	echo "<br />";
	
	// Grafici
	echo '<div class="graph_score">';
	echo '<img src="graph_global.php?type=goals" />';
	echo '<img src="graph_global.php?type=participants" />';
	echo '</div>';
	
	?>
	
	<p><table id="tabella_scelte"><col width="220" /><col width="220" /><col width="220"><tr>
	
		<th id="A" class="selected_choice" onclick="alpha()"><a href='javascript:void(0)'>Partecipanti</a></th>
		<th id="B" class="choice" onclick="beta()"><a href='javascript:void(0)'>Statistiche individuali</a></th>
		<th id="C" class="choice" onclick="gamma()" ><a href='javascript:void(0)'>Statistiche per coppie</a></th>
	
	</tr></table></p>
	
	<?php
	
	// Tabella con tutti i partecipanti
	
	echo "<div id=\"partecipanti\">";
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	$part_width = 300;
	
	$head = array("<a href='javascript:void(0)'>Nome</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>", "<a href='javascript:void(0)'>Gol fatti</a>", "<a href='javascript:void(0)'>Squadra e partecipazioni</a>");
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
	
	
	echo "</div>";
	
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
	
	echo "<div id=\"statistiche_individuali\">";
	
	paragraph("Tutti i partecipanti con almeno 2 ore di gioco totali e differenza reti positiva.");
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	$part_width = 300;
	
	$head = array("<a href='javascript:void(0)'>Nome</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>", "<a href='javascript:void(0)'>Gol fatti</a>", "<a href='javascript:void(0)'>Gol subiti</a>", "<a href='javascript:void(0)'>Diff. reti</a>", "<a href='javascript:void(0)'>GF/minuto</a>", "<a href='javascript:void(0)'>GS/minuto</a>", "<a href='javascript:void(0)'>Diff/minuto</a>", "<a href='javascript:void(0)'>Squadra e partecipazioni</a>");
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
	
	
	echo "</div>";
	
	
	// Statistiche migliori per coppie
	
	echo "<div id=\"statistiche_coppie\">";
	
	paragraph("Le 60 coppie che hanno giocato per più tempo.");
	
	$name_width = 400;
	$time_width = 160;
	$goals_width = 100;
	$part_width = 300;
	
	$head = array("<a href='javascript:void(0)'>Coppia</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>", "<a href='javascript:void(0)'>Gol fatti</a>", "<a href='javascript:void(0)'>Gol subiti</a>", "<a href='javascript:void(0)'>Diff. reti</a>", "<a href='javascript:void(0)'>GF/minuto</a>", "<a href='javascript:void(0)'>GS/minuto</a>", "<a href='javascript:void(0)'>Diff/minuto</a>");
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
		
		$pr = sprintf("%.2f",$pos_rate);
		$nr = sprintf("%.2f",$neg_rate);
		$dr = sprintf("%.2f",$diff_rate);
		
		if ( $diff <= 0 ) {
			// Oscuro i dati nel caso in cui la differenza reti sia <= 0.
			$neg_goals = "";
			$diff = "";
			$nr = "";
			$dr = "";
		}
		
		$table[] = array( $couple->link(), return_hidden_key($seconds).format_time($seconds), $pos_goals, $neg_goals, $diff, $pr, $nr, $dr );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width) ) );
	
	echo "</div>";
	
	
}




else {
	
	// Statistiche relative ad una singola edizione della 24 ore
	start_box("Statistiche 24 ore: ".$edition->year);
	
	
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
		$table[] = array( "Capitano:", $edition->captains[$i][0]->link($match_id) );
		$table[] = array( "Vicecapitano:", $edition->captains[$i][1]->link($match_id) );
		$table[] = array( "Prima coppia:", $edition->first_players[$i][0]->link($match_id).", ".$edition->first_players[$i][1]->link($match_id) );
		$table[] = array( "Partecipanti:", count($edition->teams[$i]->participants) );
		
		make_table($table, FALSE, FALSE, FALSE, array("td" => "class=\"zero\"") );
		
		echo "</td>";
	}
	echo "</tr>\n";
	
	echo "</table>";
	
	
	// Grafico
	
	echo '<div class="graph_score"><img src="graph_score.php?match_id='.$edition->id.'" /></div>';
	
	
	?>
	
	<br />
	<p><table id="tabella_scelte"><col width="220" /><col width="220" /><col width="220"><tr>
	
		<th id="A" class="selected_choice" onclick="alpha()"><a href='javascript:void(0)'>Partecipanti</a></th>
		<th id="B" class="choice" onclick="beta()"><a href='javascript:void(0)'>Statistiche individuali</a></th>
		<th id="C" class="choice" onclick="gamma()" ><a href='javascript:void(0)'>Statistiche per coppie</a></th>
	
	</tr></table></p>
	
	<?php
	
	
	// Tabella dei partecipanti
	
	echo "<div id=\"partecipanti\">";
	echo "<table id=\"tabella_semplice\"><tr>";
	
	echo "<th>". $edition->teams[0]->name ."</th><th>". $edition->teams[1]->name ."</th></tr><tr>";
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	
	for ($i=0; $i<=1; $i++) {
		echo "<td>";
		
		$head = array("<a href='javascript:void(0)'>Nome</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>", "<a href='javascript:void(0)'>Gol fatti</a>");
		$table = array();
		
		foreach ( $edition->teams[$i]->participants as $player ) {
			$seconds = $player->seconds;
			$pos_goals = $player->pos_goals;
			$table[] = array( $player->link($match_id), return_hidden_key($seconds).format_time($seconds), $player->pos_goals );
		}
		
		make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width) ) );
		
		echo "</td>";
	}
	
	echo "</tr></table>";
	echo "</div>";
	
	
	// Tabella delle statistiche
	
	/*
	- Tempo giocato
	- Gol fatti
	- Differenza reti / tempo
	- Gol fatti / tempo
	- Gol subiti / tempo
	
	Stessa cosa per le coppie
	*/
	
	
	echo "<div id=\"statistiche_individuali\">";
	
	
	paragraph("Tutti i partecipanti con almeno 20 minuti di gioco e differenza reti positiva.");
	
	$name_width = 220;
	$time_width = 160;
	$goals_width = 100;
	$team_width = 105;
	
	$head = array("<a href='javascript:void(0)'>Nome</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>", "<a href='javascript:void(0)'>Gol fatti</a>", "<a href='javascript:void(0)'>Gol subiti</a>", "<a href='javascript:void(0)'>Diff. reti</a>", "<a href='javascript:void(0)'>GF/minuto</a>", "<a href='javascript:void(0)'>GS/minuto</a>", "<a href='javascript:void(0)'>Diff/minuto</a>", "<a href='javascript:void(0)'>Squadra</a>");
	$table = array();
	
	// Creo l'array con i partecipanti che soddisfano le condizioni
	$participants = array();
	
	for ($i=0; $i<=1; $i++) {
		foreach ( $edition->teams[$i]->participants as $player ) {
			$seconds = $player->seconds;
			$pos_goals = $player->pos_goals;
			$neg_goals = $player->neg_goals;
			
			// Check per decidere se mettere o no questo giocatore
			if ( $seconds >= 20*60 && $pos_goals > $neg_goals ) {
				$participants[$player->fname." ".$player->lname." ".$player->comment." ".$player->id] = $player;
			}
		}
	}
	
	ksort( $participants );
	
	foreach ( $participants as $player ) {
		
		$seconds = $player->seconds;
		$pos_goals = $player->pos_goals;
		$neg_goals = $player->neg_goals;
		$diff = $pos_goals - $neg_goals;
		
		$minutes = $seconds / 60;
		$pos_rate = $pos_goals / $minutes;
		$neg_rate = $neg_goals / $minutes;
		$diff_rate = $diff / $minutes;
		
		$team = $player->team->name;
		
		$table[] = array( $player->link($match_id), return_hidden_key($seconds).format_time($seconds), $pos_goals, $neg_goals, $diff, sprintf("%.2f",$pos_rate), sprintf("%.2f",$neg_rate), sprintf("%.2f",$diff_rate), $team );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $team_width) ) );
	
	echo "</div>";
	
	
	// Statistiche delle coppie che hanno giocato per più tempo
	
	echo "<div id=\"statistiche_coppie\">";
	
	paragraph("Le 60 coppie che hanno giocato per più tempo.");
	
	$name_width = 360;
	//$time_width = 160;
	//$goals_width = 100;
	//$team_width = 160;
	
	$head = array("<a href='javascript:void(0)'>Coppia</a>", "<a href='javascript:void(0)'>Tempo di gioco</a>", "<a href='javascript:void(0)'>Gol fatti</a>", "<a href='javascript:void(0)'>Gol subiti</a>", "<a href='javascript:void(0)'>Diff. reti</a>", "<a href='javascript:void(0)'>GF/minuto</a>", "<a href='javascript:void(0)'>GS/minuto</a>", "<a href='javascript:void(0)'>Diff/minuto</a>", "<a href='javascript:void(0)'>Squadra</a>");
	$table = array();
	
	foreach ( $edition->couples as $couple ) {
		
		$seconds = $couple->seconds;
		$pos_goals = $couple->pos_goals;
		$neg_goals = $couple->neg_goals;
		$diff = $pos_goals - $neg_goals;
		
		$minutes = $seconds / 60;
		$pos_rate = $pos_goals / $minutes;
		$neg_rate = $neg_goals / $minutes;
		$diff_rate = $diff / $minutes;
		
		$pr = sprintf("%.2f",$pos_rate);
		$nr = sprintf("%.2f",$neg_rate);
		$dr = sprintf("%.2f",$diff_rate);
		
		if ( $diff <= 0 ) {
			// Oscuro i dati nel caso in cui la differenza reti sia <= 0.
			$neg_goals = "";
			$diff = "";
			$nr = "";
			$dr = "";
		}
		
		$table[] = array( $couple->link($match_id), return_hidden_key($seconds).format_time($seconds), $pos_goals, $neg_goals, $diff, $pr, $nr, $dr, $couple->team->name );
	}
	
	make_table($table, "tabella", $head, FALSE, array("table" => "class=\"sortable\"", "col_widths" => array($name_width, $time_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $goals_width, $team_width) ) );
	
	
	echo "</div>";
}



?>

<script type="text/javascript">

function alpha() {
	show("partecipanti");
	hide("statistiche_individuali");
	hide("statistiche_coppie");
	
	document.getElementById("A").setAttribute("class", "selected_choice");
	document.getElementById("B").setAttribute("class", "choice");
	document.getElementById("C").setAttribute("class", "choice");
}

function beta() {
	hide("partecipanti");
	show("statistiche_individuali");
	hide("statistiche_coppie");
	
	document.getElementById("A").setAttribute("class", "choice");
	document.getElementById("B").setAttribute("class", "selected_choice");
	document.getElementById("C").setAttribute("class", "choice");
}

function gamma() {
	hide("partecipanti");
	hide("statistiche_individuali");
	show("statistiche_coppie");
	
	document.getElementById("A").setAttribute("class", "choice");
	document.getElementById("B").setAttribute("class", "choice");
	document.getElementById("C").setAttribute("class", "selected_choice");
}

alpha();


</script>

<?php
	

end_box();
end_html();

?>
