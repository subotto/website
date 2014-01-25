<?php

include_once "inclusions.php";

start_html("24 ore", FALSE, TRUE);

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

?>

<div id="time_box" >05:17:58</div>

<div id="graphic_interface">

	<div id="blue_team">Fisici</div>
	
	<div>
		<span id="blue_attacker" class="player">
		    Luca Rigovacca

		</span>
		
	    <span id="blue_attacker_stats" class="stats">
		    Partecipazioni: 4<br />Tempo di gioco: 8 ore e 41 min<br />(3 ore e 10 min in questa partita)<br />Gol fatti: 530<br />(138 in questa partita)
	    </span>

		<span id="blue_defender" class="player">
		    Giulio Mandorli
		</span>

	    <span id="blue_defender_stats" class="stats">
		    Partecipazioni: 3<br />Tempo di gioco: 8 ore e 3 min<br />(2 ore e 48 min in questa partita)<br />Gol fatti: 585<br />(195 in questa partita)
	    </span>
	</div>
	
	<div>
		<span id="red_score">1863</span>
		<div id="field_svg"><?php include "svg_subotto/field.svg"; ?></div>
		<span id="blue_score">374</span>
	</div>
	
	<div>
	    <span id="red_attacker" class="player">
	        Fabrizio Bianchi
		</span>
		
	    <span id="red_attacker_stats" class="stats">
		    Partecipazioni: 4<br />Tempo di gioco: 14 ore e 27 min<br />(3 ore e 14 min in questa partita)<br />Gol fatti: 982<br />(207 in questa partita)
	    </span>
		
		<span id="red_defender" class="player">
		    Giovanni Paolini
		</span>

	    <span id="red_defender_stats" class="stats">
		    Partecipazioni: 3<br />Tempo di gioco: 9 ore e 12 min<br />(3 ore e 9 min in questa partita)<br />Gol fatti: 689<br />(205 in questa partita)
	    </span>
	</div>
	
	<div id="red_team">Matematici</div>

</div>

<div id="classic_interface">
	...
</div>

<div id="statistics"></div>

<div id="graph"></div>


<div id="debug_div"></div>
<div id="frames_div"></div>
<div id="time"></div>

<?php
/*
$status = trim(file_get_contents("stats/fake.html"));

if ( $status != "before" ) {

}
*/
?>

<script>
init_field();
</script>

<script type="text/javascript">

var n=0;
function dataRefresh() {
	$("#time_box").load("stats/time.html");
	
	$("#red_team").load("stats/red_team.html");
	$("#blue_team").load("stats/blue_team.html");
	
	$("#red_score").load("stats/red_score.html");
	$("#blue_score").load("stats/blue_score.html");
	
	$("#red_attacker").load("stats/red_attacker.html");
	$("#red_defender").load("stats/red_defender.html");
	$("#blue_attacker").load("stats/blue_attacker.html");
	$("#blue_defender").load("stats/blue_defender.html");
	
	
	n = n+1;
}
setInterval( dataRefresh, 500 );

</script>

<?php

end_box();
end_html();

?>
