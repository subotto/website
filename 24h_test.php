<?php

include_once "inclusions.php";

start_html("24 ore", FALSE, TRUE);

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

?>

<div id="time_box" ></div>

<div class="toggle" >
    <a onclick="toggle_interface(this)" class="active">Visualizzazione classica </a>
    <a onclick="toggle_interface(this)">Visualizzazione avanzata</a>
</div>

<div id="graphic_interface" class="hidden">

	<div id="blue_team"></div>
	
	<div>
		<span id="blue_attacker" class="player"></span>
		<span id="blue_attacker_stats" class="stats"></span>

		<span id="blue_defender" class="player"></span>
	    <span id="blue_defender_stats" class="stats"></span>
	</div>
	
	<div>
		<span id="red_score"></span>
		<div id="field_svg"><?php include "svg_subotto/field.svg"; ?></div>
		<span id="blue_score"></span>
	</div>
	
	<div>
	    <span id="red_attacker" class="player"></span>
	    <span id="red_attacker_stats" class="stats"></span>
		<span id="red_defender" class="player"></span>
	    <span id="red_defender_stats" class="stats"></span>
	</div>
	
	<div id="red_team"></div>

    <div id="debug_div"></div>
    <div id="frames_div"></div>
    <div id="time"></div>

</div>


<div id="classic_interface">
	
	<div id="team0"></div>
	<div id="team1"></div>
	
	<div id="player00"></div>
	<div id="player01"></div>
	<div id="player10"></div>
	<div id="player11"></div>
	
	<div id="player00_stats"></div>
	<div id="player01_stats"></div>
	<div id="player10_stats"></div>
	<div id="player11_stats"></div>
	
	<div id="score0"></div>
	<div id="score1"></div>
	
	
</div>


<?php
end_box();
start_box("Statistiche", "purple", "480", "right");
?>

<div id="statistics"></div>

<?php
end_box();
start_box("Grafico", "orange", "480", "left");
?>

<div class="toggle" >
    <a onclick="toggle_graph(this)" class="active">Tutta la partita</a>
    <a onclick="toggle_graph(this)">Ultimi 30 minuti</a>
</div>


<div id="graph_all" class="graph">
	<img id="score_plot_all" />
</div>


<div id="graph_recent" class="hidden graph">
	<img id="score_plot_recent" />
</div>


<?php
end_box();
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
	
	// Graphic interface
	$("#red_team").load("stats/red_team.html");
	$("#blue_team").load("stats/blue_team.html");
	
	$("#red_score").load("stats/red_score.html");
	$("#blue_score").load("stats/blue_score.html");
	
	$("#red_attacker").load("stats/red_attacker.html");
	$("#red_defender").load("stats/red_defender.html");
	$("#blue_attacker").load("stats/blue_attacker.html");
	$("#blue_defender").load("stats/blue_defender.html");
	
	$("#red_attacker_stats").load("stats/red_attacker_stats.html");
	$("#red_defender_stats").load("stats/red_defender_stats.html");
	$("#blue_attacker_stats").load("stats/blue_attacker_stats.html");
	$("#blue_defender_stats").load("stats/blue_defender_stats.html");
	
	// Classic interface
	$("#team0").load("stats/team0.html");
	$("#team1").load("stats/team1.html");
	
	$("#player00").load("stats/player00.html");
	$("#player01").load("stats/player01.html");
	$("#player10").load("stats/player10.html");
	$("#player11").load("stats/player11.html");
	
	$("#player00_stats").load("stats/player00_stats.html");
	$("#player01_stats").load("stats/player01_stats.html");
	$("#player10_stats").load("stats/player10_stats.html");
	$("#player11_stats").load("stats/player11_stats.html");
	
	$("#score0").load("stats/score0.html");
	$("#score1").load("stats/score1.html");
	
	// Statistics
	if ( n%3 == 0 ) {
		$("#statistics").load("stats/statistics.html");
	}
	
	// Graph
	if ( n%10 == 0 ) {
		d = new Date();
		$("#score_plot_all").attr("src", "stats/score_plot_all.png?"+d.getTime());
		$("#score_plot_recent").attr("src", "stats/score_plot_last.png?"+d.getTime());
	}
	n = n+1;
}
setInterval( dataRefresh, 1000 );


function toggle_interface(element) {
    if(element.classList.contains("active")) return;
    document.getElementById("classic_interface").classList.toggle("hidden");
    document.getElementById("graphic_interface").classList.toggle("hidden");
    for(e in element.parentNode.children) {
        if(element.parentNode.children[e].classList === undefined)
            continue;
        element.parentNode.children[e].classList.toggle("active");
    }
}

function toggle_graph(element) {
    if(element.classList.contains("active")) return;
    document.getElementById("graph_all").classList.toggle("hidden");
    document.getElementById("graph_recent").classList.toggle("hidden");
    for(e in element.parentNode.children) {
        if(element.parentNode.children[e].classList === undefined)
            continue;
        element.parentNode.children[e].classList.toggle("active");
    }
}

</script>

<?php

end_html();

?>
