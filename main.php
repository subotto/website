<?php

include_once "inclusions.php";

$test = TRUE;

// Carico lo status
$status = trim(file_get_contents("stats/fake.html"));
//var_dump($status);

if ( $status == "before" ) {
	// Countdown
	include_once "stats/countdown.html";
}
else {

	// Timer
	include_once "stats/time.html";
	
	// Score
	include_once "stats/score.html";

}

?>
