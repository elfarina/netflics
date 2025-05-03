<?php

/*
pagina di controllo dei dati inseriti in login (../form.html);
*/ 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("user-connect.php");
    // Esegui il login e controlla eventuali errori
    list($check, $data) = check_login($conn, $_POST['username'], $_POST['password']);
    if ($check) {
        session_start();
        $_SESSION['nome'] = $data['nome'];
        $_SESSION['cognome'] = $data['cognome'];
        $_SESSION['username'] = $data['username']; 

        // Redirect alla homepage con la sessione appena creata
        header("Location: ../index.php");
        exit();
    } else {
        // Concatena gli errori in una stringa e reindirizza alla pagina HTML
        $error_message = urlencode(implode("\n", $data)); // Concatenare gli errori
        header("Location: ../form.html?errors=$error_message");
        exit();
    }
}


//funzione di supporto per il controllo del login
function check_login($dbc, $username = '', $pass = '')
{
    $errors = []; //array contenente i messaggi d'errore

    // controllo dell'inserimento di email e password e conseguente controllo dell'input
    if (empty($username)) {
        $errors[] = 'Hai dimenticato di inserire: username.';
    } else {
        $u = trim($username);
    }

    // Validazione della password
    if (empty($pass)) {
        $errors[] = 'Hai dimenticato di inserire: password.';
    } else {
        $p = trim($pass);
    }
    //controllo finale degli errori, se non ce ne sono procedi con la query
    if (empty($errors)) {
        //preparazione della query
        $stmt = $dbc->prepare("SELECT * FROM users WHERE username LIKE ?");
        $stmt->bind_param("s", $u); // Associa i parametri (s = stringa)
        $stmt->execute();
        $result = $stmt->get_result();

        // se ho un risultato, l'utente esiste
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verifica della password hashata
            if (password_verify($p, $row['pw'])) {
                // Login riuscito
                return [true, $row];
            } else {
                $errors[] = 'Password errata.';
            }
        } else {
            $errors[] = 'username non trovato.';
        }

        $stmt->close();
    }

    // Restituisce false e gli errori in caso di problemi
    return [false, $errors];
}
?>
