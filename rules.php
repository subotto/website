<?php

include_once "inclusions.php";

start_html("Regolamento 24 ore");
make_header(true, "event");

?>

<?php


start_box("Regolamento");

?>

<h2>Regole di gioco</h2>
Le modalità di gioco sono quelle del biliardino SNS, descritte sulla Wiki di UZ (<a href="https://uz.sns.it/wiki/index.php/Biliardino">https://uz.sns.it/wiki/index.php/Biliardino</a>), con le seguenti modifiche o precisazioni.

<ul>
<li>La partita non termina quando una squadra arriva a 6 gol, ma continua per una durata complessiva di 24 ore.</li>
<li>Una squadra non può mantenere il controllo della pallina, senza farle oltrepassare stecche avversarie, per più di 30 secondi; se ciò accade, la pallina viene data alla difesa avversaria ed è considerata "da tirare".
Perdere tempo di proposito è in ogni caso considerato un comportamento antisportivo.</li>
<li>Come conseguenza del punto precedente, in fase di rimessa la difesa ha 30 secondi per effettuare un tiro valido.</li>
<li>Non vale la regola dell'annullamento dei gol nel caso in cui passino ragazze.</li>
</ul>

<p>Nell'arco delle 24 ore le squadre possono cambiare i propri giocatori in campo ogniqualvolta il numero totale di gol segnati sia multiplo di 10.
Ciascuna squadra può chiedere il cambio campo soltanto se il numero totale di gol segnati è multiplo di 30.
Si può chiedere la lubrificazione delle stecche ogni 6 ore.</p>

<p>Se dopo 24 ore di gioco la differenza dei punteggi è strettamente minore di 10, si continua a giocare fino a quando una delle due squadre raggiunge i 10 gol di vantaggio.</p>


<h2>Suddivisione della partita</h2>
La 24 ore viene usualmente disputata con inizio alle ore 22 e conclusione alle 22 della sera successiva. La partita è suddivisa in quattro momenti principali:
<ul>
<li>l'Apertura (fino a mezzanotte), durante la quale i giocatori vengono cambiati molto frequentemente, per consentire a tutti i presenti di partecipare;</li>
<li>la Notte (da mezzanotte alle 8), che non è coperta da turni programmati, e durante la quale i Capitani hanno la facoltà di scegliere quali giocatori schierare in campo;</li>
<li>il Giorno (dalle 8 alle 20), durante cui il gioco è regolato da <a href="schedule.php">turni</a> (di 20-30 minuti) precedentemente concordati dai Capitani, con l'intento di avere incontri il più possibile equilibrati;</li>
<li>la Chiusura (a partire dalle 20), nuovamente senza turni programmati, come la Notte.</li>
</ul>

<h2>Altre regole</h2>

<p>Ciascuna squadra sceglie segretamente la propria coppia iniziale, che deve essere composta da almeno una matricola e non può comprendere giocatori che siano o siano stati Capitani, né giocatori che abbiano già fatto parte della coppia iniziale in una precedente 24 ore.</p>

<p>Al termine della partita, il Capitano e il Vicecapitano della squadra sconfitta devono effettuare il cammello, passando sotto al biliardino. Se la squadra vincitrice ha battuto l'altra con almeno il doppio dei punti, l'intera squadra perdente deve effettuare il cammello. Se la squadra perdente termina la partita con 0 gol e la squadra vincitrice ne ha segnati almeno 600, la squadra perdente deve effettuare il cammello per le successive 24 ore.</p>

<?php

end_box();
end_html();

?>
