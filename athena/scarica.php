<?php
    include "../base.php";
    session_start();
    $idappunto = $_GET["id"];
    $idappunto = escapa($con, $idappunto);
    
    //Registra tentativo di download nella tabella
    $idutente = $_SESSION["idutente"];
    if(!isProprietarioOf($con, $idutente, $idappunto)) {
        $queryAggiornaDownload = "INSERT INTO `downloadappunti`(`idutente`, `idappunto`, `orario`) VALUES ($idutente,$idappunto,NOW())";
        if(!mysqli_query($con, $queryAggiornaDownload)) {
            $logDownload = fopen("../logs/aggiornamentoNdownload.txt", "a+");
            fwrite($logDownload, "Inserimento nella tabella dei download fallito! Query:$queryAggiornaDownload\n");
            fclose($logDownload);
        }
    }
    $linkQuery = "SELECT link FROM appunti WHERE idappunto = $idappunto";
    $tmp = mysqli_query($con, $linkQuery);
    $coso = mysqli_fetch_array($tmp);
    $link = $coso["link"];
    header("Location: $link");
?>