<!DOCTYPE html>
<html lang="it" dir="ltr" data-settore="athena">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "../style/inserisci.css" />
        <link rel="icon" href="../res/logo32.png" />
        <title>Athena - Modifica l'appunto</title>
        <?php
            include "../base.php";
            session_start();
            
            $idappunto = $_GET["id"];
            
            $queryAppunto = "SELECT * FROM appunti WHERE idappunto = $idappunto";
            $tmp = mysqli_query($con, $queryAppunto);
            if(mysqli_num_rows($tmp) == 0) {
                //Redirect alla pagina principale. Qualcuno ha provato a modificare l'id dall'url e ne ha messo uno inesistente. Che divertente.
                $avviso = "Non fare birbonate, birbantello!";
                header("Location: login.html?avviso=$avviso");
                exit();
            }
            $appunto = mysqli_fetch_array($tmp);
            if($_SESSION["idutente"] != $appunto["idutente"] && $_SESSION["idutente"] != 5) {
                //Redirect alla pagina principale. Tentano di modificare un appunto senza permesso.
                $avviso = "Hey, non hai i permessi per fare questa cosa, birbantello!";
                header("Location: login.html?avviso=$avviso");
                exit();
            }
            
            //Bene, ora puoi stampare la pagina della modifica.
            generaTestata();
            $_SESSION["idappunto"] = $idappunto;
        ?>

        <style>
            h3 {
                margin-top: 1.2rem;
                margin-bottom: 0.3rem;
            }
        </style>
    </head>
    <body>
        <h1>Modifica appunto!</h1>
        <form id = "infoAppunto" action = "modificaDaForm.php" method = "post" enctype="multipart/form-data">
            <!--Titolo appunto-->
            <h3>Titolo:</h3>
            <input type = "text" name = "titolo" id = "titolo"  value = <?php echo "\"". $appunto["titolo"] . "\""; ?> required><br />

            <!--Testo appunto-->
            <h3>Descrizione (facoltativa):</h3>
            <textarea name = "testo" id = "testo" ><?php echo $appunto["testo"]; ?></textarea><br />

            <!--Materia-->
            <h3>Materia:</h3>
            <?php
                $queryMaterie = "SELECT * FROM materie";
                $materie = mysqli_query($con, $queryMaterie) or die("Errore nell'accesso alle materie!");
                echo "<select id = 'materia' name = 'materia' required>";
                while($materia = mysqli_fetch_array($materie)) {
                    $nome = $materia["nome"];
                    $cod = $materia["codicemateria"];
                    echo "<option value = '$cod'";
                    if($cod == $appunto["codicemateria"]) {
                        echo " selected";
                    }
                    echo ">$nome</option>";
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
                    echo "<input type = 'radio' required name = 'tipo' value = '$cod' id = '$cod' ";
                    if($cod == $appunto["tipo"]) {
                        echo " checked";
                    }
                    echo "/>$nome<br />";
                }
            ?>

            <h3>Macroaree:</h3>
            <!--Macroaree-->
            <?php
                session_start();
                $queryAree = "SELECT * FROM macroaree";
                $queryAreeAppunto = "SELECT codicemacroarea FROM collegamento WHERE idappunto = $idappunto";
                $aree = mysqli_query($con, $queryAree) or die("Errore nell'accesso alle macroaree!");
                $areeAppunto = mysqli_query($con, $queryAreeAppunto) or die("Errore nell'accesso alle macroaree!");
                while($area = mysqli_fetch_array($aree)) {
                    $nome = $area["nome"];
                    $cod = $area["codicemacroarea"];
                    echo "<input type = 'checkbox' name = 'macroarea[]' value = '$cod' id = '$cod' ";
                    while($areaAppunto = mysqli_fetch_array($areeAppunto)) {
                        if($cod == $areaAppunto["codicemacroarea"]) 
                            echo " checked";
                    }
                    mysqli_data_seek($areeAppunto, 0);
                    echo " />$nome<br />";
                }
            ?>
            <br />
            <input type = "text" hidden name = "linkFile" id = "inputLinkFile" value = <?php echo "'". $appunto["link"] . "'"; ?>></input>
        </form>

        <div id = "divInserimentoFile">
            <h3>File appunti (se vuoi sostituirlo, caricane un altro):</h3>
            <input type = "file" id = "file" name = "file" onchange = "caricaFileAsincrono()"/>
            <progress id = "progress" max = "100" value = "100" ></progress>
            <br /><br />
            <button id = "bottone_invia" onclick = "inviaForm()">Aggiorna!</button>
        </div>
        <script src = "../script/caricaFileAsincrono.js?version=2"></script>
        <script>caricato = true;</script>
    </body>
</html>
