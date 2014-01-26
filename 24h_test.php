<?php

include_once "inclusions.php";

start_html("24 ore", FALSE, TRUE);

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

?>

<div id="time_box" ></div>

<div class="toggle" >
    <a onclick="toggle_interface(this)" class="active">Interfaccia grafica <!-- TODO: trovare un nome migliore --></a>
    <a onclick="toggle_interface(this)">Interfaccia classica </a>
</div>

<div id="graphic_interface">

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


<div id="classic_interface" class="hidden">
	...
</div>


<?php
end_box();
start_box("Statistiche", "purple", "480", "right");
?>

<div id="statistics">
	<div id="general_stats"></div>
	<div id="projection"></div>
</div>

<?php
end_box();
start_box("Grafici", "orange", "480", "left");
?>

<div class="toggle" >
    <a onclick="toggle_graph(this)" class="active">Grafico completo</a>
    <a onclick="toggle_graph(this)">Grafico recente</a>
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
	
	// Statistics
	$("#general_stats").load("stats/general_stats.html");
	$("#projection").load("stats/projection.html");
	
	// Graph
	d = new Date();
	$("#score_plot_all").attr("src", "stats/score_plot.png?"+d.getTime());
	$("#score_plot_recent").attr("src", "stats/score_plot.png?"+d.getTime());
	
	n = n+1;
}
setInterval( dataRefresh, 500 );


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
