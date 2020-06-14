<html data-settore="hermes">
<head>
    <title>Appunti della 5Ai</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="dodgerblue">
    <meta name = "lang" content = "it">
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
    <link rel = "stylesheet" href = "../style/diario.css" />
    <!--Icone-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src = "../script/diario.js"></script>
    <?php
    include "../base.php";
    session_start();
    controllaHttps();
    setlocale(LC_ALL, 'it_IT');

    if(!isset($_SESSION["utente"])) {
        esci();
        exit();
    }
    generaTestata();
    generaPulsanteTornaHome();

    function generaRigaCompito($compito, $controllo=true) {
        echo "<tr id = " . $compito["idcompito"] . ">";
        //Icona per l'indicazione del privato e nome della materia del compito
        echo "<td>" . $compito["nomemateria"] . "</td>";

        //Testo del compito
        echo "<td>" . $compito["compito"] . "</td>";

        //Controllo data consegna
        if($compito["dataconsegna"] >= date("Y-m-d")) {
            $data = date_create($compito["dataconsegna"]);
            echo "<td>" . date_format($data, "D d/m/Y") . "</td>";
        }
        else {
            echo "<td class = 'compitoInRitardo'>In ritardo!</td>";
        }

        echo "<td>";
        //Pulsante per dire "L'HO FATTO"
        if($controllo)
            echo "<span class=\"material-icons\" onclick = \"eseguiCompito(this.parentNode.parentNode)\">done</span>";
        else
            echo "<span class=\"material-icons\" onclick = \"nonEseguiCompito(this.parentNode.parentNode)\">remove</span>";

        //Pulsante per l'eliminazione
        if($_SESSION["idutente"] == 5 || $_SESSION["idutente"] == $compito["idutente"]) {
            echo "<span class = 'material-icons' onclick='eliminaCompito(this.parentNode.parentNode)'>delete</span>";
        }

        //Lucchetto per i compiti privati
        if($compito["privato"] == 1) {
            echo "<span class = 'material-icons' title = 'Questo compito è privato: solo tu lo puoi vedere.'>lock</span>";
        }
        echo "</td>";
        echo "</tr>";
    }
    ?>
</head>
    <body>
        <div id = "modalInserimento">
            <form action = "inserisciCompito.php" method="post">
                <label for = "materia">Materia:</label>
                <?php
                    $queryMaterie = "SELECT * FROM materie";
                    $materie = eseguiQuery($con, $queryMaterie) or die("Errore nell'accesso alle materie!");
                    echo "<select id = 'materia' name = 'materia'>";
                    echo "<option value = '' selected></option>";
                    while($materia = mysqli_fetch_array($materie)) {
                        $nome = $materia["nome"];
                        $cod = $materia["codicemateria"];
                        echo "<option value = '$cod'>$nome</option>";
                    }
                    echo "</select>";
                ?>

                <label for = "descrizione">Descrizione:</label>
                <textarea id = "descrizione" name = "descrizione" rows="5"></textarea>

                <label for = "dataconsegna">Data consegna:</label>
                <input id = "dataconsegna" type = "date" name = "dataconsegna" />

                <input id = "isPrivato" type = "checkbox" name = "privato" />
                <label for = "isPrivato">Privato</label>

                <input id = "caricaCompiti" type = "submit" value="Aggiungi!" />
            </form>
        </div>
        <h1>Diario</h1>
        <h3>Compiti ancora da fare</h3>
        <table>
            <tr>
                <th>Materia</th>
                <th>Compito</th>
                <th>Data consegna</th>
                <th></th>
            </tr>
        <?php
            //Prendo i compiti da fare ancora (sia in ritardo che in orario)
            $queryCompiti = "SELECT m.nome as nomemateria, idcompito, compito, dataconsegna, privato, idutente FROM `diario` d, materie m WHERE (d.codicemateria = m.codicemateria ";
            $queryCompiti .= "AND idcompito NOT IN ";
            $queryCompiti .= "(SELECT DISTINCT idcompito FROM compitifatti WHERE idutente = " . $_SESSION["idutente"] . ")) ";
            $queryCompiti .= "AND (privato = 0 OR (idutente = ". $_SESSION["idutente"] . " AND privato = 1)) ";
            $queryCompiti .= "ORDER BY dataconsegna";
            $tmpCompiti = eseguiQuery($con, $queryCompiti);
            while($c = mysqli_fetch_array($tmpCompiti)) {
                generaRigaCompito($c);
            }
        ?>
        </table>
        <h3>Compiti già fatti</h3>
        <table>
            <tr>
                <th>Materia</th>
                <th>Compito</th>
                <th>Data consegna</th>
                <th></th>
            </tr>
        <?php
            //Prendo i compiti già fatti, con data di consegna non ancora arrivata
            $queryCompiti = "SELECT m.nome as nomemateria, idcompito, compito, dataconsegna, privato FROM `diario` d, materie m WHERE (d.codicemateria = m.codicemateria ";
            $queryCompiti .= "AND dataconsegna >= CURRENT_DATE() AND idcompito IN ";
            $queryCompiti .= "(SELECT DISTINCT idcompito FROM compitifatti WHERE idutente = " . $_SESSION["idutente"] . ")) ORDER BY dataconsegna";
            $tmpCompiti = eseguiQuery($con, $queryCompiti);
            while($c = mysqli_fetch_array($tmpCompiti)) {
                //Il false serve a dire di non generare l'ultima colonna
                generaRigaCompito($c, false);
            }
        ?>
            <span class="material-icons" id = "bottoneAggiungiAppunto" onclick="toggleModalInserimento()">
                add_circle
            </span>
    </body>
</html>
