<html data-settore="athena">
    <head>
        <title>Progetto Olympus</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="dodgerblue">
        <meta http-equiv="Cache-control" content="no-cache">
        <!--Meta tag per iOS-->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta name="apple-mobile-web-app-title" content="Progetto Olympus">
        <link rel="apple-touch-icon" href="res/logo.png">
        <link rel = "manifest" href = "manifest.json" />
        <link rel="icon" href="res/logo32.png" />
        <link rel = "stylesheet" href = "style/home.css" />
        <script src = "script/home.js"></script>
    </head>
    <body>
        <?php
            include "base.php";
            controlloLogin();
            controllaHttps();
            generaTestata(true);
        ?>
        <h1 id = "titoloProgetto">Progetto Olympus</h1>
        <div id = "containerSettori">
            <section class = "settore" onclick = "window.location.href='./athena/index.php'">
                <div class = "logoSettoreContainer">
                    <img src = "res/athenaLogo.svg" />
                </div>
                <p>Athena</p>
                <p>Una raccolta di tutti gli appunti che ti servono - e tanto altro!</p>
            </section>
            <section class = "settore" onclick = "window.location.href='./hermes/index.php'">
                <div class = "logoSettoreContainer">
                    <img src = "res/hermesLogo.svg" />
                </div>
                <p>Hermes</p>
                <p>Una lista personalizzabile di ciò che hai da fare</p>
            </section>
            <section class = "settore" onclick = "window.location.href='./delphi/index.php'">
                <div class = "logoSettoreContainer">
                    <img src = "res/delphiLogo.svg" />
                </div>
                <p>Delphi</p>
                <p>L'oracolo, che saprà rispondere a quello che hai da chiedergli</p>
            </section>
        </div>
