var isPushSupported = false;
var swRegistration;

function eliminaAppunto(idappunto) {
    if(confirm("Sei davvero sicuro di voler eliminare l'appunto?\nQuest'azione è irreversibile.")) {
        console.log("Oh, rabbia");
        window.location.href = "../athena/eliminaAppunto.php?id=" + idappunto;
    }
}

/*
if('PushManager' in window) {
    isPushSupported = true;
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

function initializeUI() {
    subscribeUser();
}

function subscribeUser() {
  const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
  swRegistration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: applicationServerKey
  })
  .then(function(subscription) {
    console.log('User IS subscribed.' + JSON.stringify(subscription));
    document.getElementById("endpoint").innerText = subscription.endpoint;
    updateSubscriptionOnServer(subscription);
    isSubscribed = true;
  })
  .catch(function(err) {
    console.log('Failed to subscribe the user: ', err);
  });
}

function updateSubscriptionOnServer(subscription) {
  if (subscription) {
      fetch("./iscriviUtentiNotifica.php",
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
*/

const urlParams = new URLSearchParams(window.location.search);
const avviso = urlParams.get('avviso');

if(avviso) {
  alert(avviso);
}