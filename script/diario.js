var modalAperto = false;
function toggleModalInserimento() {
    if(modalAperto) {
        //Chiudi modal
        document.getElementById("modalInserimento").style.transform = "scale(0)";
        document.getElementById("bottoneAggiungiAppunto").style.transform = "rotate(0deg)";
        document.getElementById("bottoneAggiungiAppunto").style.color = "dodgerblue";
        document.getElementById("bottoneAggiungiAppunto").style.right = "1rem";
        modalAperto = false;
    }
    else {
        //Apri modal
        document.getElementById("modalInserimento").style.transform = "scale(1)";
        document.getElementById("bottoneAggiungiAppunto").style.transform = "rotate(45deg)";
        document.getElementById("bottoneAggiungiAppunto").style.color = "#FF5555";
        document.getElementById("bottoneAggiungiAppunto").style.right = "calc((100% - 4rem)/2)";
        modalAperto = true;
    }
}

function eseguiCompito(riga) {
    idcompito = riga.id;

    var compito = {idappunto: idcompito};
    fetch("completaCompito.php?id=" + idcompito,
        {
            method: "POST",
            body: JSON.stringify(compito)
        })
        .then(function(res){
            res.text()
                .then(testo => {
                    if(testo == "okay") {
                        console.log("Fatto!");
                        riga.classList.add("compitoFatto");
                    }
                    else {
                        console.log("non fatto!" + testo);
                    }
                });
        })
        .catch(function(err) {
            console.error(err);
        });
}

function eliminaCompito(riga) {
    idcompito = riga.id;

    var compito = {id: idcompito};
    fetch("eliminaCompito.php",
        {
            method: "POST",
            body: JSON.stringify(compito)
        })
        .then(function(res){
            res.text()
                .then(testo => {
                    if(testo == "okay") {
                        console.log("Fatto!");
                        riga.parentElement.removeChild(riga);
                    }
                    else {
                        console.log("non fatto!" + testo);
                        riga.style.color = "var(--rosso)";
                    }
                });
        })
        .catch(function(err) {
            console.error(err);
        });
}

function nonEseguiCompito(riga) {
    idcompito = riga.id;

    var compito = {idappunto: idcompito};
    fetch("./completaCompitoNo.php?id=" + idcompito,
        {
            method: "POST",
            body: JSON.stringify(compito)
        })
        .then(function(res){
            res.text()
                .then(testo => {
                    if(testo == "okay") {
                        console.log("Fatto!");
                        riga.classList.add("compitoNonFatto");
                    }
                    else {
                        console.log("non fatto!" + testo);
                    }
                });
        })
        .catch(function(err) {
            console.error(err);
        });
}