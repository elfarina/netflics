<?php
/*
Pagina che restituisce sottoforma di json tutti i content correlati in base al genere del content inviatogli.
(logica ajax del player video)
*/


//inizializzazione della sessione per evitare accessi indesiderati
session_start();
if (!isset($_SESSION['username'])) {
    $error_message = "sessione scaduta";
    header("Location: ../form.html?errors=$error_message");
    exit();
}

//se esiste l'id del content posso lavorare
if (isset($_GET['cid'])) {
    //sanifico il parametro passato in get (evito injection o altro)
    $cid = isset($_GET['cid']) ? preg_replace("/[^0-9\s]/", "", filter_input(INPUT_GET, 'cid', FILTER_SANITIZE_STRING)) : 0;
    //richiedo il file di configurazione della connessione
    try {
        require "user-connect.php";
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("Errore nella connessione al database.");
    }
    //preparo la query: prendi tutti i content correlati per genere (genre)
    $stmt = $conn->prepare('SELECT DISTINCT c.* 
FROM content c
JOIN content_genres cg1 ON c.id = cg1.content_id
JOIN content_genres cg2 ON cg1.genre_id = cg2.genre_id
WHERE cg2.content_id = ?
AND c.id != ?;');
    $stmt->bind_param("ii", $cid, $cid);
    $stmt->execute();
    $ris = $stmt->get_result();
    //creo un array d'appoggio da inviare poi
    $correlated = array();
    while ($row = $ris->fetch_assoc()) {
        //inizializzo l'array con i dati (array di array)
        $correlated[] = array(
            'cid' => $row['id'],
            'title' => $row['title'],
            'release_year' => $row['release_year'],
            'poster_url' => $row['poster_url'],
            'rating' => $row['rating']
        );
    }
    //restituisco il json di risposta
    echo json_encode($correlated);
}