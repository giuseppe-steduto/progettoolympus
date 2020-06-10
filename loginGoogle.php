<?php
    require_once 'GoogleAPI/vendor/autoload.php';
    require_once "base.php";
    $id_token = $_POST["idtoken"];

    $CLIENT_ID = "8751899160-bmc7qpi981l19htokhlq8pdoc8g39b7g.apps.googleusercontent.com";
    $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
        $userid = $payload['sub'];
        $emailGoogle = $payload['email'];
        $queryEmail = "SELECT * FROM utenti WHERE email LIKE \"$emailGoogle\"";
        $tmp = mysqli_query($con, $queryEmail);
        if(mysqli_num_rows($tmp) == 0) {
            esci("Email non registrata! Contatta l'amministratore.");
        }
        else {
            $u = mysqli_fetch_array($tmp);
            $utente = $u["nome"];
            $idutente = $u["idutente"];
            $not = $u["notifiche"];
            $_SESSION["utente"] = $utente;
            $_SESSION["idutente"] = $idutente;
            $_SESSION["emailutente"] = $email;
            $_SESSION["notifiche"] = $not;
        }
        echo "Login effettuato!";
        exit();
    } else {
        echo "Not okay man. Not okay at all";
    }
