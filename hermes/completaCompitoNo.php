<?php
include "../base.php";
session_start();
controlloLogin();

$idcompito = $_GET["id"];
$idutente = $_SESSION["idutente"];

$queryFaiCompito = "DELETE FROM `compitifatti` WHERE idutente=$idutente AND idcompito=$idcompito";
if(eseguiQuery($con, $queryFaiCompito)) {
    echo "okay";
}
else {
    echo "nonono." + $queryFaiCompito;
}
