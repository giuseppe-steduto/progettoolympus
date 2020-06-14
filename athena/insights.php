<!DOCTYPE html>
<html lang="it" dir="ltr" data-settore="athena">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "../style/insights.css" />
        <title>Athena - Insights</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src = "../script/insights.js"></script>
    </head>
    <body>
        <?php
            include "../base.php";
            session_start();
            generaTestata();
            $idappunto = $_GET["id"];
            if(!isProprietarioOf($con, $_SESSION["idutente"], $idappunto) && $_SESSION["idutente"] != 5) {
                tornaAHome("Non sei autorizzato a vedere gli insights di questo appunto!");
            }
        ?>
        <h3 id = "titoloAppunto"></h3>
        
        <section class = 'nomiDownloadari'>
            <h4>Chi ha scaricato questo appunto</h4>
            <table id = "tabellaPersone">
                <tr><th>Utente</th><th>Numero download</th></tr>
            </table>
        </section>
        <br />
        <div id = "graficoDownloadNelTempo"></div>

        <?php
            //Visualizza tabella delle modifiche
            $query = "SELECT * FROM appuntivecchi WHERE idappunto = $idappunto ORDER BY datamodifica DESC";
            $appunti = eseguiQuery($con, $query);
            if(mysqli_num_rows($appunti) == 0) { //Non ci sono modifiche
                exit();
            }

            echo "<h4>Modifiche all'appunto</h4>";
            echo "<table border = 1 class = 'responsive'><tr>";
            echo "<th>Titolo</th>";
            echo "<th>Materia</th>";
            echo "<th>Macroaree</th>";
            echo "<th>Data modifica</th>";
            echo "</tr>";
            while($appunto = mysqli_fetch_array($appunti)) {
                //Visualizza la riga della tabella
                $idappunto = $appunto["idappunto"];
                $idmodifica = $appunto["idmodifica"];
                $materia = mysqli_fetch_array(mysqli_query($con, "SELECT nome FROM materie WHERE codicemateria = " . $appunto["codicemateria"]));
                $macroaree = mysqli_query($con, "SELECT nome FROM macroaree WHERE codicemacroaree = " . $appunto["codicemateria"]);
                echo "<tr>";
                echo "<td><a href = './uploads/" . $appunto['link'] . "' title = '" . $appunto["testo"] . "'>" . $appunto["titolo"] . "</a></td>";
                echo "<td>" . $materia[0] . "</td>";
                echo "<td>" . estraiMacroaree($con, $appunto) . "</td>";
                echo "<td>" .  date('D d/m/Y', strtotime($appunto["datamodifica"])) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </body>
</html>