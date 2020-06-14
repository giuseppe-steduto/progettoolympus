<html data-settore="delphi">
    <head>
        <title>Progetto Olympus - Delphi</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="dodgerblue">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta content="8751899160-bmc7qpi981l19htokhlq8pdoc8g39b7g.apps.googleusercontent.com"
              name="google-signin-client_id">
        <!--Meta tag per iOS-->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta name="apple-mobile-web-app-title" content="MIIDM App">
        <link rel="apple-touch-icon" href="../res/logo.png">
        <link rel = "manifest" href = "../manifest.json" />
        <link rel="icon" href="../res/logo32.png" />
        <link rel = "stylesheet" href = "../style/mathematicaIndex.css" />
        <script src = "../script/mathematica.js"></script>
    </head>
    <body>
    <?php
        include "../base.php";
        session_start();

        if(!isset($_SESSION["utente"])) {
            esci();
            exit();
        }
        generaTestata();
        generaPulsanteTornaHome();
    ?>
    <h1>Delphi</h1>
    <div id = "inputContainer">
        <form method = "post" action = "elaboraRisultati.php">
            <input type = "text" id = "inputText"  name = "query" placeholder = "Cosa vuoi sapere?" /><br />
            <input type="submit" id="cercaMatematica" value="Vai" />
        </form>
    </div>

    <section id = "indicazioniDelphi">
        <h3>Istruzioni</h3>
        <div>
            L'oracolo di Delfi ti dà il benvenuto!<br />
            Questo calcolatore usa <strong>Wolfram Alpha</strong> per risponderti. Può risponderti a domande sui campi
            più disparati, dalla chimica alla geografia, passando per economia, fisica e ingegneria.<br>
            Devi scrivere in <strong>inglese</strong> per usarlo, eccetto che per domande di matematica: per quelle,
            infatti, un calcolatore le tradurrà automaticamente al tuo posto!
        </div>
    </section>
    </body>
</html>
