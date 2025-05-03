<?php
require_once('../backend/connect.php');

$query = "SELECT id, title, description, release_year, rating FROM content";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $content = [];
    while ($row = $result->fetch_assoc()) {
        $content[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $content]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nessun contenuto trovato.']);
}

$conn->close();
?>
