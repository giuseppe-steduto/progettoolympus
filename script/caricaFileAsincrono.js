var caricato = false;
function inviaForm() {
    var formInfoAppunto = document.forms[0];
    if(!caricato) {
        alert("Il caricamento del file non è ancora stato completato! Guarda il progresso in basso.");
        return false;
    }
    if(controllaForm()) {
        formInfoAppunto.submit();
    }
}

function caricaFileAsincrono() {
    caricato = false;
    var file = document.getElementById("file").files[0];
    var ajax = new XMLHttpRequest();
    ajax.open("POST", "../athena/caricaFile.php", true);
    var formData = new FormData();
    formData.append("file", file);
    ajax.send(formData);

    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText.includes("ERRORENELCARICAMENTODELFILE"))   {
                console.log(this.responseText);
                alert("Caricamento del file fallito!");
                return false;
            }
            else {
                document.getElementById("inputLinkFile").value = this.responseText;
                caricato = true;
            }
            return true;
        }
    };

    var progress = document.getElementById("progress");
    ajax.onprogress = function (event) {
        progress.max = event.total;
        progress.value = event.loaded;
    };
    progress.value = 0;
}

function controllaForm() {
    var formInfoAppunto = document.forms[0];
    if(formInfoAppunto["titolo"].value == "") {
        alert("Devi inserire un titolo!");
        return false;
    }
    if(formInfoAppunto["tipo"].value == "") {
        alert("Devi scegliere un tipo per il tuo appunto!");
        return false;
    }
    return true;
}
