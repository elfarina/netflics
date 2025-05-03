<?php

/*
Pagina che restituisce sottoforma di json tutti gli episodi della serie che si sta guardando al momento
(logica ajax del player video)
*/
session_start();
if (!isset($_SESSION['username'])) {
    $error_message = "sessione scaduta";
    header("Location: ../form.html?errors=$error_message");
    exit();
}

if (isset($_GET['cid']) && isset($_GET['season'])) {
    $cid = isset($_GET['cid']) ? preg_replace("/[^0-9\s]/", "", filter_input(INPUT_GET, 'cid', FILTER_SANITIZE_STRING)) : 0;
    $season = isset($_GET['season']) ? preg_replace("/[^0-9\s]/", "", filter_input(INPUT_GET, 'season', FILTER_SANITIZE_STRING)) : 1;

    try {
        require "user-connect.php";
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("Errore nella connessione al database.");
    }

    $stmt = $conn->prepare('SELECT * FROM episodes WHERE series_id = ? AND season_number = ? ORDER BY episode_number');
    $stmt->bind_param("ii", $cid, $season);
    $stmt->execute();
    $ris = $stmt->get_result();
    $episodes = array();
    while ($row = $ris->fetch_assoc()) {
        $episodes[] = array(
            'id' => $row['id'],
            'series_id' => $row['series_id'],
            'title' => $row['title'],
            'episode_number' => $row['episode_number'],
            'video_url' => $row['video_url']
        );
    }
    echo json_encode($episodes);
}
?>
