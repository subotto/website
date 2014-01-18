<?php

include_once "inclusions.php";

// Carico lo status
$status = trim(file_get_contents("stats/fake.html"));

if ( $status == "before" ) {
	// Countdown
	// include_once "stats/countdown.html";
}
else {

	// Timer
	include_once "stats/time2.html";
	
	//echo "<br />";
	
	// Score
	include_once "stats/score2.html";

}

?>
