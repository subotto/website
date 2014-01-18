<?php

include_once "../db_connection/config.php";
include_once "../inclusions.php";

class Participation {
	public $team;
	public $pos_goals;
	public $neg_goals;
	public $seconds;
	public $turns;
	
	public function __construct ($team) {
		$this->team = $team;
		$this->pos_goals = 0;
		$this->neg_goals = 0;
		$this->seconds = 0;
		$this->turns = 0;
	}
	
	public function add_turn ($begin, $end, $pos, $neg) {
		$this->pos_goals += $pos;
		$this->neg_goals += $neg;
		
		$delta = strtotime($end) - strtotime($begin);
		$this->seconds += $delta;
		
		$this->turns++;
	}
}

class Player {
	public $id;
	public $fname;
	public $lname;
	public $comment;
	
	public $participations;
	
	public $turns;
	public $cont; // contatore delle partecipazioni
	public $elo;
	
	public function __construct ($id, $fname, $lname, $comment) {
		$this->id = $id;
		$this->fname = $fname;
		$this->lname = $lname;
		$this->comment = $comment;
		
		$this->participations = array();
		
		$this->turns = 0;
		$this->elo = 1500;
	}
	
	public function name() {
		return $this->fname . " " . $this->lname;
	}
	
	public function elo_K() {
		// La costante per modificare l'ELO
		return 1;
		
	}
}


function lexicographic_cmp ($a, $b) {
	// $a e $b$ sono Players
	return strcmp($a->name(), $b->name());
}

function elo_cmp ($a, $b) {
	// $a e $b$ sono Players
	
	return ( $a->elo < $b->elo );
}


class Turn {
	public $match_id;
	public $begin;
	public $end;
	
	public $players_id;
	public $score;
	
	public function __construct ($match_id, $begin, $end, $players_id, $score) {
		$this->match_id = $match_id;
		$this->begin = $begin;
		$this->end = $end;
		$this->players_id = $players_id;
		$this->score = $score;
	}
}


$turns_list = array(); // Conterrà la lista di tutti i turni



// Carico la lista dei giocatori

$conn = connetti();
$res = query("SELECT * FROM players");


$players = array();
foreach ( $res as $row ) {
	$players[ (int)$row["id"] ] = new Player ( (int)$row["id"], $row["fname"], $row["lname"], $row["comment"] );
}




// Carico i dati sui turni

$input = array();

// L'indice è il match_id (sotto è chiamato anche "edition")
$input[1] = file( "turns2010.txt" );
$input[2] = file( "turns2011.txt" );
$input[3] = file( "turns2012.txt" );
$input[4] = file( "turns2013.txt" );




// Elaboro i dati

foreach ( $input as $edition => $turns ) {

	$final_score = array(0,0);
	
	foreach ( $turns as $turn ) {
		$string = trim($turn);
		$pieces = explode("," , $string);
		
		$begin = $pieces[0];
		$end = $pieces[1];
		
		$score = array ( (int)$pieces[6], (int)$pieces[7] );
		
		// Aggiungo questo turno all'elenco grande dei turni (per l'inserimento nel DB)
		
		$turns_list[] = new Turn ( $edition, $begin, $end, array( (int)$pieces[2], (int)$pieces[3], (int)$pieces[4], (int)$pieces[5] ), $score );
		
		
		// Continuo l'elaborazione dei dati per giocatore
		
		$final_score[0] += $score[0];
		$final_score[1] += $score[1];
		
		foreach ( array( (int)$pieces[2], (int)$pieces[3] ) as $player_id ) {
			if ( !array_key_exists($edition, $players[ $player_id ]->participations) ) {
				$players[ $player_id ]->participations[$edition] = new Participation(1);
				
				$players[ $player_id ]->cont++; // Aumento di 1 il numero di partecipazioni
			}
		}
		foreach ( array( (int)$pieces[4], (int)$pieces[5] ) as $player_id ) {
			if ( !array_key_exists($edition, $players[ $player_id ]->participations) ) {
				$players[ $player_id ]->participations[$edition] = new Participation(2);
				
				$players[ $player_id ]->cont++; // Aumento di 1 il numero di partecipazioni
			}
		}
		
		$players[ (int)$pieces[2] ]->participations[$edition]->add_turn($begin,$end,$pieces[6],$pieces[7]);
		$players[ (int)$pieces[3] ]->participations[$edition]->add_turn($begin,$end,$pieces[6],$pieces[7]);
		$players[ (int)$pieces[4] ]->participations[$edition]->add_turn($begin,$end,$pieces[7],$pieces[6]);
		$players[ (int)$pieces[5] ]->participations[$edition]->add_turn($begin,$end,$pieces[7],$pieces[6]);
		
		// Aggiorno l'ELO
		for ( $i = 0; $i < ($score[0]+$score[1])/10; $i++ ) {
			$elo = array( ( $players[ (int)$pieces[2] ]->elo + $players[ (int)$pieces[3] ]->elo )/2, ( $players[ (int)$pieces[4] ]->elo + $players[ (int)$pieces[5] ]->elo )/2 );
			$expected = 1 / ( 1 + exp( ($elo[1]-$elo[0])/100 ) );
			
			$delta = ( ( $score[0] ) / ( $score[0] + $score[1] ) - $expected );
			
			foreach ( array( (int)$pieces[2], (int)$pieces[3] )  as $id ) {
				$players[ $id ]->elo += ( $delta ) * ( $players[ $id ]->elo_K() );
			}
			foreach ( array( (int)$pieces[4], (int)$pieces[5] )  as $id ) {
				$players[ $id ]->elo += ( -$delta ) * ( $players[ $id ]->elo_K() );
			}
			
		}
		
		foreach ( array( (int)$pieces[2], (int)$pieces[3], (int)$pieces[4], (int)$pieces[5] ) as $id ) {
			$players[$id]->turns++;
		}
	}
	
	echo "Double check: il punteggio finale dell'edizione $edition &egrave; ".$final_score[0]."-".$final_score[1].".<br />\n";
}


