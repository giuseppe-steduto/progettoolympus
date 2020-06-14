<?php
    include "../base.php";
    session_start();
    if($_SESSION["idutente"] != 5) {
        esci("");
    }

    /**
     * @param $nomeTabella: Stringa con il nome della tabella
     * @param $arr: array (mysql) di cose da stampare
     * @param $campi: array (normale) in cui sono contenuti i campi mysql da estrarre
     * @param $nomi: array (normale) in cui sono contenuti i nomi per le intestazioni delle tabelle
     */
    function generaTabella($nomeTabella, $arr, $campi, $nomi) {
        echo "<table name = $nomeTabella>";

        //Stampa l'intestazione della tabella
        echo "<tr>";
        foreach ($nomi as $nome) {
            echo "<th>" . $nome . "</th>";
        }
        echo "<th>Azioni</th></tr>";
        while($e = mysqli_fetch_array($arr)) {
            $id = $e["id"];
            echo "<tr id = '$nomeTabella-$id'>";
            foreach ($campi as $campo) {
                echo "<td name = '$campo'>" . $e[$campo] . "</td>";
            }
            echo "<td>
                    <span class = 'material-icons' onclick='modificaCampo(this.parentNode.parentNode)'>edit</span>
                    <span class = 'material-icons' onclick='eliminaCampo(this.parentNode.parentNode)'>remove</span>
                  </td>";
            echo "</tr>";
        }
        echo sprintf("<tr id = '$nomeTabella' onclick='creaRigaPerAggiungere(this.parentElement)'>
                <td colspan='%d'>Aggiungi</td> 
              </tr>", sizeof($campi) + 1);
        echo "</table>";
    }
?>
<html data-settore="amministrazione">
    <head>
        <title>Pagina di amministrazione Olympus</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "../style/gestisci.css" />
        <script src = "../script/gestisci.js"></script>
    </head>
    <body>
    <?php
        generaTestata();
        generaPulsanteTornaHome();

        $queryMaterie = "SELECT nome, codicemateria AS id FROM materie";
        $queryMacroaree = "SELECT nome, codicemacroarea AS id FROM macroaree";
        $queryTipiAppunto = "SELECT nome, estensione, codicetipo AS id FROM tipiappunto";
        $queryUtenti = "SELECT nome, email, notifiche, idutente AS id FROM utenti";

        $materie = eseguiQuery($con, $queryMaterie);
        $macroaree = eseguiQuery($con, $queryMacroaree);
        $tipi = eseguiQuery($con, $queryTipiAppunto);
        $utenti = eseguiQuery($con, $queryUtenti);

        echo "<h3 onclick = 'toggleTabella(this)'>Materie 
                <span class = 'material-icons'>expand_more</span></h3>";
        generaTabella("materie", $materie, ['nome'], ['Nome']);

        echo "<h3 onclick = 'toggleTabella(this)'>Macroaree 
                <span class = 'material-icons'>expand_more</span></h3>";
        generaTabella("macroaree", $macroaree, ['nome'], ['Nome']);

        echo "<h3 onclick = 'toggleTabella(this)'>Tipi appunto 
                <span class = 'material-icons'>expand_more</span></h3>";
        generaTabella("tipiappunto", $tipi, ['nome', 'estensione'], ['Nome tipo', 'Estensione file']);

        echo "<h3 onclick = 'toggleTabella(this)'>Utenti 
                <span class = 'material-icons'>expand_more</span></h3>";
        generaTabella("utenti", $utenti, ['nome', 'email', 'notifiche'], ['Nome', 'Indirizzo e-mail', 'Notifiche abilitate']);

        echo "<h3><a href = 'inviaNotifiche.php'>Invia notifiche</a></h3>";
    ?>
    </body>
</html>
