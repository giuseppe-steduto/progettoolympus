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
    $query = "INSERT INTO $nomeTabella SET";
    foreach ($peppe AS $proprieta => $valore) {
        if($proprieta != "nomeTabella") {
            $query .= " $proprieta='$valore',";
        }
    }
    $query = substr($query, 0, -1); //Toglie l'ultima virgola di troppo
    if(mysqli_query($con, $query)) {
        echo "ok";
    }
    else {
        echo "errore" . mysqli_error($con) . " - $query";
    }