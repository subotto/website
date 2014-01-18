<?php

include_once "../db_connection/config.php";
include_once "../db_connection/db.php";
include_once "../inclusions.php";


$conn = connetti();

$i = 1; // Indice della partita

$goals = array(1 => array(), 2 => array());
$res = query("SELECT * FROM events WHERE match_id = ".$i." AND ( type = 'goal' OR type = 'goal_undo' ) ORDER BY timestamp");


foreach ( $res as $row ) {
	if ( $row["type"] == "goal" ) {
		$goals[ $row["team_id"] ][] = $row["timestamp"];
	}
	else {
		array_pop( $goals[ $row["team_id"] ] );
	}
}


// Carico le squadre e inizio/fine partita
$res = query("SELECT ta.id AS ta_id, tb.id AS tb_id, ta.name AS ta_name, tb.name, m.begin, m.end AS tb_name FROM matches AS m INNER JOIN teams AS ta ON ta.id = m.team_a_id INNER JOIN teams AS tb ON tb.id = m.team_b_id WHERE m.id=".$i);

$teams = array( $res["ta_id"] => $res["ta_name"], $res["tb_id"] => $res["tb_name"] );
$team_ids = array( $res["ta_id"], $res["tb_id"] );
$begin = $res["begin"];
$end = $res["end"];

var_dump($res);


// Parametri
$deltat = 5;	// ogni quanti secondi mettere un punto nel grafico


$times = array();
$scores = array( $team_ids[0] => array(), $team_ids[1] => array() );

foreach ( $teams as $tid => $tname ) {
	
}

disconnetti($conn);


?>
