<?php
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    include("base.php");
    session_start();

    //Ottieni email
    $email = file_get_contents('php://input');
    if(!isset($email)) {
        exit("Email non inserita!");
    }

    //Ottieni IP
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    //Ottieni nome
    $utenteQuery = "SELECT nome FROM utenti WHERE email LIKE '$email'";
    $utenti = mysqli_query($con, $utenteQuery);
    while($u = mysqli_fetch_array($utenti)) {
        $nome = $u["nome"];
    }

    //Genera token e inseriscilo nel database
    $token = hash('sha256', generateRandomString());
    $mysqltime = date();
    $query = "INSERT INTO `tokenreset`(`email`, `token`, `orarichiesta`, nome) VALUES ('$email', '$token', NOW(), '$nome')";
    if(!mysqli_query($con, $query)) {
        exit("Mail non inviata!");
    }

    //Genera e invia email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <giuseppe@steduto.it>' . "\r\n";
    $corpoMessaggio = "<h3>Gentile <strong>$nome,</strong></h3>abbiamo ricevuto una richiesta per il reset della password per l'accesso al database degli appunti.";
    $corpoMessaggio .= "<br />La richiesta è stata inoltrata da questo IP: <strong>$ip</strong>";
    $corpoMessaggio .= "<br /><br /><center><a href = 'https://progettoolympus.ddns.net/resetPass.php?token=$token'>Reset password</a></center><br /><br />";
    $corpoMessaggio .= "Il link è valido solo per le prossime <strong>2 ore</strong>.<br />Se non sei stato tu a richiedere il reset della password, per favore ignora questa mail.<br />";
    $oggetto = "Richiesta reset password per l'accesso al database di appunti";
    if(mail($email, $oggetto, $corpoMessaggio, $headers))
      echo "Mail inviata correttamente!";
    else
      echo "ERRORE!";
?>
