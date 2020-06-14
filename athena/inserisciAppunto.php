<!DOCTYPE html>
<html lang="it" dir="ltr" data-settore="athena">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "../style/inserisci.css" />
        <title>Athena - Carica un tuo appunto</title>
        <?php
        //Connessione al database
        //Salvataggio dell'utente con la sessione PHP nella variabile $utente
        include "../base.php";
        session_start();
        if(!isset($_SESSION["utente"])) {
            echo "Login non effettuato, pagina non visualizzabile!";
            exit();
        }
        generaTestata();
        ?>

        <style>
            h3 {
                margin-top: 1.2rem;
                margin-bottom: 0.3rem;
            }
        </style>
    </head>
    <body>
        <h1>Carica i tuoi appunti!</h1>
        <form id = "infoAppunto" action = "upload.php" method = "post" enctype="multipart/form-data">
            <!--Titolo appunto-->
            <h3>Titolo:</h3>
            <input type = "text" name = "titolo" id = "titolo" required/><br />

            <!--Testo appunto-->
            <h3>Descrizione (facoltativa):</h3>
            <textarea name = "testo" id = "testo"></textarea><br />

            <!--Materia-->
            <h3>Materia:</h3>
            <?php
                $queryMaterie = "SELECT * FROM materie";
                $materie = mysqli_query($con, $queryMaterie) or die("Errore nell'accesso alle materie!");
                echo "<select id = 'materia' name = 'materia' required>";
                while($materia = mysqli_fetch_array($materie)) {
                    $nome = $materia["nome"];
                    $cod = $materia["codicemateria"];
                    echo "<option value = '$cod'>$nome</option>";
                }
                echo "</select>";
            ?>
            <br />

            <!--Tipo appunto-->
            <h3>Tipo appunto:</h3>
            <?php
                $queryTipi = "SELECT * FROM tipiappunto";
                $tipi = mysqli_query($con, $queryTipi) or die("Errore nell'accesso ai tipi di appunto!");
                while($tipo = mysqli_fetch_array($tipi)) {
                    $nome = $tipo["nome"];
                    $cod = $tipo["codicetipo"];
                    echo "<input type = 'radio' required name = 'tipo' value = '$cod' id = '$cod' />$nome<br />";
                }
            ?>

            <h3>Macroaree:</h3>
            <!--Macroaree-->
            <?php
                session_start();
                $queryAree = "SELECT * FROM macroaree";
                $aree = mysqli_query($con, $queryAree) or die("Errore nell'accesso alle macroaree!");
                while($area = mysqli_fetch_array($aree)) {
                    $nome = $area["nome"];
                    $cod = $area["codicemacroarea"];
                    echo "<input type = 'checkbox' name = 'macroarea[]' value = '$cod' id = '$cod' />$nome<br />";
                }
            ?>
            <br />
            <input type = "text" hidden name = "linkFile" id = "inputLinkFile" />
        </form>

        <div id = "divInserimentoFile">
            <h3>File appunti (dal computer o da Drive):</h3>
            <input type = "file" id = "file" name = "file" onchange = "caricaFileAsincrono()"/>
            <progress id = "progress" max = "100" value = "0" ></progress>
            <br /><br />
            <button id = "bottone_invia" onclick = "inviaForm()">Carica!</button>
        </div>
        <script src = "../script/caricaFileAsincrono.js?version=2"></script>
    </body>
</html>
