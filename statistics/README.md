# Statistiche
Questa file spiega come è fatto il DB della 24h e come aggiungere delle nuove statistiche.

## DB
Il DB si trova su soyuz , è il solito PostgreSQL. Ci si può accede tramite phpPgAdmin di uz (chi deve sa dovo trovarlo). Quello che interessa in questo caso è `subotto` ( `subotto-test` è un vecchio DB di prova, probabilmente si potrebbe droppare senza conseguenza, ma nessuno ha voglia di provare)

Il DB è strutturato con le seguenti tabelle:
- `advantage_phases`: non più utilizzata (in realtà mai utilizzata), doveva contenere dopo quanto tempo calava il vantaggio necessario.
- `events`: tutti gli eventi registrati durante le 24h.
- `log`: il log del subtracker, nessuno vuole davvero guardarla a mano.
- `matches`: è l'elenco delle 24h (vere e di test)
- `player_matches`: contiene le associazioni giocatore-match-team: ogni riga dice che il giocatore x ha giocato la 24h y con la squadra z.
- `players`: tutti quelli che hanno partecipato ad almeno una 24h.
- `queue`: probabilmente usata per sincronizzare la coda tra le varie GUI.
- `stats_player_matches`: dettagli sulle statistiche di x nella 24h y.
- `stats_turns`: dettagli sulle statistiche di ogni turno avvenuto nelle 24h (per turno si intende il periodo tra due cambi).
- `teams`: elenco delle squadre (al momento 2, matematici e fisici).

Al momento modificare a mano il DB dovrebbe servire solo per unire due giocatori (ammesso che qualcuno non scriva uno script per farlo).

## Workflow generale
Durante la 24h spesso ci sono degli eventi sbagliati (goal segnati alla coppia prima, cambi sfasati di un paio di goal, ...). La filosofia per generare le statistiche corrette è di NON correggere gli eventi (lasciando nel DB quelli sbagliati) ma di modificare i dati dei singoli turni e di generare poi le statistiche con i turni sistemati a mano.

Le operazioni da fare sono le seguenti:
- Unire i giocatori uguali salvati con nomi diversi (es: "Michael Morello" e "Michael J. Morello")
- Creare il file dei turni con lo script apposta
- Correggere a mano il file dei turni
- Aggiornare `stats_player_matches` e `stats_turns` a partire da questo file con lo script apposta

### Unire giocatori uguali
- Si effettua la ricerca sul DB nella tabella `players` (attualmente scrivendo la query a mano) per cognome (o qualsiasi altro dettaglio distintivo della persona).
- Si sostituisce l'id sbagliato con quello giusto nelle tabelle `player_matches` e `events` con la query:
```
UPDATE player_matches SET player_id=<id giusto> WHERE player_id=<id sbagliato>
```
eventualmente aggiungendo `AND match_id=<id match>` se si vuole essere sicuri di modificare solo campi di una certa 24h. Nelle query sulla tabella `events` bisogna sostituire sia `player_a_id` che `player_b_id` con altre due query.
- OPZIONALE: rimuovere da `players` i giocatori con il nome sbagliato

### Creare il file dei turni
Il file dei turni è un csv in cui ogni riga corrisponde ad un turno. Le colonne sono
```
timestamp_inizio | timestamp_fine | atk_rosso | def_rosso | atk_blu | def_blu | goal_rosso | goal_blu
```
(non è chiaro se le colonne siano davvero indicizzate con rossi/blu o se siano indicizzate con squadra 1/squadra 2, bisogna indagare).

Al momento il file dei turni viene creato dallo script php `statistics/find_turns.php` eseguito mettendolo nella public_html di qualche utente uz e visitando la pagina da browser. Il contenuto della pagina è il file csv voluto. Questo script ha hardocdato l'id della partita che va quindi modificato nel codice prima di andare sul browser (meglio ancora prima di copiarlo nella public_html).

Ovviamente questa soluzione presenta buchi di sicurezza non indifferenti, quindi sarebbe bene riscrivere lo script in modo che venga eseguito direttamente dall'utente (leggi: usa un linguaggio di programmazione sensato e non php).

### Correggere a mano il file dei turni
La prima regola per questa fase è chiaramente il buonsenso. Segue un'idea di come dovrebbe svolgersi la fase, ma sono molto probabili situazioni impreviste.

Si scorre il file, guardando quali turni non hanno la somma dei goal multipla di 10 e/o guardando il log degli errori. Si cerca di capire cos'è successo davvero. Una volta deciso che il un goal va spostato da un turno al successivo/precedente si aggiorna il conteggio dei goal nei due turni e si modifica il timestamp del cambio (fine del turno prima, inizio del turno dopo) per renderlo uguale a quello dell'ultimo goal del turno prima (realisticamente i cambi avvengono appena dopo l'ultimo goal del turno). In caso di giocatori dubbi in un turno, si controllano i turni programmati, si riguarda il video (se esiste) e ci si affida alla memoria dei presenti (motivo per cui questo lavoro andrebbe fatto il prima possibile dopo la 24h).

### Aggiornare il DB a partire dal file
Aggiornare il DB è un'operazione molto rischiosa (con gli schifosissimi script php che abbiamo al momento) perché viene fatto da browser, ovvero ogni scemo che passa da quella pagina al momento giusto può, ricaricandola, aggiungere due volte le statistiche di quell'anno nel DB, con danni abbastanza ingenti.

Per aggiornare il DB si usa lo script php `statistics/produce_statistche.php` eseguito come sopra. Ci sono due funzioni che modificano le
Prima di modificare effettivamente il DB bisogna fare le seguenti operazioni:
- aggiungere il nuovo file dei turni al dizionario `edizioni_turni`, commentando le righe degli anni precedenti
- decommentare `edition_statistics(.)` e dargli come parametro l'id della 24h da aggiungere
-

## Proposte per il futuro
- Riscrivere tutti gli script in un linguaggio di programmazione sensato (si parlava di Python) in modo da poterli eseguire da terminale e non doverli fare eseguire ad Apollo tramite public_html
- Nello script che crea il csv prendere l'id come parametro
- Nello script che crea il csv sostituire gli id dei giocatori con i loro nomi (è fattibile per poi rimandare i dati sul server? Forse è meglio tenere sia l'id che il nome nel csv)
- Fare uno script che segnala in automatico le righe con somma non =0 (mod 10)
