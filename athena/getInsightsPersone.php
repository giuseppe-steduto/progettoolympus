<?php
    include "../base.php";
    session_start();
    
    $idappunto = $_GET["id"];
    
    $queryAppunto = "SELECT * FROM appunti WHERE idappunto = $idappunto";
    $tmp = mysqli_query($con, $queryAppunto);
    if(mysqli_num_rows($tmp) == 0) {
        //Redirect alla pagina principale. Qualcuno ha provato a modificare l'id dall'url e ne ha messo uno inesistente. Che divertente.
        $avviso = "Non fare birbonate, birbantello!";
        echo "1";
        exit();
    }
    $appunto = mysqli_fetch_array($tmp);
    if($_SESSION["idutente"] != $appunto["idutente"] && $_SESSION["idutente"] != 5) {
        //Redirect alla pagina principale. Tentano di modificare un appunto senza permesso.
        $avviso = "Hey, non hai i permessi per fare questa cosa, birbantello!";
        echo "1";
        exit();
    }
    
    //Controlli superati
    $_SESSION["idappunto"] = $idappunto;
    $queryDownload = "SELECT COUNT(*) AS ndownload, nome, titolo FROM utenti u, downloadappunti d, appunti a WHERE d.idappunto = $idappunto AND d.idutente = u.idutente AND a.idappunto = d.idappunto GROUP BY nome, titolo";
    $tmpDownload = mysqli_query($con, $queryDownload);
    
    $persone = array();
    while($tizioDownload = mysqli_fetch_assoc($tmpDownload)) {
        array_push($persone, $tizioDownload);
    }
    
    echo json_encode($persone);
?>