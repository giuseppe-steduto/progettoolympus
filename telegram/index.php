<?php
    require_once "funcTelegram.php";
    require_once "../base.php";
    $request = file_get_contents( 'php://input' );
    #          ↑↑↑↑
    $request = json_decode($request, TRUE );

    if(!$request) {
        logizza("Quello che è arrivato al bot non è una richiesta JSON valida.");
        exit();
    }
    elseif(!isset($request['update_id']) || !isset($request['message']))
    {
        logizza("Quello che è arrivato al bot non è un messaggio.");
        exit();
    }
    $chatId = $request['message']['chat']['id'];
    $message = $request['message']['text'];

    //Controllo che sia un comando
    if($message[0] == "/") {
        switch ($message) {
            case "/start":
                $messaggio = "Ciao! Per sapere cosa puoi fare, digita il comando /help.";
                break;
            case "/help":
                $messaggio = "Benvenuto nel bot del Progetto Olympus! Se è la prima volta che ti colleghi, devi associare il tuo account telegram a quello del Progetto Olympus.";
                $messaggio .= "\nPer farlo, digita le tue credenziali separate da uno spazio (' '), così: <pre>email password</pre>";
                $messaggio .= "\n\nUna volta che ti sei registrato, potrai usare questi comandi:";
                $messaggio .= "\n<b>/hermes</b>: ti uscirà la lista di tutti i compiti che devi ancora fare.";
                $messaggio .= "\n\nIn futuro le possibilità offerte da questo bot verranno estese. Inizia registrandoti al servizio!";
                break;
            case "/hermes":
                if(controllaRegistrato($con, $chatId)) {
                    $messaggio = generaCompiti($con, $chatId);
                }
                else {
                    $messaggio = "Puoi eseguire questo comando solo dopo esserti registrato! Invia le tue credenziali così: email password.";
                }
                break;
            default:
                $messaggio = "Comando non valido.";
        }
    }
    //Se non lo è, vuol dire che sono le credenziali
    else {
        $credenziali = explode(" ", $message);
        if(controllaCredenziali($con, $credenziali[0], $credenziali[1])) {
            if(inserisciChatId($con, $credenziali[0], $chatId)) {
                $messaggio = "Credenziali corrette! Sei stato registrato, ora puoi usare il bot!";
            }
            else {
                $messaggio = "Credenziali corrette, ma non sono riuscito a registrarti! Contatta l'amministratore.";
            }
        }
        else {
            $messaggio = "Credenziali sbagliate o formato di inserimento errato!";
        }
    }
    inviaMessaggio($chatId, $messaggio);