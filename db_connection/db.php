<?php

$GLOBALS["connection"] = FALSE;

function connetti() {
	global $db_config;
	$connetti=pg_connect($db_config);
	if ( !$connetti ) echo "<p><b>Connection to database failed.</b></p>";
	// or die('Could not connect: ' . pg_last_error());
	$GLOBALS["connection"] = $connetti;
	
	return $connetti;
}

function disconnetti($dbconn) {
	$GLOBALS["connection"] = FALSE;
	pg_close($dbconn);
}


function query($query, $formattato = TRUE) {
	if ( !$GLOBALS["connection"] ) return NULL;

	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	
	if ( $formattato ) {
		$res = array();
		if ( pg_num_rows($result) != 0 ) { 
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				$res[] = $line;
			}
		}
		
		$result = $res;
	}
	
	//pg_close($dbconn);
	
	return $result;
}


function get_seconds($time) {
	$struct = new DateTime($time);
	return $struct->getTimestamp();
}

// FUNZIONI PER CARICARE I DATI. Tutte assumono che la connessione sia aperta

function get_teams() {
	$teams = query("SELECT * FROM teams");
	
	return $teams;
}


function get_score($teamid) {
	$positive = query("SELECT count(1) FROM events WHERE type='goal' AND param='".$teamid."'");
	$negative = query("SELECT count(1) FROM events WHERE type='goal_undo' AND param='".$teamid."'");
	
	$score = (int)($positive[0]["count"]) - (int)($negative[0]["count"]);
	
	return $score;
}


function get_time() {
	
	$result = array();

	$res = query("SELECT type,timestamp FROM events WHERE type='begin' OR type='end' OR type='sched_begin' OR type='sched_end'");
	
	foreach ($res as $row) {
		$coso = new DateTime($row["timestamp"]);
		$result[$row["type"]] = $coso->getTimestamp();
	}
	
	$res = query("SELECT current_timestamp");
	$adesso = new DateTime($res[0]["now"]);
	$result["now"] = $adesso->getTimestamp();
	
	if ( isset($result["end"]) ) $result["now"] = min($result["now"], $result["end"]);
	
	return $result;
	
}

function get_current_players($teamid) {
	$res = query("SELECT timestamp,param FROM events WHERE type='change' AND param LIKE '".$teamid.",%' ORDER BY timestamp DESC LIMIT 1");
	if ( count($res) == 0 ) return NULL;
	
	$ids = $res[0]["param"];
	
	$lastchange = $res[0]["timestamp"];
	
	$pippo = strtok($ids,',');
	$firstid = strtok(','); 
	$secondid = strtok(',');

	$firstname = NULL;
	$res = query("SELECT * FROM players WHERE id=".$firstid);
	if ( count($res) > 0 ) $firstname = $res[0]["fname"]." ".$res[0]["lname"];

	$secondname = NULL;
	$res = query("SELECT * FROM players WHERE id=".$secondid);
	if ( count($res) > 0 ) $secondname = $res[0]["fname"]." ".$res[0]["lname"];
	
	return array( $firstname, $secondname, "last" => $lastchange );
}


function get_partial($teamid,$lastfirstchange,$lastsecondchange) {
	if ( is_null($lastfirstchange) || is_null($lastfirstchange) ) return NULL;
	
	$positive = query("SELECT count(1) FROM events WHERE type='goal' AND param='".$teamid."' AND timestamp > '".$lastfirstchange."' AND timestamp > '".$lastsecondchange."'");
	$negative = query("SELECT count(1) FROM events WHERE type='goal_undo' AND param='".$teamid."' AND timestamp > '".$lastfirstchange."' AND timestamp > '".$lastsecondchange."'");
	$partial = (int)($positive[0]["count"]) - (int)($negative[0]["count"]);
	
	return $partial;
}


function get_graph_data($teamid,$inizio,$adesso,$minutocorrente) {
	$ris = query("SELECT timestamp,type FROM events WHERE (type='goal' OR type='goal_undo') AND param='".$teamid."' ORDER BY timestamp");
	$goals = array();
	foreach ($ris as $row) {
		$orario = get_seconds($row["timestamp"]);
	
		$val = 1;
		if ( $row["type"] == "goal" ) $val = 1;
		else $val = -1;
	
		$minuto = ($orario - $inizio)/60 + 1;
		if (!isset($goals[$minuto])) $goals[$minuto]=0;
		$goals[$minuto] += $val;
	}

	$totgoals = array();
	$totgoals[0] = 0;
	for ($i=1; $i<=$minutocorrente; $i++) {
		if (!isset($goals[$i])) $goals[$i]=0;
		$totgoals[$i] = $totgoals[$i-1] + $goals[$i];
	}
	
	return $totgoals;
}

function get_old_graph_data($mincorrente) {
	$minutocorrente = $mincorrente - 1;
	$dati = file("graph/plot_punteggi.txt");

	$firstres = array(0);
	$secondres = array(0);
	
	$min = 0;
	
	foreach ( $dati as $line ) {
		$orario = (float)(strtok($line,' '));
		$mathscore = (int)(strtok(' '));
		$physscore = (int)(strtok(' '));
	
		if ( $orario < 22 ) $orario += 24;
		$orario -= 22;
	
		$minuti = (int)($orario*60);
		
		while ( $minuti > $min ) {
			$min++;
			$firstres[] = $mathscore;
			$secondres[] = $physscore;
		}
		
		if ( $minuti > $minutocorrente ) break;
		
	}
	
	return array($firstres,$secondres);
}


function get_last_goal() {
	$ris = query("SELECT param FROM events WHERE type='goal' ORDER BY timestamp DESC LIMIT 1");
	return $ris[0]["param"];
}

function get_colors() {
	$ris = query("SELECT param FROM events WHERE type='swap' ORDER BY timestamp DESC LIMIT 1");
	if ( count($ris) == 0 ) return NULL;
	
	$coso = $ris[0]["param"];
	
	$red = strtok($coso,',');
	$blue = strtok(',');
	
	$res = array( "red" => $red, "blue" => $blue );
	return $res;
}

?>
