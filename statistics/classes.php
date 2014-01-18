<?php


class DateTimeExt extends DateTime {
	
	public function format_ita() {
		$res = "";
		$day = $this->format("N");
		switch( $day ) {
			case 1:
				$res = "luned&igrave;";
				break;
			case 2:
				$res = "marted&igrave;";
				break;
			case 3:
				$res = "mercoled&igrave;";
				break;
			case 4:
				$res = "gioved&igrave;";
				break;
			case 5:
				$res = "venerd&igrave;";
				break;
			case 6:
				$res = "sabato";
				break;
			case 7:
				$res = "domenica";
				break;
		}
		
		$res .= " " . $this->format("d/m/Y") . ", ore " . $this->format("H:i");
		return $res;
	}
	
	public function timestamp() {
		return $this->format("Y-m-d H:i:s");
	}
	
}


class Player {
	
	public $id;
	
	public $fname;
	public $lname;
	public $comment;
	
	
	// Dati secondari
	
	public $pos_goals;
	public $neg_goals;
	public $seconds;
	public $turns;
	
	
	// Dati sulle partecipazioni
	
	public $participations; // array di coppie (Edition, Team) con solo le informazioni essenziali, oppure di terne (Edition, Team, Player) [per le statistiche personali]
	
	public $team;			// usato per le statistiche della singola edizione
	
	// Usati per le statistiche individuali:
	public $friends;		// Compagni di gioco (array di Player)
	public $enemies;		// Avversari (array di Player)
	
	public $play_time;		// Orari di gioco (array: 0 => secondi giocati tra la mezzanotte e l'una, ...)
	
	
	public function __construct ($id, $fname = NULL, $lname = NULL, $comment = NULL, $pos_goals = NULL, $neg_goals = NULL, $seconds = NULL, $turns = NULL, $participations = NULL, $team = NULL) {
		$this->id = $id;
		$this->fname = $fname;
		$this->lname = $lname;
		
		$this->pos_goals = $pos_goals;
		$this->neg_goals = $neg_goals;
		$this->seconds = $seconds;
		$this->turns = $turns;
		
		$this->participations = $participations;
		$this->team = $team;
	}
	
	public function link ( $match_id = 0 ) {
		$res = "<a href=\"player.php?id=" . $this->id;
		if ( $match_id != 0 ) $res .= "&match_id=".$match_id;
		$res .= "\">" . $this->fname . " " . $this->lname . "</a>";
		
		return $res;
	}
	
	// Ora le funzioni che si usano per le statistiche del singolo giocatore
	
	public function load () {
		$conn = connetti();
		
		// Carico le informazioni sul giocatore
		
		$query = "SELECT * FROM players WHERE id = ".$this->id." AND ( fname != '??' OR lname != '??' )";
		$res = query($query);
		
		if ( count($res) != 1 ) {
			disconnetti($conn);
			return FALSE;
		}
		
		$this->fname = $res[0]["fname"];
		$this->lname = $res[0]["lname"];
		$this->comment = $res[0]["comment"];
		
		disconnetti($conn);
		return TRUE;
	}
	
	public function load_participations () {
		$conn = connetti();
		
		// Carico le partecipazioni
		
		$query = "SELECT m.id AS match_id, m.year AS year, t.id AS team_id, t.name AS team_name, spm.pos_goals AS pos_goals, spm.neg_goals AS neg_goals, spm.seconds AS seconds, spm.turns AS turns
			FROM stats_player_matches AS spm INNER JOIN matches AS m ON (spm.match_id = m.id) INNER JOIN teams AS t ON (spm.team_id = t.id)
			WHERE spm.player_id = ".$this->id;
		$res = query($query);
		
		$this->pos_goals = 0;
		$this->neg_goals = 0;
		$this->seconds = 0;
		$this->turns = 0;
		
		$this->participations = array();
		foreach ($res as $row) {
			$this->participations[ $row["match_id"] ] = array( 
				new Edition( $row["match_id"], $row["year"] ),
				new Team( $row["team_id"], $row["team_name"] ),
				new Player( $this->id, $this->fname, $this->lname, $this->comment, $row["pos_goals"], $row["neg_goals"], $row["seconds"], $row["turns"] ),
			);
			
			$this->pos_goals += $row["pos_goals"];
			$this->neg_goals += $row["neg_goals"];
			$this->seconds += $row["seconds"];
			$this->turns += $row["turns"];
			
		}
		
		disconnetti($conn);
	}
	
