<?php
    use Minishlink\WebPush\WebPush;
    use Minishlink\WebPush\Subscription;
    include "base.php";

    $iscrizioneStringa = file_get_contents("php://input");
    $iscrizione = json_decode($iscrizioneStringa, true);

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            $query = "UPDATE utenti SET parametrinotifiche = '" . escapa($con, $iscrizioneStringa) . "' WHERE idutente = " . $_SESSION["idutente"];
            if(mysqli_query($con, $query)) {
                echo 'ok';
            }
            else {
                echo 'errore' . $query;
            }
            break;
        case 'DELETE':
            // delete the subscription corresponding to the endpoint
            break;
        default:
            echo "Error: method not handled";
            return;
    }