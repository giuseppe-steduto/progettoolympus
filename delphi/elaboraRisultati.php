<html data-settore="delphi">
    <head>
        <title>Delphi - Risultati </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="dodgerblue">
        <meta http-equiv="Cache-control" content="no-cache">
        <!--Meta tag per iOS-->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <link rel="apple-touch-icon" href="../res/logo.png">
        <link rel = "manifest" href = "../manifest.json" />
        <link rel="icon" href="../res/logo32.png" />
        <link rel = "stylesheet" href = "../style/mathematicaresult.css" />
    </head>

<?php
    include "wa_wrapper/WolframAlphaEngine.php";
    include "../base.php";
    session_start();
    controlloLogin();
    generaTestata();

    $q = $_POST["query"];
    $appID = "XYXEX7-VPX7VKR7J2";

    $engine = new WolframAlphaEngine( $appID );
    $response = $engine->getResults($q);
    echo "<h2>La tua domanda:</h2>";
    echo "<p>$q</p>";

      // if there are any pods, display them
      if ( count($response->getPods()) > 0 ) {
          echo "<h2>Risultati</h2>";
          echo "<table border=1 align=\"center\">";

              foreach ( $response->getPods() as $pod ) {
                  echo "<tr>";
                      echo "<td>";
                           echo "<h3>" . $pod->attributes['title'] . "</h3>";
                          //Ciascun pod può avere più subpod; ne avrà almeno uno
                          foreach ( $pod->getSubpods() as $subpod ) {
                              //Formato immagine
                              echo "<img src=" . $subpod->image->attributes['src'] . " /><hr>";
                          }

                      echo "</td>";
                  echo "</tr>";

              }

          echo "</table>";
      }
      else {
          echo "<p>Non ho trovato risultati per la tua query! :(</p>";
      }
    ?>
</html>
