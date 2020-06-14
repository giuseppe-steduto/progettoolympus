const urlParams = new URLSearchParams(window.location.search);
const idappunto = urlParams.get('id');
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(richiestaGrafico);
      
var appunto = {idappunto: idappunto};
fetch("../athena/getInsightsPersone.php?id=" + idappunto,
{
  method: "POST",
  body: JSON.stringify(appunto)
})
.then(function(res){
    res.json()
    .then(ciao => {
        if(ciao.length > 0) {
            generaTabella(ciao);
        }
        else {
            document.getElementById("titoloAppunto").innerText = "Insights non disponibili per questo appunto.";
        }
    });
})
.catch(function(err) {
    console.error(err);
});

function richiestaGrafico() {
    fetch("../athena/getInsightsTempo.php?id=" + idappunto,
    {
      method: "POST",
      body: JSON.stringify(appunto)
    })
    .then(function(res){
        res.json()
        .then(informazioni => {
            if(informazioni.length > 0) {
                generaGrafico(informazioni);
            }
            else {
                document.getElementById("titoloAppunto").innerText = "Insights non disponibili per questo appunto.";
            }
        });
    })
    .catch(function(err) {
        console.error(err);
    });
}

function generaTabella(arrayPersone) {
    document.getElementById("titoloAppunto").innerText = arrayPersone[0].titolo;
    for(var i = 0; i < arrayPersone.length; i++) {
        var riga = document.createElement("tr");
        var tdNome = document.createElement("td");
        tdNome.innerText = arrayPersone[i].nome;
        var tdNDownload = document.createElement("td");
        tdNDownload.innerText = arrayPersone[i].ndownload;
        
        riga.appendChild(tdNome);
        riga.appendChild(tdNDownload);
        
        document.getElementById("tabellaPersone").appendChild(riga);
    }
}

function generaGrafico(arrayGiorni) {
    var data = new google.visualization.DataTable();
      data.addColumn('date', 'Giorno');
      data.addColumn('number', 'Download');

      data.addRows(arrayGiorni.length);
      for (var i = 0; i < arrayGiorni.length; i++) {
          data.setCell(i, 0, new Date(arrayGiorni[i].orario));
          data.setCell(i, 1, arrayGiorni[i].ndownload);
      }

      var options = {
        hAxis: {
          title: 'Giorni'
        },
        vAxis: {
          title: 'Numero download',
          minvalue: -1
        },
        colors: ['dodgerblue'],
        pointSize: 20,
        pointShape: 'square'
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('graficoDownloadNelTempo'));

      chart.draw(data, options);
}