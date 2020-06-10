<!DOCTYPE html>
<html lang="it" dir="ltr" <?php
        session_start();
        if($_SESSION["temascuro"] == 1) {echo "data-theme='dark'";}
    ?>>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="dodgerblue">
        <!--Meta tag per iOS-->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="MIIDM App">
        <link rel="apple-touch-icon" href="res/logo.png">
        <link rel = "manifest" href = "manifest.json" />
        <!--TODO Aggiungere splash screen per iOS-->
        <title>Login - appunti 5Ai</title>
        <link rel = "stylesheet" href = "style/account.css" />
        <script src = "./script/account.js"></script>
    </head>
    <body onload = "toggleCheckbox(document.getElementById('notificheAttivate')); toggleTemaScuro(document.getElementById('temaScuroAttivato'))">
        <?php
            include "base.php";
            session_start();
        ?>
        <form method = "post" action = "modificaAccount.php">
            <h2 class = "titleImpostazioniAccount">Impostazioni account</h2>
            <label for = "nomeAccount">Nome:</label>
            <input type = "text" id = "nomeAccount" name = "nomeAccount" value = <?php echo "\"" . $_SESSION["utente"] . "\"" ?> /><br />
            <label for = "mailAccount">E-mail:</label>
            <input type = "text" id = "mailAccount" name = "mailAccount" value = <?php echo "\"" . $_SESSION["emailutente"] . "\"" ?> /><br />

            <div class = "wrapperCheckbox">
                <label for = "notificheAttivate" id = "labelNotifiche">Notifiche push</label>
                <input type = "checkbox" id = "notificheAttivate" name = "notificheAttivate" value = "1"
                    <?php
                        if($_SESSION["notifiche"] == 1) {
                            echo " checked ";
                        }
                    ?>
                    onchange = "toggleCheckbox(this)"
                    onclick = "registraNotifiche(this.checked)"
                />
            </div>
            <div class = "wrapperCheckbox">
                <label for = "temaScuroAttivato" id = "labelTemaScuro">Tema scuro</label>
                <input type = "checkbox" id = "temaScuroAttivato" name = "temaScuroAttivato" value = "1"
                    <?php
                    if($_SESSION["temascuro"] == 1) {
                        echo " checked ";
                    }
                    ?>
                     onchange = "toggleTemaScuro(this)"
                /><br />
            </div>

            <?php echo "<p id = 'cambiaPassword' onclick = 'inviaRichiestaChangePassword(\"" . $_SESSION["emailutente"] . "\")'>Cambia password</p>"; ?>
            <p id = "eliminaAccount" onclick = "alert('No dai non me la sento di eliminare il tuo account')">Elimina il mio account :(</p>
            <div class = "bottoniSalvaAnnullaContainer">
                <input type = "submit" value = "Salva le modifiche!"></input>
                <div class = "annullaCambiamenti" onclick = "window.location.href = 'index.php'">Annulla</div>
            </div>
        </form>
    </body>
</html>