// Inizio della parte di scrittura delle statistiche
//C'è già altrove (almeno sul sito uz.sns.it/24ore)
function format_time ($seconds) {
	$minutes = (int)round($seconds/60);
	$hours = (int)($minutes/60);
	$minutes = $minutes % 60;
	
	$res = "";
	
	if ( $hours == 1 ) $res .= $hours . " ora";
	if ( $hours > 1 ) $res .= $hours . " ore";
	
	if ( $hours > 0 && $minutes > 0 ) $res .= " e ";
	
	if ( $minutes == 1 ) $res .= $minutes . " minuto";
	if ( $minutes > 1 ) $res .= $minutes . " minuti";
	
	return $res;
}


function edition_statistics ($edition) {
	$participants = array();
	global $players;
	foreach ($players as $player) {
		if ( $player->fname == "??" && $player->lname == "??" ) continue;
		if ( array_key_exists($edition, $player->participations) ) {
			$participants[] = $player;
		}
	}
	
	uasort($participants, 'lexicographic_cmp');
	
	echo "<table><tr><td>\n";
	$head = array("Partecipanti", "Tempo di gioco", "Gol fatti", "Gol subiti", "Differenza reti");
	
	$math_table = array();
	$phys_table = array();
	foreach ( $participants as $player ) {
		$part = $player->participations[$edition];
		if ( $part->team == 1 ) {
			$math_table[] = array( $player->fname . " " . $player->lname, format_time( $part->seconds ), $part->pos_goals, $part->neg_goals, $part->pos_goals - $part->neg_goals );
		}
		else {
			$phys_table[] = array( $player->fname . " " . $player->lname, format_time( $part->seconds ), $part->pos_goals, $part->neg_goals, $part->pos_goals - $part->neg_goals );
		}
	}
	
	paragraph(bold("Matematici")." (".count($math_table)." partecipanti)");
	make_table( $math_table, "tabella", $head );
	
	echo "</td><td>";
	paragraph(bold("Fisici")." (".count($phys_table)." partecipanti)");
	
	make_table( $phys_table, "tabella", $head );
	
	echo "</td></tr></table>";
}


function general_statistics() {
	global $players;
	$participants = array();
	foreach ($players as $player) {
		if ( $player->fname == "??" && $player->lname == "??" ) continue;
		if ( count($player->participations) > 0 ) {
			$participants[] = $player;
		}
	}
	
	uasort($participants, 'lexicographic_cmp');
	
	$table = array();
	foreach ($participants as $player) {
		$part = $player->participations;
		
		$total_seconds = 0;
		$total_pos = 0;
		$total_neg = 0;
		
		$part_description = "";
		$list = array();
		
		foreach ( $part as $edition => $p ) {
			$total_seconds += $p->seconds;
			$total_pos += $p->pos_goals;
			$total_neg += $p->neg_goals;
			
			$list[] = $edition;
		}
		
		$table[] = array( $player->fname . " " . $player->lname, implode(", ", $list), format_time( $total_seconds ), $total_pos, $total_neg, $total_pos - $total_neg, round($player->elo), $player->turns );
	}
	
	$head = array ( "Giocatori", "Partecipazioni", "Tempo di gioco", "Gol fatti", "Gol subiti", "Differenza reti", "ELO", "Turni");
	make_table( $table, "tabella", $head );
}

//general_statistics();
//edition_statistics(4);



// Inizio della parte di riscrittura sul Database
// Normalmente sono commentati i comandi che fanno davvero la riscrittura

function fill_turns_table() {
	// Tabella dei turni
	
	global $turns_list;
	
	//var_dump($turns_list);
	
	foreach ( $turns_list as $turn ) {
		
		$query = "INSERT INTO stats_turns VALUES (DEFAULT, " . $turn->match_id;
		foreach ( $turn->players_id as $player_id ) {
			$query .= ", ".$player_id;
		}
		
		$query .= ", " . ($turn->score[0]) . ", " . ($turn->score[1]);
		$query .= ", '" . $turn->begin . "', '" . $turn->end . "')";
		
		echo $query . "<br />";
		//query($query);
		
	}
}

//fill_turns_table();

function fill_playermatches_table() {
	// Tabella dei player-matches
	
	global $players;
	
	//var_dump($players);
	
	foreach ($players as $player_id => $player) {
		foreach ($player->participations as $match_id => $participation) {
			$query = "INSERT INTO stats_player_matches VALUES (DEFAULT, " . $player_id . ", " . $match_id . ", " . $participation->team . ", " .$participation->pos_goals . ", " . $participation->neg_goals . ", " . $participation->seconds . ", " . $participation->turns . ")";
			
			echo $query . "<br />";
			//query($query);
		}
	}
}

//fill_playermatches_table();



disconnetti($conn);

?>
