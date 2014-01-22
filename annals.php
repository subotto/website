<?php

// Storia della 24 ore

include_once "inclusions.php";

start_html("Storia della 24 ore");
make_header(true, "event");

?>

<div class="page-title">
	Storia della 24 ore
</div>

<?
start_box("Edizione 2010");
?>

<p>
La 24 ore di biliardino nasce nel 2010 da un'idea di Fabrizio Bianchi,
ispiratosi ad un torneo di calcio (organizzato ogni anno a Barzanò) in
cui i giocatori di entrambe le squadre giocano a turno per una durata
complessiva di una giornata.
La prima edizione della 24 ore di biliardino viene organizzata dallo
stesso Fabrizio Bianchi e da Stefano Bolzonella, capitani delle due
squadre destinate ad affrontarsi sul campo: <em>Matematici</em> e <em>Fisici</em>.
Sebbene la tradizione voglia che questi siano i nomi delle squadre, in
realtà non prendono parte all'evento solo matematici e fisici, ma anche
chimici, biologi, informatici e letterati.
</p>
<p>
<img src="images/schermo-prima-24ore.png" class="object-with-shadow" style="margin: 0px 0px 0px 15px" width="320", height="240"  align="right"/>
La partita viene giocata negli scantinati del Timpano, sullo speciale
biliardino noto a tutti con il nome di <em>Subotto</em>.
Non ci sono turni programmati in anticipo: ciascuno dei due capitani sceglie
al momento i giocatori da schierare (con la regola di poter effettuare i cambi solo quando il numero totale di gol è multiplo di 10).

I gol vengono segnati sia manualmente (usando i pallottolieri del biliardino
per contare le unità e la lavagna per tener conto delle decine) sia con
un semplice programma in Java scritto per l'occasione da Giovanni Mascellani.
</p>

