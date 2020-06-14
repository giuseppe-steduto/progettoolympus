<?php
    include "../base.php";
    session_start();
    controlloLogin();

    $materia = escapa($con, $_POST["materia"]);
    $descrizione = escapa($con, $_POST["descrizione"]);
    $dataconsegna = escapa($con, $_POST["dataconsegna"]);
    $idutente = $_SESSION["idutente"];
    $privato = escapa($con, $_POST["privato"]);

    //Trasformo il valore della checkbox in booleano
    if($privato == "on") {
        $privato = 1;
    }
    else {
        $privato = 0;
    }

    $queryAggiungiAppunto = "INSERT INTO diario(codicemateria, dataconsegna, compito, idutente, privato) VALUES($materia, '$dataconsegna', \"$descrizione\", $idutente, $privato)";
    mysqli_query($con, $queryAggiungiAppunto) or die("Impossibile aggiungere il nuovo compito." . $queryAggiungiAppunto);

    header("Location: index.php");
