<?php
    include "../base.php";
    $valori = file_get_contents("php://input");
    $peppe = json_decode($valori);
    $nomeTabella = $peppe->nomeTabella;
    switch ($nomeTabella) {
        case "materie":
            $nomeId = "codicemateria";
            break;
        case "macroaree":
            $nomeId = "codicemacroarea";
            break;
        case "tipiappunto":
            $nomeId = "codicetipo";
            break;
        case "utenti":
            $nomeId = "idutente";
            break;
        default:
            echo "Errore";
            exit();
    }
    $query = "DELETE FROM $nomeTabella WHERE $nomeId = " . $peppe->id;

    if(eseguiQuery($con, $query)) {
        echo "ok";
    }
    else {
        echo "errore";
    }