<p>
Il punteggio viene proiettato grazie ad un tabellone grafico realizzato da Francesco Guatieri.
</p>
<p>
I Matematici rimangono in vantaggio sin dal primo gol, e mantengono il controllo della partita dalle 21.58 del 3 marzo (ora d'inizio) fino al pomeriggio del giorno successivo. Nella prima parte del pomeriggio i Fisici vengono ulteriormente indeboliti dai laboratori, che impegnano gran parte della squadra. Dalle 18 alle 20 invece, durante i corsi interni, si assentano molti valenti giocatori su cui fa affidamento la squadra dei Matematici, e i Fisici cominciano a rimontare.
Il finale di partita è tesissimo: i Matematici tornati dai corsi interni faticano ad arginare lo slancio dei Fisici, che sfiorano i 10 gol di differenza sotto i quali si andrebbe ai tempi supplementari. Il punteggio finale è di 1636-1616.
</p>

<?php
end_box();

start_box("Edizione 2011");
?>
<p>
L'anno successivo, la 24 ore viene riproposta nei primi giorni di aprile.
La novità tecnica sta nella possibilità di visualizzare in tempo reale
il punteggio della partita su internet: il programma utilizzato per tenere
conto del punteggio viene infatti migliorato da Giovanni Mascellani e
Denis Nardin in modo da supportare una basilare interfaccia web in PHP.
Prima della sfida, il Subotto viene aggiustato e rinforzato dall'istancabile
lavoro di Francesco Guatieri e Gennady Uraltsev, opera commemorata dalla
frase <em>&ldquo;Nemmeno Dio potrà affondare questo biliardino&rdquo;</em>,
stampata su un biglietto e posta sotto al campo.
</p>
<p>
I turni di gioco dalle 8 alle 20 vengono organizzati in anticipo, al fine di avere scontri il più possibile equilibrati. Di notte e durante le ultime ore della partita, invece, continua ad esserci quella che viene denominata <em>giungla</em>: i Capitani decidono chi far giocare a loro totale discrezione.
Si consolida la tradizione per ciascuna delle due squadre di schierare almeno una matricola nella coppia iniziale; questa diverrà in futuro una vera e propria regola.
</p>
<p>
La partita comincia con la duplice telecronaca di Alessandro Moia (detto <em>Alf</em>), che già aveva commentato la prima 24 ore con dichiarata parzialità, e di Francesco Morosi, chiamato a sostenere la <em>squadra rossa</em> (i Matematici) contro le sottili affermazioni dell'Alf.
Stavolta l'equilibrio non si mantiene a lungo e i Matematici vincono con un buon margine (1658-1398), sebbene non riescano a mantenersi in vantaggio per tutta la partita.
</p>

<?php
end_box();

start_box("Edizione 2012");
?>

<p>
Il Timpano, collegio che dalla notte dei tempi ospitava il maestoso Subotto, viene chiuso per ristrutturazione. Il Subotto viene quindi spostato al Carducci, che diventa il nuovo centro della scuola di biliardino della SNS. Di conseguenza, anche la 24 ore inizia ad essere ospitata nel collegio a sud dell'Arno, all'interno della Sala Proiezioni.
</p>
<p>
Con il passaggio della fascia di capitano dei Matematici, nasce la tradizione del trailer: quello del 2012, basato su alcune scene de <i>Le Due Torri</i>, viene progettato e realizzato nel giro di mezza giornata dal nuovo capitano Giovanni Paolini e da Denis Nardin.
Comincia a prendere forma il sito web, che non ospita più solamente la pagina del punteggio in tempo reale. Grazie alla regia di Giovanni Mascellani, infatti, su questo sito comincia ad essere mostrato anche lo streaming live: per quasi tutta la durata della partita il campo di gioco viene ripreso da alcune webcam con diverse angolazioni, le immagini sono trasmesse sul web e decine di spettatori seguono online il grande evento.
Una nuova interfaccia scritta in Python da Gennady Uraltsev consente di memorizzare più facilmente le squadre che si succedono al biliardino. Vengono fatti inoltre i primi passi verso lo sviluppo di fotocellule per segnare automaticamente i gol, ma purtroppo non si riesce a realizzare in tempo un prototipo funzionante.
</p>
<p>
L'evento è nuovamente un successo, e si superano i 100 partecipanti totali (più di 50 per squadra).
La partita viene dominata dai Matematici, che prevalgono sia di notte che di giorno arrivando a vincere 1865-1442.
</p>

<?php
end_box();

start_box("Edizione 2013");
?>
<p>
Il 2013 continua sulla scia delle importanti innovazioni tecniche.
I mesi precedenti alla 24 ore vengono segnati da discussioni e progetti
per la realizzazione delle tanto ambite fotocellule. Vengono realizzate
due batterie da quattro sensori, con design molto diversi: le quattro
fotocellule di Francesco Guatieri sono basate su un'elaborazione hardware
del segnale, mentre quelle di Alessandro Achille si appoggiano quasi
interamente su un controllo software.
</p>
<p>
Esce alcune settimane prima della 24 ore il nuovo trailer, anch'esso
frutto di mesi di lavoro (di manodopera umana e di computer). Nella sua
produzione, Giovanni Paolini viene affiancato da numerosi aiutanti:
Alessandro Achille ed Enrico Polesel per gli effetti grafici,
Francesco Veneziano per la traduzione in elfico di luoghi di Pisa,
Giovanni Mascellani per la registrazione della canzone dei nani, e
Ugo Bindini nel ruolo di direttore (nonché quasi unica componente)
dell'orchestra.
</p>
<p>
Oltre alle fotocellule, vengono aggiunti al Subotto dei display 7-segment,
per mostrare il punteggio delle due squadre, e quattro pulsanti
(i <em>Subottoni</em>) per aggiungere e togliere gol.
Il Subotto inizia a distinguersi dai comuni biliardini non più soltanto
per il nome, la fama e i supergol, ma anche per la complessa elettronica
di cui è stato dotato.
</p>
<p>
Nel frattempo, Giovanni Mascellani e Giovanni Paolini progettano un nuovo
database, più flessibile e potente, per la memorizzazione dei dati di
tutte le 24 ore. Il nuovo design consente di separare diversi processi:
il programma di gestione della partita, il programma di gestione di
fotocellule, bottoni e display, e il programma utilizzato per elaborare 
le informazioni da mostrare sul web.
Anche l'interfaccia web viene migliorata: le informazioni presenti sul
nuovo database unificato consentono di mostrare statistiche non solo sulla
partita in corso ma anche su quelle precedenti (tempo di gioco e gol segnati
dai quattro giocatori in campo). Come già nell'anno precedente, viene man
mano disegnato un grafico con l'andamento dei punteggi nel corso delle 24 ore.
</p>
<p>
Come ormai avviene dal 2011, nei giorni precedenti la partita il Subotto
viene smontato e ripulito. Stavolta però, oltre alla pulizia, avviene
anche una meticolosa sostituzione delle molle e degli altri pezzi da
cambiare. L'opera di manutenzione viene supervisionata dal nuovo Vicecapitano
dei Matematici, Alessandro "Sasha" Iraci.
Vengono inoltre acquistate delle nuove palline, che vanno a sostituire
quelle ormai erose per le innumerevoli ore di gioco dai tempi antichi del Timpano.
</p>
<p>
La partita comincia con mezz'ora di ritardo (era in effetti successo
anche nel 2012), per problemi tecnici con le fotocellule e lo streaming,
ma poi le cose vanno per il meglio. Lo streaming regge per tutte e 24 le
ore, come anche le fotocellule di Francesco Guatieri e i display 7-segment,
anche se le fotocellule hanno alcuni problemi nei primi minuti di gioco.
I Matematici hanno una grande partenza, ma vengono ripresi un paio d'ore
dopo l'inizio. Durante la notte i Fisici cedono nuovamente terreno, e al
mattino i Matematici hanno più di 150 gol di vantaggio. Durante il giorno
inizia una lenta ma tenace rimonta dei Fisici, che riapre i giochi e
aggiunge suspence alla sfida. A poche ore dalla fine, però, i Matematici
si riorganizzano e riescono con determinazione a mantenere l'esiguo
distacco dagli avversari. La partita si conclude con il risultato di 1519-1458.
</p>
<p>
Pochi secondi dopo il termine del match viene proiettato il
<em>trailer clandestino</em>, creato in gran segreto da Giovanni Mascellani,
Fabrizio Bianchi ed Enrico Polesel alcuni giorni prima. Il pubblico
dimostra di apprezzare la sorpresa.
</p>


<?php
end_box();
end_html();

?>