	public function load_friends_and_enemies ($match_id = NULL) {
		// Carica compagni e avversari (se match_id==NULL, carica tutto)
		
		$conn = connetti();
		
		$mstring = "";
		if ( !is_null($match_id) ) $mstring = "st.match_id = ".$match_id." AND ";
		
		// TODO: aggiungere il LIMIT
		
		$query = "SELECT
			CASE WHEN ( st.p00_id = ".$this->id." ) THEN st.p01_id
			ELSE CASE WHEN ( st.p01_id = ".$this->id." ) THEN st.p00_id
			ELSE CASE WHEN ( st.p10_id = ".$this->id." ) THEN st.p11_id
			ELSE CASE WHEN ( st.p11_id = ".$this->id." ) THEN st.p10_id END END END END AS p,
			p.fname AS fname, p.lname AS lname, p.comment AS comment,
			
			sum( (EXTRACT(EPOCH FROM st.end - st.begin))::Integer ) AS seconds
			
			FROM stats_turns AS st INNER JOIN players AS p ON ( p.id = CASE WHEN ( st.p00_id = ".$this->id." ) THEN st.p01_id
			ELSE CASE WHEN ( st.p01_id = ".$this->id." ) THEN st.p00_id
			ELSE CASE WHEN ( st.p10_id = ".$this->id." ) THEN st.p11_id
			ELSE CASE WHEN ( st.p11_id = ".$this->id." ) THEN st.p10_id END END END END )
			
			WHERE ".$mstring."( ( st.p00_id = ".$this->id." ) OR ( st.p01_id = ".$this->id." ) OR ( st.p10_id = ".$this->id." ) OR ( st.p11_id = ".$this->id." ) )
			GROUP BY p, fname, lname, comment

			ORDER BY seconds DESC";
		
		$res = query($query);
		
		$this->friends = array();
		
		foreach ( $res as $row ) {
			if ( $row["fname"] == "??" && $row["lname"] == "??" ) continue;
			$this->friends[] = new Player( $row["p"], $row["fname"], $row["lname"], $row["comment"], NULL, NULL, $row["seconds"] );
		}
		
		
		$mstring = "";
		if ( !is_null($match_id) ) $mstring = "(sta.match_id = ".$match_id." OR stb.match_id = ".$match_id.") AND ";
		
		$query = "SELECT
			CASE WHEN ( sta.p00_id IS NOT NULL ) THEN
			 CASE WHEN ( sta.p00_id = ".$this->id." OR sta.p01_id = ".$this->id." ) THEN sta.p10_id ELSE
			 CASE WHEN ( sta.p10_id = ".$this->id." OR sta.p11_id = ".$this->id." ) THEN sta.p00_id END END
			ELSE
			 CASE WHEN ( stb.p00_id = ".$this->id." OR stb.p01_id = ".$this->id." ) THEN stb.p11_id ELSE
			 CASE WHEN ( stb.p10_id = ".$this->id." OR stb.p11_id = ".$this->id." ) THEN stb.p01_id END END
			END AS p,
			p.fname AS fname, p.lname AS lname, p.comment AS comment,

			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN ( (EXTRACT(EPOCH FROM sta.end - sta.begin))::Integer ) ELSE ( (EXTRACT(EPOCH FROM stb.end - stb.begin))::Integer ) END ) AS seconds

			FROM stats_turns AS sta FULL OUTER JOIN stats_turns AS stb ON (0=1)
			INNER JOIN players AS p ON ( p.id = CASE WHEN ( sta.p00_id IS NOT NULL ) THEN
			  CASE WHEN ( sta.p00_id = ".$this->id." OR sta.p01_id = ".$this->id." ) THEN sta.p10_id ELSE
			  CASE WHEN ( sta.p10_id = ".$this->id." OR sta.p11_id = ".$this->id." ) THEN sta.p00_id END END
			 ELSE
			  CASE WHEN ( stb.p00_id = ".$this->id." OR stb.p01_id = ".$this->id." ) THEN stb.p11_id ELSE
			  CASE WHEN ( stb.p10_id = ".$this->id." OR stb.p11_id = ".$this->id." ) THEN stb.p01_id END END
			 END )
			
