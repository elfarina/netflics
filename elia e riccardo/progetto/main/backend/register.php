<?php
/*
pagina di controllo della registrazione (../form.html)
*/

//inizializzo la sessione
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("connect.php");
    $errors = []; // Array per gli errori

    // Validazione del nome
    if (empty($_POST['nome'])) {
        $errors[] = "Hai dimenticato di inserire il nome.";
    } else {
        $nome = trim($_POST['nome']);
    }

    // Validazione del cognome
    if (empty($_POST['cognome'])) {
        $errors[] = "Hai dimenticato di inserire il cognome.";
    } else {
        $cognome = trim($_POST['cognome']);
    }

    // Validazione dell'email
    if (empty($_POST['email'])) {
        $errors[] = "Hai dimenticato di inserire l'email.";
    } else {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email inserita non è valida.";
        }
    }

    // Validazione della password
    if (empty($_POST['password'])) {
        $errors[] = "Hai dimenticato di inserire la password.";
    } else {
        $password = trim($_POST['password']);
        if (strlen($password) < 8) {
            $errors[] = "La password deve essere lunga almeno 8 caratteri.";
        }
    }

    // se non son stati riscontrati errori posso andare avanti
    if (empty($errors)) {
        // Verifica se l'email esiste già (Prepared Statement)
        $stmt = $conn->prepare("SELECT em FROM users WHERE em = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "L'email esiste già, riprova.";
        }
        $stmt->close();
    }
    //controllo ulteriore degli errori (ovviabile ma ho scelto di farlo cosi)
    if (empty($errors)) {
        // Creazione dell'hash della password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Inserimento nel database (Prepared Statement)
        $stmt = $conn->prepare("INSERT INTO users (nome, cognome, em, pw) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $cognome, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Salva dati nella sessione
            $_SESSION["nome"] = $nome;
            $_SESSION["cognome"] = $cognome;
            $_SESSION["email"] = $email;

            // Redirect o messaggio di successo
            header("Location: ../index.php");
            exit();
        } else {
            echo "<h1>Errore di sistema: " . $conn->error . "</h1>";
        }

        $stmt->close();
    } else {
        // Reindirizza alla pagina form.html con gli errori come query string
        $error_message = urlencode(implode("\n", $errors));
        header("Location: ../form.html?errors=$error_message");
        exit();
    }

    // Chiudi la connessione
    $conn->close();
}
?>
