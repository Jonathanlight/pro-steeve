$(document).ready(function(){
    function reload_spooler() {
        $.ajax({
            url: window.apiAddress + 'spooleritems/'+ spoolerItemId + '/state.json',
            type: 'GET',
            error: function(error) {
                document.getElementById("error").style.display = "block";
                $("#error span").html('Server en attente ...');
            },
            success: function(data) {
                if(data.status === 0){
                    //En attente
                    $('#status').html(data.value);
                    $('#status-detail').html('Temps d\'attente estimé : '+ data.time);
                    setTimeout(reload_spooler, 3000);
                }
                if(data.status === 1){
                    //Traitement en cours
                    $('#status').html(data.value);
                    $('#status-detail').html('Temps d\'exécution estimé : '+ data.time);
                    setTimeout(reload_spooler, 5000);
                }
                if(data.status === 2){
                    //ont retourne les resultats
                    $("#loader").css('display', 'none');
                    $("#validated").css('display', 'inline-block');
                    $('#status').html(data.value);
                    $('#status-detail').html('Vous allez être re-dirigé(e) vers la page de résultats...');

                    setTimeout(function(){
                        window.location.assign(window.address + 'spooler/anwser/'+data.spoolerItem);
                    }, 3000);
                }

            }
        });
    }

    reload_spooler();

});