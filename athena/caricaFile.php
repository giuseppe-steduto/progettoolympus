<?php
    //Upload file
    $dirFile = "uploads/";
    $randomLetters = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 8);
    $link = htmlspecialchars($dirFile . $randomLetters . basename($_FILES["file"]["name"], ENT_QUOTES));
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $link)) {
        echo $link;
    } else {
        echo "ERRORENELCARICAMENTODELFILE" .  " - " . $_FILES["file"]["error"] . " - " . $link . " - " . $_FILES["file"]["tmp_name"] . " - " . $_FILES["file"]["name"];
    }

