function toggleTabella(intestazione) {
    let tabella = intestazione.nextSibling;
    let visibilita = tabella.style.display;
    let freccetta = intestazione.childNodes[1];

    if(visibilita == "table") {
        tabella.style.display = "none";
        intestazione.style.fontWeight = "normal";
        freccetta.style.transform = "rotate(0deg)";
    } else {
        tabella.style.display = "table";
        intestazione.style.fontWeight = "bold";
        freccetta.style.transform = "rotate(180deg)";
    }
}

function modificaCampo(riga) {
    numeroCampi = riga.childElementCount;
    for (var i = 0;  i < numeroCampi - 1; i++) {
        //Crea un input al posto del semplice valore precedente, con lo stesso valore
        var valorePrecedente = riga.childNodes[i].innerText;
        var inputPerCella = document.createElement("input");
        inputPerCella.value = valorePrecedente;

        riga.childNodes[i].innerHTML = "";
        riga.childNodes[i].appendChild(inputPerCella);
    }

    //Aggiorna l'icona
    riga.childNodes[numeroCampi - 1].innerHTML = "<span class = 'material-icons' onclick = 'completaRiga(this)'>done</span>";
}

function completaRiga(icona) {
    var riga = icona.parentNode.parentNode;

    //L'id della riga è del tipo nomeTabella-idRiga
    var idCosa = parseInt(riga.id.split("-")[1]);
    var nomeTabella = riga.id.split("-")[0];
    var parametriRichiesta = {
        id: idCosa,
        nomeTabella: nomeTabella
    };

    numeroCampi = riga.childElementCount;
    for (var i = 0;  i < numeroCampi - 1; i++) {
        var valorePrecedente = riga.childNodes[i].childNodes[0].value;
        riga.childNodes[i].innerHTML = valorePrecedente;
        parametriRichiesta[riga.childNodes[i].getAttribute("name")] = valorePrecedente;
    }

    //Aggiorna l'icona
    var icone = "<span class = 'material-icons' onclick='modificaCampo(this.parentNode.parentNode)'>edit</span><span class = 'material-icons'>remove</span>";
    riga.childNodes[numeroCampi - 1].innerHTML = icone;

    fetch('aggiornaParametri.php', {
        method: 'POST',
        body: JSON.stringify(parametriRichiesta)
    }).then(response => response.text())
    .then(response => {
        if(response == "ok") {
            riga.style.color = "green";
        }
        else {
            riga.style.color = "red";
        }
    })
    .catch(err => console.err("C'è stato un errore!" + err));
}

function eliminaCampo(riga) {
    var idCosa = parseInt(riga.id.split("-")[1]);
    var nomeTabella = riga.id.split("-")[0];
    var parametriRichiesta = {
        id: idCosa,
        nomeTabella: nomeTabella
    };

    fetch('eliminaElementi.php', {
        method: 'POST',
        body: JSON.stringify(parametriRichiesta)
    }).then(response => response.text())
        .then(response => {
            if(response == "ok") {
                riga.parentElement.removeChild(riga);
            }
            else {
                riga.style.color = "red";
                alert("Eliminazione fallita! Probabilmente eliminando questo elemento romperesti un vincolo di integrità referenziale.")
            }
        })
        .catch(err => console.err("C'è stato un errore!" + err));
}

function creaRigaPerAggiungere(tabella) {
    let riga = tabella.childNodes[1]; //Prendi la prima riga della tabella (0 è l'intestazione)
    let nomeTabella = tabella.parentElement.getAttribute("name");
    let numeroCampi = riga.childElementCount;
    let nuovaRiga = document.createElement("tr");
    for(let i = 0; i < numeroCampi - 1; i++) {
        let td = document.createElement("td");
        td.setAttribute("name", riga.childNodes[i].getAttribute("name"));
        let input = document.createElement("input");
        input.setAttribute("name", riga.childNodes[i].getAttribute("name"));
        td.appendChild(input);
        nuovaRiga.appendChild(td);
    }
    let tdIcona = document.createElement("td");
    tdIcona.innerHTML = "<span class = 'material-icons' onclick = 'aggiungiElemento(this.parentNode.parentNode)'>done</span>";;
    nuovaRiga.appendChild(tdIcona);

    //Inserisci la nuova riga in penultima posizione
    tabella.insertBefore(nuovaRiga, tabella.childNodes[tabella.childElementCount - 1]);
}

function aggiungiElemento(riga) {
    let nomeTabella = riga.parentElement.parentElement.getAttribute("name");
    let parametriRichiesta = {
        nomeTabella: nomeTabella
    };
    let numeroCampi = riga.childElementCount;
    for (let i = 0;  i < numeroCampi - 1; i++) {
        let valore = riga.childNodes[i].childNodes[0].value;
        parametriRichiesta[riga.childNodes[i].getAttribute("name")] = valore;
    }

    fetch('aggiungiElementi.php', {
        method: 'POST',
        body: JSON.stringify(parametriRichiesta)
    }).then(response => response.text())
        .then(response => {
            if(response == "ok") {
                //Inserimento riuscito, ricarica la pagina
                location.reload();
            }
            else {
                riga.style.color = "red";
                alert("Inserimento fallito!" + response);
            }
        })
        .catch(err => console.error("C'è stato un errore!" + err));
}