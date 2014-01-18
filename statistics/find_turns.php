<?php

include_once "../db_connection/config.php";

class Turn {
	public $players;
	public $score;
	public $begin;
	public $end;
	
	public function __construct ($players, $score, $begin, $end) {
		$this->players = $players;
		$this->score = $score;
		$this->begin = $begin;
		$this->end = $end;
	}
}



$conn = connetti();

$i = 1; // Indice della partita

$goals = array(1 => array(), 2 => array());
$res = query("SELECT * FROM events WHERE match_id = ".$i." AND ( type = 'goal' OR type = 'goal_undo' ) ORDER BY timestamp");

foreach ( $res as $row ) {
	if ( $row["type"] == "goal" ) {
		$goals[ (int)$row["team_id"] ][] = $row["timestamp"];
	}
	else {
		array_pop( $goals[ (int)$row["team_id"] ] );
	}
}


/* [Elenco dei gol]
echo count($goals[1])."\n";
foreach ( $goals[1] as $goal ) {
	echo $goal."\n";
}

echo count($goals[2])."\n";
foreach ( $goals[2] as $goal ) {
	echo $goal."\n";
}
*/

$match = query("SELECT * FROM matches WHERE id = ".$i);
$match = $match[0];
$res = query("SELECT * FROM events WHERE match_id = ".$i." AND ( type = 'change' ) ORDER BY timestamp");


$turns = array();
$current_players = array();

$i = array(1 => 0, 2 => 0); // Indici per i gol dei matematici e i gol dei fisici

// Inizializzazione giocatori
for ( $j=0; $j<=1; $j++ ) {
	$current_players[ (int)$res[$j]["team_id"] ] = array( $res[$j]["player_a_id"], $res[$j]["player_b_id"] );
}

$begin = $match["begin"];
$score = array( 1 => 0, 2 => 0);

// Aggiungo un cambio fittizio alla fine, per far rientrare l'ultimo turno tra gli altri (TODO: controllare che non ci siano cambi dopo la fine)
$res[] = array( "timestamp" => $match["end"], "team_id" => "1", "player_a_id" => "1", "player_b_id" => "1" );

foreach ( $res as $id => $change ) {
	if ( $id == 0 || $id == 1 ) continue;
	
	// Guardo cosa succede prima di questo cambio
	
	for ($k=1; $k<=2; $k++) {
		while ( $i[$k] < count($goals[$k]) && strcmp( $goals[$k][$i[$k]], $change["timestamp"] ) < 0 ) {
			// Aggiungo un gol
			$score[$k]++;
			$i[$k]++;
		}
	}
	
	if ( $score[1] == 0 && $score[2] == 0 ) {
		// Non c'è stato un vero turno!
		$current_players[ (int)$change["team_id"] ] = array( $change["player_a_id"], $change["player_b_id"] );
		continue;
	}
	
	// C'è stato un vero turno
	$end = $change["timestamp"];
	$turns[] = new Turn ( $current_players, $score, $begin, $end );
	
	// Ora faccio il cambio
	$begin = $end;
	$current_players[ (int)$change["team_id"] ] = array( $change["player_a_id"], $change["player_b_id"] );
	$score = array( 1 => 0, 2 => 0 );
}

// Stampo i cambi
foreach ( $turns as $turn ) {
	echo $turn->begin . "," . $turn->end . ", " . $turn->players["1"][0] . "," . $turn->players["1"][1] . "," . $turn->players["2"][0] . "," . $turn->players["2"][1] . "," . $turn->score[1] . "," . $turn->score[2] . "\n";
}

disconnetti($conn);



?>
