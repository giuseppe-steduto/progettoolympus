<?php
    include("base.php");
    session_start();
    $pw = $_POST["password"];

    //Controllo del token
    $tokenInserito = $_POST["token"];
    $queryToken = "SELECT * FROM tokenreset WHERE token LIKE '$tokenInserito'";
    $risultati = mysqli_query($con, $queryToken);
    if(mysqli_num_rows($risultati) <= 0) {
        exit("Token invalido. Reset password non permesso.");
    }
    while($riga = mysqli_fetch_array($risultati)) {
        $email = $riga["email"];
    }
    $queryResetPassword = "UPDATE `utenti` SET `password`=MD5(MD5('$pw')) WHERE email LIKE '$email'";
    if(mysqli_query($con, $queryResetPassword)) {
        esci("Password resettata!");
    }
    else {
        esci("Reset fallito!");
    }
?>