			WHERE ".$mstring."( ( sta.p00_id = ".$this->id." ) OR ( sta.p01_id = ".$this->id." ) OR ( sta.p10_id = ".$this->id." ) OR ( sta.p11_id = ".$this->id." ) OR ( stb.p00_id = ".$this->id." ) OR ( stb.p01_id = ".$this->id." ) OR ( stb.p10_id = ".$this->id." ) OR ( stb.p11_id = ".$this->id." ) )
			GROUP BY p, fname, lname, comment

			ORDER BY seconds DESC";
		
		$res = query($query);
		
		$this->enemies = array();
		
		foreach ( $res as $row ) {
			if ( $row["fname"] == "??" && $row["lname"] == "??" ) continue;
			$this->enemies[] = new Player( $row["p"], $row["fname"], $row["lname"], $row["comment"], NULL, NULL, $row["seconds"] );
		}
		
		disconnetti($conn);
	}
	
	public function load_play_time ($match_id = NULL) {
		$conn = connetti();
		
		// E' una porcheria...
		
		$query = "SELECT ";
		for ($i=0; $i<24; $i++) {
			$query .= "SUM(
				CASE WHEN ( EXTRACT(HOUR FROM st.begin) = ".$i." AND EXTRACT(HOUR FROM st.end) = ".$i." ) THEN ( EXTRACT(MINUTE FROM st.end) - EXTRACT(MINUTE FROM st.begin) ) ELSE
				CASE WHEN ( EXTRACT(HOUR FROM st.begin) != ".$i." AND EXTRACT(HOUR FROM st.end) = ".$i." ) THEN ( (EXTRACT (MINUTE FROM st.end)) ) ELSE
				CASE WHEN ( EXTRACT(HOUR FROM st.begin) = ".$i." AND EXTRACT(HOUR FROM st.end) != ".$i." ) THEN ( 60 - EXTRACT (MINUTE FROM st.begin) ) ELSE 0 END
			END END) AS s".$i;
			
			if ( $i < 23 ) $query .= ", "; 
		}
		$query .= " FROM stats_turns AS st WHERE (p00_id = ".$this->id." OR p01_id = ".$this->id." OR p10_id = ".$this->id." OR p11_id = ".$this->id." )";
		if ( !is_null($match_id) ) $query .= " AND match_id = ".$match_id;
		
		$res = query($query);
		
		$this->play_time = array();
		for ($i=0; $i<24; $i++) {
			$this->play_time[$i] = (int)($res[0]["s".$i]);
		}
		
		disconnetti($conn);
	}
}


class Couple {
	
	public $players; // Array di 2 players
	
	// Dati secondari
	
	public $pos_goals;
	public $neg_goals;
	public $seconds;
	
	// Dati sulle partecipazioni
	
	public $participations; // array di coppie (Edition, Team) con solo le informazioni essenziali
	public $team;			// usato per le statistiche della singola edizione
	
	public function __construct ( $player_a, $player_b, $pos_goals = NULL, $neg_goals = NULL, $seconds = NULL, $participations = NULL, $team = NULL ) {
		$this->players = array( $player_a, $player_b );
		$this->pos_goals = $pos_goals;
		$this->neg_goals = $neg_goals;
		$this->seconds = $seconds;
		$this->participations = $participations;
		$this->team = $team;
	}
	
	public function link () {
		return $this->players[0]->link().", ".$this->players[1]->link();
	}
	
}


class Team {
	
	public $id;
	
	public $name;
	
	// Dati secondari
	
	public $participants;	// Array di players
	public $score;
	public $num_participants;
	public $captains;	// Array ( match_id => (capitano, vicecapitano) )
	
	public $graph_data;	// Dati per i grafici
	
	public function __construct ($id, $name = NULL) {
		$this->id = $id;
		$this->name = $name;
		
		$this->participants = array();
		$this->score = 0;
		$this->num_participants = NULL;
	}
}



class Edition {
	
	public $id;
	public $year;
	public $place;
	
	public $teams;			// Array di teams
	public $captains;		// Array di array di players
	public $score;			// Array di integers
	public $first_players;	// Array di array di players
	public $couples;		// Array di coppie
	
	public $begin;
	public $end;
	
	public $graph_data;		// Dati per il grafico del punteggio
	
	public function __construct ($id, $year = NULL, $place = NULL) {
		$this->id = $id;
		
		$this->year = $year;
		$this->place = $place;
	}
	
