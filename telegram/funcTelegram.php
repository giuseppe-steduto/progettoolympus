<?php
    $tokenBot = "1165566785:AAGU5kCjvZ5iDWMUCNE8GRl46YrnHk1l-kA";

    function inviaMessaggio($chat_id, $messaggio) {
        $messaggio = urlencode($messaggio);
        $r = file_get_contents("https://api.telegram.org/bot" . $GLOBALS["tokenBot"] . "/sendMessage?chat_id=$chat_id&text=$messaggio&parse_mode=html");
        $response = json_decode($r);
        if($response->{'ok'}) {
            return true;
        }
        else {
            return $response;
        }
    }

    function getMe() {
        //Fai la richiesta HTTP al bot Telegram per vedere se è tutto okay
        $r = file_get_contents("https://api.telegram.org/bot" . $GLOBALS["tokenBot"] . "/getMe");
        $response = json_decode($r);
        if($response->{'ok'}) {
            return true;
        }
        return false;
    }

    function setWebhook($url) {
        $r = file_get_contents("https://api.telegram.org/bot" . $GLOBALS["tokenBot"] . "/setWebhook?url=$url");
        $response = json_decode($r);
        if($response->{'ok'}) {
            return "Todo bien";
        }
        return $response->{'description'};
    }

    function logizza($messaggio) {
        $logTelegram = fopen("../logs/telegram.log", "a+");
        fwrite($logTelegram, $messaggio . "\n");
        fclose($logTelegram);
    }

    function controllaRegistrato($con, $chatId) {
        $query = "SELECT * FROM utenti WHERE telegramchatid = $chatId";
        $res = mysqli_query($con, $query);
        if(mysqli_num_rows($res) == 0) {
            return false;
        }
        return true;
    }

    function generaCompiti($con, $chatId) {
        $tmp = mysqli_query($con, "SELECT idutente FROM utenti WHERE telegramchatid = $chatId");
        $tmp2 = mysqli_fetch_array($tmp);
        $idutente = $tmp2["idutente"];

        $query = "SELECT dataconsegna, compito, nome FROM diario, materie WHERE diario.codicemateria = materie.codicemateria AND idcompito NOT IN ( SELECT idcompito FROM compitifatti, utenti WHERE compitifatti.idutente = utenti.idutente AND utenti.telegramchatid = $chatId) AND (privato = 0 OR (privato = 1 AND idutente = $idutente)) ORDER BY dataconsegna";
        $compiti = mysqli_query($con, $query);
        $dataTmp = "";
        $testo = "";
        $isPrimoCompito = true;
        while($compito = mysqli_fetch_array($compiti)) {
            if($compito["dataconsegna"] != $dataTmp) {
                $data = date_create($compito["dataconsegna"]);
                if(!$isPrimoCompito) {
                    $testo .= "\n\n";
                }
                $isPrimoCompito = false;
                $testo .= "<em>Compiti per " . date_format($data, "D d/m/Y") . "</em>\n";
                $dataTmp = $compito["dataconsegna"];
            }
            $testo .= "<strong>" . $compito["nome"] . ":</strong> " . $compito["compito"] . "\n";
        }

        return $testo;
    }
