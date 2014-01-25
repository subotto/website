<?php

include_once "inclusions.php";

start_html("24 ore", FALSE, TRUE);

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

?>

<div id="time" />

<div id="graphic_interface">

	<div id="blue_team" />
	
	<div>
		<span id="blue_attacker_stats" />
		<span id="blue_attacker" />
		
		<span id="blue_defender_stats" />
		<span id="blue_defender" />
	</div>
	
	<div>
		<span id="red_score" />
		<div id="field">
			<div id="debug_div"></div>
			<div id="frames_div"></div>
			<div><?php include "field.svg"; ?></div>
			<div id="time"></div>
		</div>
		<span id="blue_score" />
	</div>
	
	<div>
		<span id="red_attacker_stats" />
		<span id="red_attacker" />
		
		<span id="red_defender_stats" />
		<span id="red_defender" />
	</div>
	
	<div id="red_team" />

</div>

<div id="classic_interface">
	...
</div>

<div id="statistics" />

<div id="graph" />



<?php

$status = trim(file_get_contents("stats/fake.html"));

if ( $status != "before" ) {

}
?>

<script>
init_field();
</script>

<script type="text/javascript">

var n=0;

function dataRefresh() {
	loadXMLDoc("main", "main.php");
	loadXMLDoc("left", "left.php");
	
	if (n%3 == 0) {
		loadXMLDoc("player00", "stats/player00.html");
		loadXMLDoc("player01", "stats/player01.html");
		loadXMLDoc("player10", "stats/player10.html");
		loadXMLDoc("player11", "stats/player11.html");
	}
	
	if (n%20 == 0) loadXMLDoc("right", "right.php");
	n = n+1;
	
	setTimeout("dataRefresh()",1000);
}

dataRefresh();

</script>

<?php

end_box();
end_html();

?>