	public function link () {
		return "<a href=\"statistics.php?match_id=" . $this->id . "\">" . $this->year . "</a>";
	}
	
	public function load() {
		$conn = connetti();
		
		// Query per i dati principali della partita
		$query = "SELECT m.year, m.place, t1.id AS t1_id, t1.name AS t1_name, t2.id AS t2_id, t2.name AS t2_name, p1.id AS p1_id, p1.fname AS p1_fname, p1.lname AS p1_lname, p2.id AS p2_id, p2.fname AS p2_fname, p2.lname AS p2_lname, p3.id AS p3_id, p3.fname AS p3_fname, p3.lname AS p3_lname, p4.id AS p4_id, p4.fname AS p4_fname, p4.lname AS p4_lname, m.begin, m.end FROM matches AS m INNER JOIN players AS p1 ON m.team_a_captain_id = p1.id INNER JOIN players AS p2 ON m.team_a_deputy_id = p2.id INNER JOIN players AS p3 ON m.team_b_captain_id = p3.id INNER JOIN players AS p4 ON m.team_b_deputy_id = p4.id INNER JOIN teams AS t1 ON m.team_a_id = t1.id INNER JOIN teams AS t2 ON m.team_b_id = t2.id WHERE m.id = ".$this->id;
				
		$res = query($query);
		
		if ( count($res) == 0 ) {
			disconnetti($conn);
			return FALSE;
		}
		
		$res = $res[0];
		
		$this->year = $res["year"];
		$this->place = $res["place"];
		
		$this->teams = array( new Team( $res["t1_id"], $res["t1_name"] ), new Team( $res["t2_id"], $res["t2_name"] ) );
		$this->captains = array( array( new Player( $res["p1_id"], $res["p1_fname"], $res["p1_lname"] ), new Player( $res["p2_id"], $res["p2_fname"], $res["p2_lname"] ) ), array( new Player( $res["p3_id"], $res["p3_fname"], $res["p3_lname"] ), new Player( $res["p4_id"], $res["p4_fname"], $res["p4_lname"] ) ) );
		
		$this->begin = new DateTimeExt( $res["begin"] );
		$this->end = new DateTimeExt( $res["end"] );
		
		disconnetti($conn);
		return TRUE;
	}
	
	public function load_more_information() {
		$conn = connetti();
		
		// Query per il risultato della partita
		$query = "SELECT sum(score_a) AS score_a, sum(score_b) AS score_b FROM stats_turns WHERE match_id = ".$this->id;
		$res = query($query);
		
		if ( count($res) == 0 ) {
			disconnetti($conn);
			return FALSE;
		}
		
		$res = $res[0];
		
		$this->score[0] = $res["score_a"];
		$this->score[1] = $res["score_b"];
		
		
		// Query per i titolari
		$query = "SELECT p1.id AS p1_id, p1.fname AS p1_fname, p1.lname AS p1_lname, p2.id AS p2_id, p2.fname AS p2_fname, p2.lname AS p2_lname, p3.id AS p3_id, p3.fname AS p3_fname, p3.lname AS p3_lname, p4.id AS p4_id, p4.fname AS p4_fname, p4.lname AS p4_lname FROM stats_turns AS s INNER JOIN players AS p1 ON s.p00_id = p1.id INNER JOIN players AS p2 ON s.p01_id = p2.id INNER JOIN players AS p3 ON s.p10_id = p3.id INNER JOIN players AS p4 ON s.p11_id = p4.id WHERE s.match_id = ". $this->id ." ORDER BY s.begin LIMIT 1";
		$res = query($query);
		
		if ( count($res) == 0 ) {
			disconnetti($conn);
			return FALSE;
		}
		
		$res = $res[0];
		
		$this->first_players = array( array( new Player( $res["p1_id"], $res["p1_fname"], $res["p1_lname"] ), new Player( $res["p2_id"], $res["p2_fname"], $res["p2_lname"] ) ), array( new Player( $res["p3_id"], $res["p3_fname"], $res["p3_lname"] ), new Player( $res["p4_id"], $res["p4_fname"], $res["p4_lname"] ) ) );
		
		disconnetti($conn);
		return TRUE;
	}
	
	
	public function load_participants() {
		// Carica la lista dei partecipanti
		
		$conn = connetti();
		$query = "SELECT * FROM stats_player_matches AS pm INNER JOIN players AS p ON pm.player_id = p.id WHERE match_id = ". $this->id ." AND (p.fname != '??' OR p.lname != '??') ORDER BY p.fname, p.lname, p.comment";
		// echo $query."<br />";
		
		$res = query($query);
		disconnetti($conn);
		
		// Assumiamo che $this->teams sia già stato caricato
		$inv_teams = array( $this->teams[0]->id => 0, $this->teams[1]->id => 1 );
		
		foreach ( $res as $row ) {
			$small_team = new Team ( $row["team_id"], $this->teams[ $inv_teams[ $row["team_id"] ] ]->name );
			
			$this->teams[ $inv_teams[ $row["team_id"] ] ]->participants[ $row["player_id"] ] = new Player ( $row["player_id"], $row["fname"], $row["lname"], $row["comment"], $row["pos_goals"], $row["neg_goals"], $row["seconds"], $row["turns"], NULL, $small_team );
		}
		
	}
	
