<?php
    include "../base.php";
    session_start();
    
    $idappunto = $_GET["id"];
    $idutente = $_SESSION["idutente"];
    
    $queryElimina = "DELETE FROM appunti WHERE idappunto = $idappunto ";
    if($idutente != 5) {
        $queryElimina .= "AND idutente = $idutente";
    }
    mysqli_query($con, $queryElimina);
    
    if(mysqli_affected_rows($con) == 0) {
        $avviso = "Non hai il permesso per eliminare quella cosa, giondolo.";
        header("Location: index.php?avviso=" . $avviso);
    }
    header("Location: index.php");