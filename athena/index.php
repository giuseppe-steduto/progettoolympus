<html data-settore="athena">
    <head>
        <title>Appunti della 5Ai</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="dodgerblue">
        <meta http-equiv="Cache-control" content="no-cache">
        <!--Meta tag per iOS-->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta name="apple-mobile-web-app-title" content="Progetto Olympus">
        <link rel="apple-touch-icon" href="../res/logo.png">
        <link rel = "manifest" href = "../manifest.json" />
        <link rel="icon" href="../res/logo32.png" />
        <link rel = "stylesheet" href = "../style/elenco.css" />
        <script src = "../script/homeAthena.js"></script>
    </head>
    <body>
        <?php
            function numeroDownload($c, $a) {
                $q = "SELECT COUNT(*) AS ndownload FROM downloadappunti WHERE idappunto = " . $a["idappunto"] . " GROUP BY idappunto";
                $ndownload = mysqli_query($c, $q);
                $tmp = mysqli_fetch_array($ndownload);
                if(mysqli_num_rows($ndownload) == 0) {
                    return 0;
                }
                return $tmp[0];
            }

            include "../base.php";
            session_start();
            controllaHttps();

            if(!isset($_SESSION["utente"])) {
                esci();
                exit();
            }
            generaTestata();
            generaPulsanteTornaHome();
            //Controllo form
            $in_materie = $_POST["materia"];
            if(!isset($in_materie)) {
            	$in_materie = "%%";
            }
            $in_autore = $_POST["autore"];
            if(!isset($in_autore)) {
            	$in_autore = "%%";
            }
            $in_titolo = $_POST["titolo"];
            $in_area = $_POST["macroarea"];
            if($in_area != "%%" && $in_area != "") {
              $query = "SELECT * FROM appunti WHERE titolo LIKE '%$in_titolo%' AND codicemateria LIKE '$in_materie' AND idutente LIKE '$in_autore' AND idappunto IN (SELECT DISTINCT idappunto FROM collegamento WHERE codicemacroarea LIKE '%$in_area%') ORDER BY datacaricamento DESC";
            }
            else {
              $query = "SELECT * FROM appunti WHERE titolo LIKE '%$in_titolo%' AND codicemateria LIKE '$in_materie' AND idutente LIKE '$in_autore'  ORDER BY datacaricamento DESC";
            }
            $app = mysqli_query($con, $query);

            echo "<a href = 'inserisciAppunto.php' id = 'collegamentoAppunti'>Inserisci i tuoi appunti</a><br />";

            echo "<form action = '#' method = 'post'>";
            //Ricerca titolo
            echo "Titolo: ";
            echo "<input type = 'text' name = 'titolo' /> <br />";

            //Ricerca materie
            echo "Materia: ";
            $queryMaterie = "SELECT * FROM materie";
            $materie = mysqli_query($con, $queryMaterie) or die("Errore nell'accesso alle materie!");
            echo "<select id = 'materia' name = 'materia'>";
            echo "<option value = '%%'selected></option>";
            while($materia = mysqli_fetch_array($materie)) {
                $nome = $materia["nome"];
                $cod = $materia["codicemateria"];
                echo "<option value = '$cod'>$nome</option>";
            }
            echo "</select><br />";

            //Ricerca macroaree
            echo "Macroarea:";
            $queryMaterie = "SELECT * FROM macroaree";
            $macroaree = mysqli_query($con, $queryMaterie) or die("Errore nell'accesso alle macroaree!");
            echo "<select id = 'macroarea' name = 'macroarea'>";
            echo "<option value = '%%' selected></option>";
            while($macroarea = mysqli_fetch_array($macroaree)) {
                $nome = $macroarea["nome"];
                $cod = $macroarea["codicemacroarea"];
                echo "<option value = '$cod'>$nome</option>";
            }
            echo "</select><br />";

            //Ricerca autore
            echo "Autore:";
            $queryUtenti = "SELECT * FROM utenti";
            $utenti = mysqli_query($con, $queryUtenti) or die("Errore nell'accesso agli utenti!");
            echo "<select id = 'autore' name = 'autore'>";
            echo "<option value = '%%' selected></option>";
            while($user = mysqli_fetch_array($utenti)) {
                $nome = $user["nome"];
                $cod = $user["idutente"];
                echo "<option value = '$cod'>$nome</option>";
            }
            echo "</select><br />";

            echo "<input type = 'submit' value = 'Cerca' name = 'bottoneCerca' /></form>";

            echo "<p style = 'width:100%; text-align:center;'>Numero di risultati: " . mysqli_num_rows($app) . "</p>";
            //Mostra tutti gli appunti
            echo "<table border = 1><tr>";
            echo "<th>Titolo</th>";
            echo "<th>Materia</th>";
            echo "<th>Autore</th>";
            echo "<th>Data</th>";
            echo "<th>Macroaree</th>";
            echo "<th>Azioni</th>";
            echo "<th>Download</th>";
            echo "</tr>";
            while($appunto = mysqli_fetch_array($app)) {
                //Visualizza la riga della tabella
                $idappunto = $appunto["idappunto"];
                $materia = mysqli_fetch_array(mysqli_query($con, "SELECT nome FROM materie WHERE codicemateria = " . $appunto["codicemateria"]));
                $autore = mysqli_fetch_array(mysqli_query($con, "SELECT nome, idutente FROM utenti WHERE idutente = " . $appunto["idutente"]));
                $macroaree = mysqli_query($con, "SELECT nome FROM macroaree WHERE codicemacroaree = " . $appunto["codicemateria"]);
                echo "<tr>";
                echo "<td><a href = './scarica.php?id=" . $idappunto . "' title = '" . $appunto["testo"] . "'>" . $appunto["titolo"] . "</a></td>";
                echo "<td>" . $materia[0] . "</td>";
                echo "<td>" . $autore["nome"] . "</td>";
                echo "<td>" .  date('d-m-Y', strtotime($appunto["datacaricamento"])) . "</td>";
                echo "<td>" . estraiMacroaree($con, $appunto) . "</td>";
                echo "<td>";
                    //Colonna delle possibili azioni
                    if($autore["idutente"] == $_SESSION["idutente"] || $_SESSION["idutente"] == 5) { //Azioni permesse solo all'autore degli appunti e all'amministratore
                        echo "<a href = 'modificaAppunto.php?id=$idappunto'><span class='material-icons'>create</span></a>";
                        echo "<span class='material-icons' onclick = eliminaAppunto($idappunto)>delete</span></a>";
                        echo "<a href = 'insights.php?id=$idappunto'><span class='material-icons'>bar_chart</span></a>";
                    }                         
                echo "</td>"; 
                echo "<td>" . numeroDownload($con, $appunto) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        ?>

        <!--Bottone "torna su"-->
        <div onclick="topFunction()" id="GoToTop" title="Torna su">
            <span class = "material-icons">expand_less</span>
        </div>

        <div id = "endpoint"></div>
        <script>
            function topFunction() {
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            }
            mybutton = document.getElementById("GoToTop");
            window.onscroll = mostraBottone;
            function mostraBottone() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    mybutton.style.transform = "rotate(0)";
                    mybutton.style.right = "5vw";
                } else {
                    mybutton.style.transform = "rotate(1080deg)";
                    mybutton.style.right = "-20vw";
                }
            }
        </script>
    </body>
</html>
