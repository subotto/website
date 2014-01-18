<?php

include_once "inclusions.php";

$isprime = array();
$pi = array();

function findPrimes($max) {
	global $isprime;
	
	$isprime[0] = 0;
	$isprime[1] = 0;
	
	for ($i=2; $i<=$max; $i++) {
		$isprime[$i] = 1;
	}
	
	for ($i=2; $i<=$max; $i++) {
		if ( $isprime[$i] ) {
			for ($j=2*$i; $j<=$max; $j+=$i) {
				$isprime[$j] = 0;
			}
		}
	}
}

function findPi($max) {
	global $isprime,$pi;
	
	$pi[0] = 0;
	for ($i=1; $i<=$max; $i++) {
		$pi[$i] = $pi[$i-1] + $isprime[$i];
	}
}

function issquare($n) {
	if ( $n < 0 ) return FALSE;
	
	$x = (int)(sqrt($n));
	return ( $x*$x == $n );
}

function num_scritture($n) { // come somma di 2 quadrati
	$num = 0;
	for ($i=0; ($i*$i)<=($n/2); $i++) {
		$resto = $n - $i*$i;
		if ( issquare($resto) ) $num++;
	}
	
	return $num;
}

function comment($ms,$fs,$lastgoal,$teams,$tempotrascorso) {
	$total = $ms+$fs;
	$diff = $ms-$fs;
	$adiff = abs($diff);
	$max = max($ms,$fs);
	$min = min($ms,$fs);
	
	$migliori = 0;
	$peggiori = 1;
	if ( $ms < 0 ) list($migliori,$peggiori) = array($peggiori,$migliori);
	
	$minutitrascorsi = (int)($tempotrascorso/60);
	
	//findPrimes($total);
	//findPi($total);
	
	global $isprime,$pi;
	
	if ( $lastgoal == 0 && ( $ms % 500 ) == 0 && ( $ms > 0 ) ) {
		return "I ".$teams[0]["name"]." hanno segnato il loro ".$ms."-esimo gol!";
	}
	
	if ( $lastgoal == 1 && ( $fs % 500 ) == 0 && ( $fs > 0 ) ) {
		return "I ".$teams[1]["name"]." hanno segnato il loro ".$fs."-esimo gol!";
	}
	
/*
	$numprimi = $pi[$max]-$pi[$min-1];
	if ( ( $numprimi == 42 ) || ( $numprimi == 12 ) || ( $numprimi == 23 ) ) {
		return "Tra il numero di gol dei ".$teams[1]["name"]." e il numero di gol dei ".$teams[0]["name"]." ci sono esattamente ".$numprimi." numeri primi.";
	}
*/	
	$coso = (int)($total/pi());
	if ( $coso > 0 && ( $coso%100 == 0 ) ) {
		return "Il numero totale di gol ha superato ".(int)($total/pi())."&pi;.";
	}
/*	
	$s = (int)(sqrt($total));
	if ( $isprime[$s] && ($s*$s == $total) ) {
		return "Non esistono gruppi non abeliani con ordine uguale al numero totale di gol.";
	}
	
	if ( num_scritture($total) == $adiff ) {
		return "Il numero di modi di scrivere il numero totale di gol come somma di due quadrati &egrave; uguale alla differenza reti.";
	}
*/	
	if ( ( $adiff%100 == 0 ) && ( $adiff > 0 ) ) {
		return "I ".$teams[$peggiori]["name"]." stanno giocando in modo subottimale.";
	}
	
	if ( $minutitrascorsi == 682 ) {
		return "&Egrave; trascorso un tempo pari alla durata complessiva della trilogia <i>Il Signore degli Anelli</i> di Peter Jackson.";
	}
	
	if ( $adiff == 170 ) {
		return "La differenza reti &egrave; uguale alla durata in minuti del film <i>La sottile linea rossa</i>.";
	}
	
	if ( $adiff == 132 ) {
		return "La differenza reti &egrave; uguale alla durata in minuti del film <i>Il pianista</i>.";
	}
	
	if ( $adiff == 163 ) {
		return "La differenza reti &egrave; uguale alla durata in minuti del film <i>Munich</i>.";
	}
	
	if ( $adiff == 197 ) {
		return "La differenza reti &egrave; uguale alla durata in minuti del film <i>Il dottor &Zcaron;ivago</i>.";
	}
	
	if ( $adiff == 219 ) {
		return "La differenza reti &egrave; uguale alla durata in minuti del film <i>Lawrence d'Arabia</i>.";
	}
	
	if ( $adiff == 318 ) {
		return "La differenza reti &egrave; uguale alla durata in minuti del film <i>Novecento</i>.";
	}
	
	if ( $minutitrascorsi == 453 ) {
		return "L'anno scorso a quest'ora i Matematici hanno segnato il loro 500-esimo gol.";
	}
	
	if ( $minutitrascorsi == 551 ) {
		return "L'anno scorso a quest'ora i Fisici hanno segnato il loro 500-esimo gol.";
	}
	
	if ( $minutitrascorsi == 813 ) {
		return "L'anno scorso a quest'ora i Matematici hanno segnato il loro 1000-esimo gol.";
	}
	
	if ( $minutitrascorsi == 1049 ) {
		return "L'anno scorso a quest'ora i Fisici hanno segnato il loro 1000-esimo gol.";
	}
	
	if ( $minutitrascorsi == 1290 ) {
		return "L'anno scorso a quest'ora i Matematici hanno segnato il loro 1500-esimo gol.";
	}
	
	if ( $minutitrascorsi == 486 ) {
		return "L'anno scorso a quest'ora i Matematici sono arrivati ad avere 100 gol in pi&ugrave; dei Fisici.";
	}
	
	if ( $minutitrascorsi == 874 ) {
		return "L'anno scorso a quest'ora i Matematici sono arrivati ad avere 200 gol in pi&ugrave; dei Fisici.";
	}
	
	if ( $total == 1694 ) {
		return "Il numero totale di gol &egrave; uguale al numero di righe di codice di questa interfaccia web.";
	}
	
	if ( $minutitrascorsi == 180 ) {
		return "&Egrave; stato superato il tempo che Marco e Fabrizio hanno impiegato per scrivere 2 e-mail.";
	}
}


?>
