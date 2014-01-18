<?php

include_once "inclusions.php";

$test = TRUE;

if ( $test ) echo "<h2>Questa pagina &egrave; attualmente in fase di test: le informazioni presenti sono COSE A CASO (GioMasce fa il puntiglioso e nota che non sono proprio a caso).</h2>";


// Carico lo status
$status = trim(file_get_contents("stats/fake.html"));
var_dump($status);

if ( $status == "before" ) {
	// Countdown
	include_once "stats/countdown.html";
}
else {


echo '<table class="centro"> <col width="180" /> <col width="800" /> <col width="180" />';
echo '<tr><td>';

include_once "stats/player00.html";
include_once "stats/player01.html";

echo '</td><td>';

// Timer
include_once "stats/time.html";

// Score
include_once "stats/score.html";

echo '</td><td>';

include_once "stats/player10.html";
include_once "stats/player11.html";

echo '</td></tr></table>';

?>

<table class="centro"><tr><td>

<?php

// Statistiche generali
include_once "stats/general_stats.html";

if ( $status != "ended" ) {
	echo "<br />";
	
	// Proiezione lineare
	include_once "stats/projection.html";
}

// Stampa orario
echo "<p>".date('D, d M Y H:i:s')."</p>";
?>

</td>

<td>

<br />
<br />

<?php

$path = 'graph/graph.png';
if ( ( !file_exists($path) ) || ( time() - filectime($path) >= 10 ) ) {
	$locked = FALSE;
	if ( file_exists($path) ) {
		$locked = TRUE;
		flock($path,LOCK_EX);
	}
	include 'graph.php';
	if ( $locked ) flock($path,LOCK_UN);
}
//echo filectime('graph/graph.png');

//echo '<img src="graph.php?time='.((int)(time()/10)).' alt="Grafico dei gol" height="500" width="700" />';

echo '<img src="graph/graph.png?time='.((int)(time()/10)).'" alt="Grafico dei gol" height="500" width="750" />'

?>

</td></tr></table>

<?php

}

// Lo scorso anno matematici hanno vinto 1658-1398!

?>
