<html data-settore="amministrazione">
<?php
    session_start();
    include("base.php");
    $tokenInserito = $_GET["token"];
    $queryToken = "SELECT * FROM tokenreset WHERE token LIKE '$tokenInserito'";
    $risultati = eseguiQuery($con, $queryToken);
    if(mysqli_num_rows($risultati) <= 0) {
        exit("Token invalido. Reset password non permesso.");
    }
    while($riga = mysqli_fetch_array($risultati)) {
        $_SESSION["utente"] = $riga["nome"];
        $orarichiesta = strtotime($riga["orarichiesta"]);
    }
    //Controllo sull'ora della richiesta
    if(time() - $orarichiesta > 120 * 60) {
        exit("Token scaduto. Richiedine un altro e resetta la tua password entro due ore.");
    }

    echo "<head><title>Password reset</title><link rel = 'stylesheet' href = 'style/elenco.css' /><meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='theme-color' content='dodgerblue'></head>";
    generaTestata();

    echo "<form action = 'passwordReset.php' method = 'post' style = 'margin-top: 9vh;'>";
    echo "<input type = 'password' name = 'password' placeholder = 'Inserisci la nuova password' />";
    echo "<input type = 'password' placeholder = 'Re-inserisci la nuova password' />";
    echo "<input type = 'text' hidden name = 'token' value = '$tokenInserito' />";
    echo "<input type = 'submit' value = 'Aggiorna' />";
    echo "</form>";
?>
</html>
