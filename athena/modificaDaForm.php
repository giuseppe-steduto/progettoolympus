<?php
    include "../base.php";
    session_start();
    $idappunto = $_SESSION["idappunto"];
    $idutente = $_SESSION["idutente"];
    $titolo = $_POST["titolo"];
    $titolo = mysqli_real_escape_string($con, $titolo);
    $descrizione = $_POST["testo"];
    $descrizione = mysqli_real_escape_string($con, $descrizione);
    $materia = $_POST["materia"];
    $macroaree = $_POST["macroarea"]; //Array di codici di macroaree per un appunto
    $tipo = $_POST["tipo"];
    $link = $_POST["linkFile"];
    $link = mysqli_real_escape_string($con, $link);
    $query = "UPDATE `appunti` SET `codicemateria`=$materia,`titolo`='$titolo',`testo`='$descrizione',`tipo`=$tipo,`link`='$link' WHERE idappunto = $idappunto ";
    if($idutente != 5) {
        $query .= "AND idutente = $idutente";
    }
    mysqli_query($con, $query) or die("Errore nell'aggiornamento dell'appunto. Query: $query");
    
    $queryEliminaMacroaree = "DELETE FROM collegamento WHERE idappunto = $idappunto";
    mysqli_query($con, $queryEliminaMacroaree);
    
    //Prova inserimento delle macroaree
    foreach($macroaree as $area) {
        $queryArea = "INSERT INTO collegamento(codicemacroarea, idappunto) VALUES($area, $idappunto)";
        mysqli_query($con, $queryArea) or die("Errore nell'inserimento della macroarea. Query: $queryArea");
    }
    header("Location: index.php");
    
