<?php

include_once "inclusions.php";

$test = TRUE;


// Carico lo status
$status = trim(file_get_contents("stats/fake.html"));

if ( $status != "before" ) {

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

/*

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
*/

echo '<img src="stats/score_plot.png" alt="Grafico dei gol" height="500" width="750" />';

?>

</td></tr></table>

<?php

}


?>
