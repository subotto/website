<?php

include_once "inclusions.php";

$time = "28-01-2014 22:00";
$timestamp = strtotime($time);
$difference = $timestamp - time();

if ( $difference > 0 ) {
	$seconds = $difference % 60;
	$difference = (int)( $difference/60 );
	
	$minutes = $difference % 60;
	$difference = (int)( $difference/60 );
	
	$hours = $difference % 24;
	$days = (int)( $difference/24 );
	
	echo "La quinta 24 ore inizier&agrave; alle ore 22 di marted&igrave; 28 gennaio 2014. Mancano $days giorni, $hours ore, $minutes minuti e $seconds secondi!";
}

?>
