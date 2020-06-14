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