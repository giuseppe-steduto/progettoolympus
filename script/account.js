const applicationServerPublicKey = 'BIJJSDCZFtq3BQGKiO8LB_b-Owvq8vDGl_fwgpb9jtAkLH07_lkAJhKyJbgVXy3fhKKztgjSDyGtxvc879abp0A';

//Registrazione service worker
let swRegistration;
if('serviceWorker' in navigator) {
    navigator.serviceWorker
        .register('/sw.js')
        .then(function(swReg) {
            console.log("Service Worker Registered");
            swRegistration = swReg;
            //initializeUI();
        })
        .catch((err) => {console.log("Errore: ", err); });
}

function inviaRichiestaChangePassword(email) {
    if(!confirm("Cliccando su 'Ok', una mail di reset password verrà inviata a " + email)) {
        return;
    }
    fetch("inviaEmail.php", {
        method: "POST",
        body: email
    })
        .then((res) => {
            if(res.status != 200) {
                alert("Non sono riuscito a inviare la mail!")
            }
            res.text()
                .then((response) => {
                   if(response == "ok") {
                       console.log("utente registrato!");
                   }
                   else {
                       console.error("Registrazione fallita!" + response);
                   }
                });
        })
        .catch((err) => {console.log("OPS!" + err);});
}

function toggleCheckbox(cb) {
    if(cb.checked) {
        document.getElementById("labelNotifiche").classList.add("checkboxON");
    }
    else {
        document.getElementById("labelNotifiche").classList.remove("checkboxON");
    }
}

function toggleTemaScuro(cb) {
    if(cb.checked) {
        document.getElementById("labelTemaScuro").classList.add("checkboxON");
    }
    else {
        document.getElementById("labelTemaScuro").classList.remove("checkboxON");
    }
}

function registraNotifiche(selezionato) {
    if(!'PushManager' in window) {
        alert("Browser non supportato!");
        return;
    }
    if(!selezionato) {
        //Disiscrivi
        return;
    }
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    swRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
    .then(function(pushSubscription) {
        console.log(pushSubscription);
        updateSubscriptionOnServer(pushSubscription);
    });
}

function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function updateSubscriptionOnServer(subscription) {
    if (subscription) {
        fetch("./iscriviUtentiNotifiche.php",
            {
                method: "POST",
                body: JSON.stringify(subscription)
            })
            .then(function(res){
                res.text()
                    .then((risposta) => {console.log(risposta);});
            })
            .catch(function(res){alert("Errore! Email non inviata.")});
    } else {
        console.warn("Utente non iscritto al servizio push!");
    }
}