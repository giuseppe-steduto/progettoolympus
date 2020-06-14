const urlParams = new URLSearchParams(window.location.search);
const avviso = urlParams.get('avviso');

if(avviso) {
  alert(avviso);
}

function inviaRichiestaChangePassword() {
    var email = prompt("Inserisci la mail a cui invieremo il link per resettare la password: ");
    fetch("../inviaEmail.php", {
      method: "POST",
      body: email
    })
    .then((res) => {
        if(res.status != 200) {
            alert("Non sono riuscito a inviare la mail!")
        }
        res.text()
        .then((response) => {
            console.log(response);
        });
    })
    .catch((err) => {console.log("OPS!" + err);});
}

function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    var id_token = googleUser.getAuthResponse().id_token;

    //Invia il token ID al server per l'autorizzazione
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'loginGoogle.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if(xhr.responseText == "Login effettuato!")
            window.location.href = "index.php";
        else
            alert("Login con Google fallito!");
    };
    xhr.send('idtoken=' + id_token);
}

function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
        window.location.href = "login.html";
    })
    .catch(err => {
        console.log('Error.' + err);
        window.location.href = "login.html";
    });
}

function onLoad() {
    gapi.load('auth2', function() {
        gapi.auth2.init()
            .then(signOut)
            .catch(err => {window.location.href = "login.html";});
    });
}

function renderButton() {
    gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark'
    });
}
