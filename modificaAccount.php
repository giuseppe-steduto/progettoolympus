<?php
    include "base.php";
    session_start();
    
    $nuovaMail = escapa($con, $_POST["mailAccount"]);
    $nuovoNome = escapa($con, $_POST["nomeAccount"]);
    $notifichePost = $_POST["notificheAttivate"];
    $temascuroPost = $_POST["temaScuroAttivato"];
    if(isset($notifichePost))
        $notifiche = 1;
    else 
        $notifiche = 0;

    if(isset($temascuroPost))
        $temascuro = 1;
    else
        $temascuro = 0;
    $idutente = $_SESSION["idutente"];
    $queryModificaUtente = "UPDATE utenti SET nome='$nuovoNome', email='$nuovaMail', notifiche = $notifiche, temascuro=$temascuro WHERE idutente = $idutente";
    if(mysqli_query($con, $queryModificaUtente)) {
        $_SESSION["utente"] = $nuovoNome;
        $_SESSION["emailutente"] = $nuovaMail;
        $_SESSION["notifiche"] = $notifiche;
        $_SESSION["temascuro"] = $temascuro;
        tornaAHome("Parametri modificati correttamente!");
    }
    else {
        tornaAHome("Errore nella modifica dei parametri! Query:$queryModificaUtente");
    }
?>