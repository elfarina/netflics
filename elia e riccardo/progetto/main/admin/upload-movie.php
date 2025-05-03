<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include il file di connessione
    require_once('../backend/connect.php');

    // Cartelle di destinazione
    $uploadDir = '../../';
    $posterDir = '../images/posters/vertical/';
    $movieDir = $uploadDir . 'movies/';

    // Crea le cartelle se non esistono
    if (!file_exists($posterDir)) {
        mkdir($posterDir, 0777, true);
    }
    if (!file_exists($movieDir)) {
        mkdir($movieDir, 0777, true);
    }

    // Dati del form
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $releaseYear = $_POST['release_year'];
    $rating = $_POST['rating'];
    $type = 'movie';
    $posterPath = '';
    $videoPath = '';
    $errors = [];

    // Gestione del file poster
    if (isset($_FILES['poster-file']) && isset($_POST['renamedPosterName'])) {
        $posterTmpName = $_FILES['poster-file']['tmp_name'];
        $posterOriginalName = basename($_FILES['poster-file']['name']); // Nome originale del file
        $posterExtension = pathinfo($posterOriginalName, PATHINFO_EXTENSION); // Estensione del file
        $posterNewName = strtolower(trim($_POST['title'])) . '.' . $posterExtension; // Nome del file con il titolo del film e l'estensione
        $posterNewName = preg_replace('/[^a-z0-9\-\.]/', '', $posterNewName); // Rimuovi caratteri non validi

        $posterDestination = $posterDir . $posterNewName;

        if (move_uploaded_file($posterTmpName, $posterDestination)) {
            $posterPath = $posterDestination; // Percorso del poster per il database
        } else {
            $errors[] = "Errore nel caricamento del poster.";
        }
    } else {
        $errors[] = "File poster mancante o errore nel file.";
    }

    // Gestione del file video
    if (isset($_FILES['movie-file']) && isset($_POST['renamedFileName'])) {
        $movieTmpName = $_FILES['movie-file']['tmp_name'];
        $movieNewName = basename($_POST['renamedFileName']);
        $movieDestination = $movieDir . $movieNewName;

        if (move_uploaded_file($movieTmpName, $movieDestination)) {
            $videoPath = $movieDestination; // Percorso del video per il database
        } else {
            $errors[] = "Errore nel caricamento del video.";
        }
    } else {
        $errors[] = "File video mancante o errore nel file.";
    }
    // Verifica se ci sono errori prima di inserire nel database
    if (empty($errors)) {
        // Prepara la query per l'inserimento nel database
        $stmt = $conn->prepare("INSERT INTO `content` (`title`, `description`, `release_year`, `type`, `poster_url`, `video_path`, `rating`) VALUES (?, ?, ?, 'movie', ?, ?, ?);");

        // Bind dei parametri
        $tmpPath1 = 'images/posters/'; // Percorso relativo del poster
        $tmpPath2 = '../movies/' . $movieNewName; // Percorso relativo del video
        $stmt->bind_param("ssissd", $title, $description, $releaseYear, $tmpPath1, $tmpPath2, $rating);

        if (!$stmt->execute()) {
            // Stampa l'errore di MySQL per capire cosa sta succedendo
            echo "Errore nell'esecuzione della query: " . $stmt->error;
            // Cancella i file temporanei in caso di errore
            unlink($posterPath);
            unlink($videoPath);
            $errors[] = "Errore nell'inserimento dei dati nel database: " . $conn->error;
            echo json_encode(["status" => "error", "errors" => $errors]);
        } else {
            echo json_encode(["status" => "success", "message" => "Dati inseriti nel database con successo."]);
        }
        $stmt->close();
    } else {
        // Se ci sono errori nel caricamento dei file, cancella i file temporanei
        unlink($posterPath);
        unlink($videoPath);
        // Ritorna errori di caricamento
        echo json_encode(["status" => "error", "errors" => $errors]);
    }

    // Chiudi la connessione al database
    $conn->close();
}
?>
