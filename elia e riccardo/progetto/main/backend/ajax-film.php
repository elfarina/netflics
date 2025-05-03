<?php
/*
Pagina che restituisce i risultati della ricerca nella navbar della mainpage tramite i caratteri contenuti nel titolo del content
(ajax)
*/
if (isset($_GET['q'])) {
    try {
        require "user-connect.php";
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("Errore nella connessione al database.");
    }
    $query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
    $query = preg_replace("/[^a-zA-Z0-9\s]/", "", $query);

    $data = [];

    try {
        $stmt = $conn->prepare(
            "SELECT *
             FROM `content` 
             WHERE `title` LIKE CONCAT('%', ?, '%') 
             ORDER BY `id` DESC 
             LIMIT 10"
        );
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        // preparazione risposta json
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        //errore e stampa
        error_log("Errore nella query: " . $e->getMessage());

        // messaggio di errore generico (500)
        http_response_code(500);
        echo json_encode(["error" => "Errore interno. Riprova più tardi."]);
    } finally {

        if (isset($stmt))
            $stmt->close();
        $conn->close();
    }
}
