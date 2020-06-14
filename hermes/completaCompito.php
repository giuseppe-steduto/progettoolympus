<?php
    include "../base.php";
    session_start();
    controlloLogin();

    $idcompito = $_GET["id"];
    $idutente = $_SESSION["idutente"];

    $queryFaiCompito = "INSERT INTO `compitifatti`(`idutente`, `idcompito`) VALUES ($idutente,$idcompito)";
    if(eseguiQuery($con, $queryFaiCompito)) {
        echo "okay";
    }
    else {
        echo "nonono.";
    }