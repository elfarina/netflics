README
*** 
	index:		localhost/main/index.php
***
	-> controllo di sessione, si verrà portati automaticamente alla pagina di login se essa è scaduta
	L'header presenta il logo della pagina (href all'index), 2 bottoni con le librerie film/serie,
	una barra di ricerca per cercare dinamicamente (tramite ajax) i content esistenti in database con 
	la possibilità di navigare direttamente al player video e visualizzare tale contenuto.
	
	La pagina presenta, inoltre, un poster principale caricato randomicamente tra i content presenti 
	nel database con i dati relativi tra cui: titolo, descrizione, voto, anno di publicazione e il
	bottone "riproduci" che ci porta al player video.
	
	in fondo alla pagina troviamo dei wrapper con annesso bottone laterale per scorrere alcuni dei 
	contenuti presenti in database (in realtà son solo dei poster fittizzi, lo scopo di essi è puramente
	dimostrativo e per creare esempi di future espansioni del sito).
***
	libreria: 	localhost/main/library.php
***
	-> questa pagina, prima controlla la sessione, poi esegue una ricerca tramite get_method sui parametri
	"genre" o "title" dei vari content (vedi query nel codice) e stampa a schermo i risultati della ricerca
	tramite una "libreria" di poster cliccabili e animati che portano al player video
	
	anche qui lo stesso header di navigazione dell'index.
***
	player: 	localhost/main/play.php
***
	-> controllo sessione come gia spiegato,
	viene effettuata una ricerca del content (e/o episodio) tramite metodo get, qui poi viene aggiornata la
	pagina con il film relativo. 

	Il player video presenta 2 barre di navigazione, a sinistra i contenuti correlati per genere, o, se
	si tratta di una serie, gli altri episodi e le altre stagioni relative.
	A destra, invece, troviamo la barra di navigazione.
	
	Il player video presenta una serie di bottoni classici di ogni player video, tra cui:
	- Volume,
	- Barra di scorrimento,
	- Pausa,
	- Avanti veloce di 10 secondi,
	- Indietro veloce di 10 secondi,
	- Schermo intero,
	- Prossimo episodio (solo se si tratta di un episodio).
***
	admin:		localhost/main/admin/index.html
***
	-> inizialmente lo scopo era creare un form html per il login da amministratore, con una tabella
	annessa degli account amministratori, ma per questione di tempistiche non è stata potuta essere
	implementata. La pagina presenta comunque, una tabella riempita dai dati relativi ai content
	e relativo bottone "elimina" per eliminare l'elemento dal database.
	
	Presenta in oltre un form per la creazione del content con relativi input video e file.jpeg
	per il video e il poster del content, caricato automaticamente nelle cartelle, aggiornando
	il database del percorso dei file.
	