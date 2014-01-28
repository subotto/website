<?php

include_once "inclusions.php";

start_html("24 ore", FALSE, TRUE);

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

?>

<div id="time_box" ></div>

<div class="toggle" id="toggle_interface">
    <a onclick="toggle(this)" id="tab_classic" class="active">Visualizzazione classica</a>
    <a onclick="toggle(this)" id="tab_graphic">Visualizzazione avanzata</a>
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

<script>
function interface_callback(active) {
    if (active == "classic") stop_svg();
    else start_svg();
}

loadtoggle("interface");
</script>

<?php
end_box();
start_box("Statistiche", "purple", "480", "right");
?>

<div id="statistics"></div>

<?php
end_box();
start_box("Grafico", "orange", "480", "left");
?>


<div class="toggle" id="toggle_graph">
    <a onclick="toggle(this)" id="tab_full" class="active">Tutta la partita</a>
    <a onclick="toggle(this)" id="tab_recent">Ultimi 30 minuti</a>
</div>


<div id="full_graph" class="graph">
	<img id="score_plot_all" />
</div>


<div id="recent_graph" class="hidden graph">
	<img id="score_plot_recent" />
</div>

<div id="legend">
    <table>
        <tr>
            <td><span class="green-dashed"></p></td>
            <td>Punteggio <span id="team_0"></span> anno precedente</td>
        </tr>
        <tr>
            <td><span class="green-full"></p></td>
            <td>Punteggio <span id="team_-0"></span> anno corrente</td>
        </tr>
        <tr>
            <td><span class="blue-dashed"></p></td>
            <td>Punteggio <span id="team_1"></span> anno precedente</td>
        </tr>
        <tr>
            <td><span class="blue-full"></p></td>
            <td>Punteggio <span id="team_-1"></span> anno corrente</td>
        </tr>
    </table>
</div>

<?php
end_box();
/*
$status = trim(file_get_contents("stats/fake.html"));

if ( $status != "before" ) {

}
*/
?>

<script type="text/javascript">

init_field();

var n=0;
function dataRefresh() {
	$("#time_box").load("stats/time.html");
	$("#team_0").load("stats.php?page_name=team0");
    $("#team_1").load("stats.php?page_name=team1");
	$("#team_-0").load("stats.php?page_name=team0");
    $("#team_-1").load("stats.php?page_name=team1");
	
	// Graphic interface
	if ( ! document.getElementById("graphic_interface").classList.contains("hidden") ) {
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
	}
	
	// Classic interface
	if ( ! document.getElementById("classic_interface").classList.contains("hidden") ) {
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
	}
	
	// Statistics
	if ( n%3 == 0 ) {
		$("#statistics").load("stats/statistics.html");
	}
	
	// Graph
	if ( n%10 == 0 ) {
		d = new Date();
		if ( ! document.getElementById("full_graph").classList.contains("hidden") ) {
			$("#score_plot_all").attr("src", "stats/score_plot_all.png?"+d.getTime());
		}
		if ( ! document.getElementById("recent_graph").classList.contains("hidden") ) {
			$("#score_plot_recent").attr("src", "stats/score_plot_last.png?"+d.getTime());
		}
	}
	n = n+1;
}

var refresh;

function start_refresh() {
	refresh = setInterval( dataRefresh, 1000 );
}

function stop_refresh() {
	clearInterval(refresh);
}

start_refresh();

function graph_callback(active) {
    stop_refresh();
    n = 0;
    start_refresh();
}

loadtoggle("graph");

</script>

<?php

end_html();

?>
