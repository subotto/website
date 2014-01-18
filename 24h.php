<?php

include_once "inclusions.php";

start_html("24 ore");

make_header(true, "current");

start_box("24 ore: aggiornamento in tempo reale");

paragraph("Coming soon!");

/*

$test = FALSE;

if ( $test ) {
	
	if ( isset( $_GET["test"] ) ) echo "<h2>Questa pagina &egrave; attualmente in fase di test: le informazioni presenti sono COSE A CASO (GioMasce fa il puntiglioso e nota che non sono proprio a caso).</h2>";
	else die( paragraph(bold( "Dati non disponibili." )) );

}

?>

<table class="centro">
<col width="250" />
<col width="800" />
<col width="250" />

<tr><td>
	<div id="player00" style="display:none"></div>
	<div id="player01" style="display:none"></div>
</td><td>
	<div id="main"><b>Caricamento dati...</b></div>
</td><td>
	<div id="player10" style="display:none"></div>
	<div id="player11" style="display:none"></div>
</td></tr>

</table>

<?php

$status = trim(file_get_contents("stats/fake.html"));

if ( $status != "before" ) {

?>

<table class="centro"><tr><td>
<div id="left"></div>
</td>

<td>

<br />
<br />
<div id="right"></div>
</td></tr></table>

<?php
}
?>

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

*/
end_box();
end_html();

?>
