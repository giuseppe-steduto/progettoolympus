<?php
    include "base.php";
    session_start();
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password = md5(md5($password));

    //Prendo l'username e lo salvo nella variabile di sessione
    $queryUser = "SELECT nome, idutente, notifiche, temascuro FROM utenti WHERE email = '$email'";
    $utentiTmp = mysqli_query($con, $queryUser);
    if(mysqli_num_rows($utentiTmp) == 0) {
        esci("Email non registrata! Contatta l'amministratore.");
    }
    $u = mysqli_fetch_array($utentiTmp);
    $utente = $u["nome"];
    $idutente = $u["idutente"];
    $not = $u["notifiche"];
    $temaScuro = $u["temascuro"];
    $_SESSION["utente"] = $utente;
    $_SESSION["idutente"] = $idutente;
    $_SESSION["emailutente"] = $email;
    $_SESSION["notifiche"] = $not;
    $_SESSION["temascuro"] = $temaScuro;

    //Controllo il login
    $queryLogin = "SELECT * FROM utenti WHERE email = '$email' AND password = '$password'";
    $utentiTmp = mysqli_query($con, $queryLogin) or die("Errore nella query login");
    if(mysqli_num_rows($utentiTmp) == 0) {
        esci("Login non autorizzato.");
    }
    
    //Redirect alla pagina principale
    header("Location: index.php");
    exit();
?>
