<?php
require_once('../backend/connect.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $stmt = $conn->prepare("DELETE FROM content WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Contenuto eliminato con successo.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'eliminazione del contenuto.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID non fornito.']);
}

$conn->close();
?>
