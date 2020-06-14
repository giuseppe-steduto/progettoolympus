<?php
    ini_set('display_errors', 1);
    include "../invianotifiche.php"; //Include implicitamente anche base.php
    session_start();
    $idutente = $_SESSION["idutente"];
    $titolo = $_POST["titolo"];
    $titolo = mysqli_real_escape_string($con, $titolo);
    $descrizione = $_POST["testo"];
    $descrizione = mysqli_real_escape_string($con, $descrizione);
    $materia = $_POST["materia"];
    $macroaree = $_POST["macroarea"]; //Array di codici di macroaree per un appunto
    $tipo = $_POST["tipo"];
    $link = $_POST["linkFile"];
    $link = mysqli_escape_string($con, $link);
    $link = htmlspecialchars($link);
    
    //File di log delle mail
    $fileMail = fopen("../logs/invioNotificaMail.txt", "a+");
    
    //Inserimento nella tabella
    $query = "INSERT INTO appunti(codicemateria, idutente, datacaricamento, titolo, testo, tipo, link) VALUES($materia, $idutente, now(), \"$titolo\", \"$descrizione\", $tipo, \"$link\")";
    if(mysqli_query($con, $query)) {
        echo "Inserimento appunto avvenuto correttamente <br />";
        $idappunto = mysqli_insert_id($con); //Ottiene la chiave primaria dell'ultimo elemento inserito (l'id dell'appunto)
        
        //Notifica i vari utenti con le notifiche mail abilitate dell'avvenuto inserimento
        /*
        $linkMail = "https://giuseppesteduto.altervista.org/bancaAppunti/login.html";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <appunti@steduto.it>' . "\r\n";
        $corpoMessaggio = "Un nuovo appunto è stato aggiunto alla banca dati!";
        $corpoMessaggio .= "<br /><strong>Titolo: </strong>$titolo<br />";
        $corpoMessaggio .= "Clicca <a href = '$linkMail'>qui</a> per fare il login e visualizzarlo! <br /><br />";
        $corpoMessaggio .= "Puoi disattivare le notifiche dall'apposito pannello di controllo. Una volta fatto il login, clicca sul tuo nome utente in alto a destra.<br /><br />";
        $oggetto = "Nuovo appunto inserito!";
        $queryUtenti = "SELECT email FROM utenti WHERE idutente <> $idutente AND notifiche = 1";
        $utentivari = mysqli_query($con, $queryUtenti);
        while($user = mysqli_fetch_array($utentivari)) {
            if(!mail($user["email"], $oggetto, $corpoMessaggio, $headers)) {
                fwrite($fileMail, "INVIO MAIL FALLITO! Indirizzo: " . $user["email"] . " | idappunto: " . mysqli_insert_id($con) . "\n");
            }
        }*/

        //Notifica gli utenti con le notifiche push
        $notifica = $titolo . " - " . getNomeMateria($con, $materia) . " - " . getNomeUtente($con, $idutente);

        $queryUtentiNot = "SELECT parametrinotifiche FROM utenti WHERE idutente <> $idutente AND notifiche = 1 AND parametrinotifiche IS NOT NULL";
        $utentiVariNot = mysqli_query($con, $queryUtentiNot);
        inviaNotifiche($utentiVariNot, $notifica);
    } else {
        echo "Inserimento appunto non riuscito! $query";
        fclose($fileMail);
        exit();
    }

    //Prova inserimento delle macroaree
    foreach($macroaree as $area) {
        $queryArea = "INSERT INTO collegamento(codicemacroarea, idappunto) VALUES($area, $idappunto)";
        mysqli_query($con, $queryArea) or die("Errore nell'inserimento della macroarea. Query: $queryArea");
    }
    fclose($fileMail);
    header("Location: index.php");

?>
