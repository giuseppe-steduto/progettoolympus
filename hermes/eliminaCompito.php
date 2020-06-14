<?php
    include "../base.php";
    session_start();
    controlloLogin();

    $input = file_get_contents("php://input");
    $inputJSON = json_decode($input);
    if(!isProprietarioOfCompito($con, $_SESSION["idutente"], $inputJSON->id)) {
        esci("");
    }

    $queryFaiCompito = "DELETE FROM diario WHERE idcompito = " . $inputJSON->id;
    if(eseguiQuery($con, $queryFaiCompito)) {
        echo "okay";
    }
    else {
        echo "nonono." . $queryFaiCompito;
    }