	public function load_couples($limit = 60) {
		// Carica la lista delle coppie
		// Assume che sia già stata chiamata load_participants()
		
		$conn = connetti();
		
		// TOFIX: attualmente, per eliminare le coppie contenenti un giocatore sconosciuto, lo facciamo a mano invece che nella query
		
		$query = "SELECT
			CASE WHEN ( sta.p00_id IS NOT NULL ) THEN
			 CASE WHEN ( sta.p00_id < sta.p01_id ) THEN sta.p00_id ELSE sta.p01_id END
			ELSE
			 CASE WHEN ( stb.p10_id < stb.p11_id ) THEN stb.p10_id ELSE stb.p11_id END
			END AS p0,
			CASE WHEN ( sta.p00_id IS NOT NULL ) THEN
			 CASE WHEN ( sta.p00_id < sta.p01_id ) THEN sta.p01_id ELSE sta.p00_id END
			ELSE
			 CASE WHEN ( stb.p10_id < stb.p11_id ) THEN stb.p11_id ELSE stb.p10_id END
			END AS p1,

			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN sta.score_a ELSE stb.score_b END ) AS pos_goals,
			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN sta.score_b ELSE stb.score_a END ) AS neg_goals,
			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN ( sta.score_a - sta.score_b ) ELSE ( stb.score_b - stb.score_a ) END ) AS diff,
			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN ( (EXTRACT(EPOCH FROM sta.end - sta.begin))::Integer ) ELSE ( (EXTRACT(EPOCH FROM stb.end - stb.begin))::Integer ) END ) AS seconds


			FROM stats_turns AS sta FULL OUTER JOIN stats_turns AS stb ON (0=1)
			
			WHERE sta.match_id = ". $this->id ." OR stb.match_id = ". $this->id ."

			GROUP BY p0, p1

			ORDER BY seconds DESC
			LIMIT ".($limit+5);
		
		$res = query($query);
		
		$couples = array();
		
		$cont = 0;
		foreach ($res as $row) {
			
			if ( $cont >= $limit ) break;
			
			$team_key = 0;
			if ( array_key_exists($row["p0"], $this->teams[0]->participants ) && array_key_exists($row["p1"], $this->teams[0]->participants ) ) $team_key = 0;
			else if ( array_key_exists($row["p0"], $this->teams[1]->participants ) && array_key_exists($row["p1"], $this->teams[1]->participants ) ) $team_key = 1;
			else continue;
			
			$small_team = new Team ( $this->teams[ $team_key ]->id, $this->teams[ $team_key ]->name );
			
			$p0 = $this->teams[ $team_key ]->participants[ $row["p0"] ];
			$p1 = $this->teams[ $team_key ]->participants[ $row["p1"] ];
			
			$couples[] = new Couple ( $p0, $p1, $row["pos_goals"], $row["neg_goals"], $row["seconds"], NULL, $small_team );
			
			$cont++;
		}
		
		$this->couples = $couples;
		
		disconnetti($conn);
	}
	
	public function load_graph_data() {
		$conn = connetti();
		
		// Assume che $this->begin e $this->end siano stati caricati
		$query = "SELECT ( EXTRACT( EPOCH FROM date_trunc( 'minute', (e.timestamp - timestamp '".$this->begin->timestamp()."') ) ) / 60 ) AS time,
					e.team_id AS team_id, sum( CASE WHEN ( e.type = 'goal' ) THEN 1 ELSE -1 END ) AS goals
					FROM events AS e WHERE e.match_id = ".$this->id." AND ( e.type = 'goal' OR e.type = 'goal_undo' )
					GROUP BY time, team_id
					ORDER BY time";
		
		$res = query($query);
		
		$duration = strtotime($this->end->timestamp()) - strtotime($this->begin->timestamp());
		
		// Approssimo per eccesso al minuto
		$duration = (int)( ($duration+59)/60 );
		
		$data = array( array(), array() );	// Indicizzato da 0 fino a $duration minuti
		
		for ( $i = 0; $i <= $duration; $i++ ) {
			// Inizializzo a zero i gol in tutti i minuti di gioco
			$data[0][$i] = 0;
			$data[1][$i] = 0;
		}
		
		$tid2index = array();
		foreach ( $this->teams as $index => $team ) {
			$tid2index[ $team->id ] = $index;
		}
		
		foreach ( $res as $row ) {
			$tid = $row["team_id"];
			$index = $tid2index[ $tid ];
			$minute = (int)($row["time"]) + 1;
			$numgoals = (int)($row["goals"]);
			
			$data[ $index ][ $minute ] = $numgoals;
		}
		
		// Ora sostituisco i delta con le somme parziali
		for ($i=0; $i<=1; $i++) {
			for ($j=1; $j<=$duration; $j++) {
				$data[ $i ][ $j ] += $data[ $i ][ $j-1 ];
			}
		}
		
		$this->graph_data = $data;
		
		disconnetti($conn);
	}
}


