<?php

include_once "inclusions.php";

start_html("24 ore", FALSE, TRUE);

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

?>

<div id="time_box"></div>

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
    <div id="time" style="display:none"></div>

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

<div id="countdown24h" >

</div>

<script>
function interface_callback(active) {
    countdown();
    if (active == "classic") stop_svg();
    else start_svg();
}
</script>

<?php
end_box();
start_box("Statistiche", "purple", "480", "right", "statisticsbox");
?>

<div id="statistics"></div>

<?php
end_box();
start_box("Grafico", "orange", "480", "left", "graphbox");
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
	
	if ( status != "before" ) {
		$("#time_box").load("stats.php?page_name=time");
		$("#team_0").load("stats.php?page_name=team0");
		$("#team_1").load("stats.php?page_name=team1");
		$("#team_-0").load("stats.php?page_name=team0");
		$("#team_-1").load("stats.php?page_name=team1");
	
		// Graphic interface
		if ( ! document.getElementById("graphic_interface").classList.contains("hidden") ) {
			$("#red_team").load("stats.php?page_name=red_team");
			$("#blue_team").load("stats.php?page_name=blue_team");
	
			$("#red_score").load("stats.php?page_name=red_score");
			$("#blue_score").load("stats.php?page_name=blue_score");
	
			$("#red_attacker").load("stats.php?page_name=red_attacker");
			$("#red_defender").load("stats.php?page_name=red_defender");
			$("#blue_attacker").load("stats.php?page_name=blue_attacker");
			$("#blue_defender").load("stats.php?page_name=blue_defender");
	
			$("#red_attacker_stats").load("stats.php?page_name=red_attacker_stats");
			$("#red_defender_stats").load("stats.php?page_name=red_defender_stats");
			$("#blue_attacker_stats").load("stats.php?page_name=blue_attacker_stats");
			$("#blue_defender_stats").load("stats.php?page_name=blue_defender_stats");
		}
	
		// Classic interface
		if ( ! document.getElementById("classic_interface").classList.contains("hidden") ) {
			$("#team0").load("stats.php?page_name=team0");
			$("#team1").load("stats.php?page_name=team1");
	
			$("#player00").load("stats.php?page_name=player00");
			$("#player01").load("stats.php?page_name=player01");
			$("#player10").load("stats.php?page_name=player10");
			$("#player11").load("stats.php?page_name=player11");
	
			$("#player00_stats").load("stats.php?page_name=player00_stats");
			$("#player01_stats").load("stats.php?page_name=player01_stats");
			$("#player10_stats").load("stats.php?page_name=player10_stats");
			$("#player11_stats").load("stats.php?page_name=player11_stats");
	
			$("#score0").load("stats.php?page_name=score0");
			$("#score1").load("stats.php?page_name=score1");
		}
	
		// Statistics
		if ( n%3 == 0 ) {
			$("#statistics").load("stats.php?page_name=statistics");
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

function countdown() {
    status = "running"
    if(status == "before") {
        add_class("graphic_interface", "hidden");
        add_class("classic_interface", "hidden");
        add_class("statisticsbox", "hidden");
        add_class("graphbox", "hidden");
        remove_class("countdown24h", "hidden");
        $("#countdown24h").load("stats.php?page_name=countdown");
    } else {
        if(document.getElementById("countdown24h").classList.contains("hidden"))
            return;
        remove_class("classic_interface", "hidden");
        remove_class("statisticsbox", "hidden");
        remove_class("graphbox", "hidden");
        add_class("countdown24h", "hidden");
        loadtoggle("interface");
    }
}

setInterval(countdown, 1000);
countdown();

</script>

<?php

end_html();

?>
