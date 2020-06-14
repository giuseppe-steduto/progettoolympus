<?php
    session_start();

    //Sostituire con i dati corretti
    $hostname = "localhost";
    $utente = "nomeutente";
    $password = "********";
    $dbname = "nomedatabase";
    $con = mysqli_connect($hostname, $utente, $password, $dbname) or die("Errore nella connessione al database!" . mysqli_connect_errno());

    //Chiavi VAPID per le web push notification, sostituire con le proprie
    $applicationServerPublicKey = '********';
    $privateKey = "****";

    function generaTestata($isHome=false) {
        $utente = $_SESSION["utente"];
        $nomePagina = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
        echo "<nav>";
        if($nomePagina != "index.php") {
            echo "<a id = 'paginaIndietro' href = 'index.php'><span class = 'material-icons'>arrow_back</span></a>";
        }
        if($isHome)
            echo "<span id = 'nomeutente' onclick = \"window.location.href = './account.php'\">$utente</span>";
        else
            echo "<span id = 'nomeutente' onclick = \"window.location.href = '../account.php'\">$utente</span>";
        if($_SESSION["idutente"] == 5) {
            if($isHome)
                echo "<a href = 'amministrazione/index.php'><span class = 'material-icons'>build</span></a>";
            else
                echo "<a href = '../amministrazione/index.php'><span class = 'material-icons'>build</span></a>";
        }
        if($isHome) {
            echo "<a href = 'logout.php' id = 'logoutButton'><button value = 'Logout'>Logout</button></a>";
        } else {
            echo "<a href = '../logout.php' id = 'logoutButton'><button value = 'Logout'>Logout</button></a>";
        }

        echo "</nav>";

        if($_SESSION["temascuro"] == 1) {
            echo "<script>document.documentElement.setAttribute('data-theme', 'dark');</script>";
        }
        else {
            echo "<script>document.documentElement.setAttribute('data-theme', 'light');</script>";
        }
    }

    function generaPulsanteTornaHome() {
        echo "<div id = 'tornaAllOlimpo' onclick=\"window.location.href='../index.php'\"><span class = 'material-icons' alt = 'Torna alla Home'>home</span></div>";
    }

    function esci($stringa, $isHome=false) {
        if($isHome)
            header("Location: login.html?avviso=$stringa");
        else
            header("Location: ../login.html?avviso=$stringa");
        exit();
    }

    function controlloLogin() {
        if(!isset($_SESSION["utente"])) {
            esci();
            exit();
        }
    }

    function tornaAHome($stringa) {
        header("Location: ../index.php?avviso=$stringa");
        exit();
    }

    function escapa($con, $stringa) {
        return mysqli_real_escape_string($con, $stringa);
    }

    function eseguiQuery($con, $stringa) {
        $q = mysqli_real_escape_string($con, $stringa);
        return mysqli_query($con, $q);
    }

    function controllaHttps() {
        if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' ||
                $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
        {
            $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }
    }

    function controllaCredenziali($con, $email, $password) {
        $password = escapa($con, $password);
        $email = escapa($con, $email);
        $query = "SELECT * FROM utenti WHERE email = \"$email\" AND password = MD5(MD5(\"$password\"))";
        $res = mysqli_query($con, $query);
        if(mysqli_num_rows($res) == 0) {
            return false;
        }
        return true;
    }

    function inserisciChatId($con, $email, $chatId) {
        $email = escapa($con, $email);
        $query = "UPDATE utenti SET telegramchatid=$chatId WHERE email = \"$email\"";
        if(mysqli_query($con, $query)) {
            return true;
        }
        else {
            return false;
        }
    }

    function isProprietarioOf($con, $idutente, $idappunto) {
        $query = "SELECT * FROM appunti WHERE idutente = $idutente AND idappunto = $idappunto";
        $res = eseguiQuery($con, $query);
        if(mysqli_num_rows($res) > 0) {
            return true;
        }
        return false;
    }

    function isProprietarioOfCompito($con, $idutente, $idcomputo) {
        $query = "SELECT * FROM diario WHERE idutente = $idutente AND idcompito = $idcomputo";
        $res = eseguiQuery($con, $query);
        if(mysqli_num_rows($res) > 0) {
            return true;
        }
        return false;
    }

    function estraiMacroaree($c, $a) {
        $stringa = "";
        $q = "SELECT nome FROM macroaree, collegamento WHERE macroaree.codicemacroarea = collegamento.codicemacroarea AND idappunto = " . $a["idappunto"];
        $aree = mysqli_query($c, $q);
        if(mysqli_num_rows($aree) < 1) {
            return " - ";
        }
        while($area = mysqli_fetch_array($aree)) {
            $stringa .= $area["nome"] . "<br />";
        }
        return $stringa;
    }

    function getNomeMateria($con, $idmateria) {
        $query = "SELECT nome FROM materie WHERE codicemateria = $idmateria";
        $tmp = mysqli_query($con, $query);
        $tmp2 = mysqli_fetch_array($tmp);
        return $tmp2["nome"];
    }

    function getNomeUtente($con, $idutente) {
        $query = "SELECT nome FROM utenti WHERE idutente = $idutente";
        $tmp = mysqli_query($con, $query);
        $tmp2 = mysqli_fetch_array($tmp);
        return $tmp2["nome"];
    }