class GeneralStatistics {
	
	public $participants;
	public $couples;
	public $teams;
	public $editions;
	
	public $graph_data;		// Dati per i grafici
	
	
	public function __construct () {
	}
	
	public function load_participants() {
		// Carica la lista dei partecipanti
		
		$conn = connetti();
		
		$query = "SELECT * FROM stats_player_matches AS pm INNER JOIN players AS p ON pm.player_id = p.id INNER JOIN matches AS m ON pm.match_id = m.id INNER JOIN teams AS t ON pm.team_id = t.id WHERE (p.fname != '??' OR p.lname != '??') ORDER BY p.fname, p.lname, p.comment, year";
		
		$res = query($query);
		
		disconnetti($conn);
		
		$part = array();
		
		foreach ($res as $row) {
			$id = $row["player_id"];
			if (!array_key_exists($id, $part)) {
				$part[$id] = new Player ($id, $row["fname"], $row["lname"], $row["comment"], 0, 0, 0, 0, array());
			}
			
			$part[$id]->pos_goals += $row["pos_goals"];
			$part[$id]->neg_goals += $row["neg_goals"];
			$part[$id]->seconds += $row["seconds"];
			$part[$id]->turns += 1;
			
			$part[$id]->participations[] = array( new Edition ($row["match_id"], $row["year"], $row["place"]), new Team ($row["team_id"], $row["name"]) );
			
		}
		
		$this->participants = $part;
	}
	
