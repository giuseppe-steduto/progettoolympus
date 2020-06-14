<?php
    include "../base.php";
    include "../telegram/funcTelegram.php";
    session_start();
    //Esegui il PHP solo se viene inviato con il POST
    $messaggio = $_POST["corponotifica"];
    if(isset($messaggio)) {
        $tantiUtenti = mysqli_query($con, "SELECT nome, telegramchatid FROM utenti WHERE NOT telegramchatid IS NULL");
        while($utente = mysqli_fetch_array($tantiUtenti)) {
            inviaMessaggio($utente["telegramchatid"], $messaggio);
        }
    }
?>
<html data-settore="amministrazione">
    <head>
        <title>Invia notifiche</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="dodgerblue">
        <meta http-equiv="Cache-control" content="no-cache">
        <!--Meta tag per iOS-->
        <link rel = "stylesheet" href = "../style/inserisci.css" />
    </head>
    <body>
        <?php generaTestata() ?>
        <h1>Invia un messaggio</h1>
        <form method = "post" action = "#">
            <label for = "corponotifica">Testo (notazione HTML):</label><br />
            <textarea name = "corponotifica" id = "corponotifica" rows="10"></textarea>

            <button id = "bottone_invia" onclick = "this.parentElement.submit()" style = "margin-bottom: 2rem">Invia!</button>
            <br />
        </form>
    </body>