	public function load_couples($limit = 60) {
		// Carica la lista delle coppie
		// Assume che sia già stata chiamata load_participants()
		
		$conn = connetti();
		
		// TOFIX: attualmente, per eliminare le coppie contenenti un giocatore sconosciuto, lo facciamo a mano invece che nella query
		
		$query = "SELECT
			CASE WHEN ( sta.p00_id IS NOT NULL ) THEN
			 CASE WHEN ( sta.p00_id < sta.p01_id ) THEN sta.p00_id ELSE sta.p01_id END
			ELSE
			 CASE WHEN ( stb.p10_id < stb.p11_id ) THEN stb.p10_id ELSE stb.p11_id END
			END AS p0,
			CASE WHEN ( sta.p00_id IS NOT NULL ) THEN
			 CASE WHEN ( sta.p00_id < sta.p01_id ) THEN sta.p01_id ELSE sta.p00_id END
			ELSE
			 CASE WHEN ( stb.p10_id < stb.p11_id ) THEN stb.p11_id ELSE stb.p10_id END
			END AS p1,

			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN sta.score_a ELSE stb.score_b END ) AS pos_goals,
			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN sta.score_b ELSE stb.score_a END ) AS neg_goals,
			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN ( sta.score_a - sta.score_b ) ELSE ( stb.score_b - stb.score_a ) END ) AS diff,
			sum( CASE WHEN ( sta.p00_id IS NOT NULL ) THEN ( (EXTRACT(EPOCH FROM sta.end - sta.begin))::Integer ) ELSE ( (EXTRACT(EPOCH FROM stb.end - stb.begin))::Integer ) END ) AS seconds


			FROM stats_turns AS sta FULL OUTER JOIN stats_turns AS stb ON (0=1)

			GROUP BY p0, p1

			ORDER BY seconds DESC
			LIMIT ".($limit+5);
		
		$res = query($query);
		
		$couples = array();
		
		$cont = 0;
		foreach ($res as $row) {
		
			if ( $cont >= $limit ) break;
			
			if ( !array_key_exists( $row["p0"], $this->participants) || !array_key_exists( $row["p1"], $this->participants) ) continue;
			
			$couples[] = new Couple ( $this->participants[ $row["p0"] ], $this->participants[ $row["p1"] ], $row["pos_goals"], $row["neg_goals"], $row["seconds"] );
			
			$cont++;
			
		}
		
		$this->couples = $couples;
		
		disconnetti($conn);
	}
	
	
	public function load_teams() {
		// Carico le informazioni generali sulle squadre e sulle edizioni
		
		$conn = connetti();
		
		$teams = array();
		
		// Query per i gol totali
		$query = "SELECT t.id, t.name, sum(score_a) FROM teams as t
			INNER JOIN matches AS m ON m.team_a_id = t.id
			INNER JOIN stats_turns AS sta ON sta.match_id = m.id
			GROUP BY t.id, t.name
			UNION ALL
			SELECT t.id, t.name, sum(score_b) FROM teams as t
			INNER JOIN matches AS m ON m.team_b_id = t.id
			INNER JOIN stats_turns AS stb ON stb.match_id = m.id
			GROUP BY t.id, t.name";
		
		$res = query($query);
		
		foreach ($res as $row) {
			if (!array_key_exists($row["id"],$teams)) {
				$teams[$row["id"]] = new Team ( $row["id"], $row["name"] );
			}
			$teams[$row["id"]]->score += $row["sum"];
		}
		
		// Query per i partecipanti
		$query = "SELECT team_id, count(distinct player_id) FROM stats_player_matches AS spm
			INNER JOIN players AS p ON p.id = spm.player_id
			WHERE ( p.fname != '??' OR p.lname != '??' )
			GROUP BY team_id";
		
		$res = query($query);
		
		foreach ($res as $row) {
			$teams[$row["team_id"]]->num_participants = $row["count"];
		}
		
		// Query per i capitani e vicecapitani
		$query = "SELECT m.year, m.id as match_id, m.place,
			pca.id as pca_id, pca.fname as pca_fname, pca.lname as pca_lname, pca.comment as pca_comment,
			pda.id as pda_id, pda.fname as pda_fname, pda.lname as pda_lname, pda.comment as pda_comment,
			pcb.id as pcb_id, pcb.fname as pcb_fname, pcb.lname as pcb_lname, pcb.comment as pcb_comment,
			pdb.id as pdb_id, pdb.fname as pdb_fname, pdb.lname as pdb_lname, pdb.comment as pdb_comment,
			m.team_a_id, m.team_b_id
			FROM matches AS m
			INNER JOIN players AS pca ON m.team_a_captain_id = pca.id
			INNER JOIN players AS pda ON m.team_a_deputy_id = pda.id
			INNER JOIN players AS pcb ON m.team_b_captain_id = pcb.id
			INNER JOIN players AS pdb ON m.team_b_deputy_id = pdb.id
			ORDER BY m.year";
		
		$res = query($query);
		
		foreach ($res as $row) {
			$teams[ $row["team_a_id"] ]->captains[ $row["match_id"] ] = array( new Player ( $row["pca_id"], $row["pca_fname"], $row["pca_lname"], $row["pca_comment"] ), new Player ( $row["pda_id"], $row["pda_fname"], $row["pda_lname"], $row["pda_comment"] ) );
			
			$teams[ $row["team_b_id"] ]->captains[ $row["match_id"] ] = array( new Player ( $row["pcb_id"], $row["pcb_fname"], $row["pcb_lname"], $row["pcb_comment"] ), new Player ( $row["pdb_id"], $row["pdb_fname"], $row["pdb_lname"], $row["pdb_comment"] ) );
		}
		
		disconnetti($conn);
		
		$this->teams = $teams;
		
		$editions = array();
		foreach ($res as $row) {
			$editions[ $row["match_id"] ] = new Edition ( $row["match_id"], $row["year"], $row["place"] );
		}
		
		$this->editions = $editions;
	}
	
	
	public function load_graph_data($type) {
		// Funzione per caricare gol e numero dei partecipanti nelle varie edizioni
		
		$conn = connetti();
		
		$query = "";
		
		if ( $type == "goals" ) {
		// Query per il numero di gol
			$query = "SELECT m.id, m.year, m.team_a_id, m.team_b_id, ta.name AS ta_name, tb.name AS tb_name,
				sum( CASE WHEN ( e.type = 'goal' AND e.team_id = m.team_a_id ) THEN 1 ELSE (
					CASE WHEN ( e.type = 'goal_undo' AND e.team_id = m.team_a_id ) THEN -1 ELSE 0 END ) END ) AS val_a,
				sum( CASE WHEN ( e.type = 'goal' AND e.team_id = m.team_b_id ) THEN 1 ELSE (
					CASE WHEN ( e.type = 'goal_undo' AND e.team_id = m.team_b_id ) THEN -1 ELSE 0 END ) END ) AS val_b
				FROM matches AS m
				INNER JOIN events AS e ON e.match_id = m.id
				INNER JOIN teams AS ta ON ta.id = m.team_a_id
				INNER JOIN teams AS tb ON tb.id = m.team_b_id
				WHERE ( e.type = 'goal' OR e.type = 'goal_undo' )
				GROUP BY m.id, m.year, m.team_a_id, m.team_b_id, ta.name, tb.name
				ORDER BY m.year, m.id";
		}
		else {
			// Query per il numero di partecipanti
			$query = "SELECT m.id, m.year, m.team_a_id, m.team_b_id, ta.name AS ta_name, tb.name AS tb_name,
				sum( CASE WHEN ( spm.team_id = m.team_a_id ) THEN 1 ELSE 0 END ) AS val_a,
				sum( CASE WHEN ( spm.team_id = m.team_b_id ) THEN 1 ELSE 0 END ) AS val_b
				FROM matches AS m
				INNER JOIN stats_player_matches AS spm ON spm.match_id = m.id
				INNER JOIN players AS p ON (p.id = spm.player_id)
				INNER JOIN teams AS ta ON ta.id = m.team_a_id
				INNER JOIN teams AS tb ON tb.id = m.team_b_id
				WHERE ( p.fname != '??' OR p.lname != '??' )
				GROUP BY m.id, m.year, m.team_a_id, m.team_b_id, ta.name, tb.name
				ORDER BY m.id";
		}
		
		$res = query($query);
		
		$this->graph_data = array();	// Array con le squadre
		foreach ( $res as $row ) {
			for ( $i='a'; $i<='b'; $i++ ) {
				$tid = $row[ "team_".$i."_id" ];
				$tname = $row[ "t".$i."_name" ];
				$mid = $row[ "id" ];
				$year = $row[ "year" ];
				$val = $row[ "val_".$i ];
				
				if ( !array_key_exists ( $tid, $this->graph_data ) ) {
					$this->graph_data[ $tid ] = new Team ( $tid, $tname );
					$this->graph_data[ $tid ]->graph_data = array();
				}
				//$this->graph_data[ $tid ]->graph_data[ $year ] = $val;
			}
		}

		// Aggiungo manualmente tutte le edizioni (anche quelle in cui una squadra non ha partecipato)
		// WARNING: quello che segue non è lineare nei dati (NSQUADRE * NPARTITE)
		foreach ( $res as $row ) {
			$year = $row["year"];
			
			foreach ( $this->graph_data as $tid => $team ) {
				if ( !array_key_exists( $year, $team->graph_data ) ) $this->graph_data[ $tid ]->graph_data[ $year ] = 0;
			}
		}
		
		foreach ( $res as $row ) {
			for ( $i='a'; $i<='b'; $i++ ) {
				$tid = $row[ "team_".$i."_id" ];
				$tname = $row[ "t".$i."_name" ];
				$mid = $row[ "id" ];
				$year = $row[ "year" ];
				$val = $row[ "val_".$i ];
				
				$this->graph_data[ $tid ]->graph_data[ $year ] = $val;
			}
		}
		
		disconnetti($conn);
		
	}
}





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

?>
