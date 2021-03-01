window.onload = init;

function init(){
    jQuery("#sortie_ville").on("change", recuplieu);
    jQuery("#sortie_lieu").on("change", modifLieu);
    recuplieu();
}

function recuplieu(lieu){
    let idVille = jQuery("#sortie_ville").val();

    jQuery.ajax({
        url:'http://127.0.0.1:8000/api/lieu/api/recuplieuByIdVille-' +idVille,
        method: 'GET',
    })
        .done(function(datas){
            jQuery('#sortie_lieu').empty();
            datas.forEach(function (lieu){
                jQuery('#sortie_lieu').append(jQuery('<option>').attr('value',lieu.id).text(lieu.nom));
            });
            modifLieu(lieu.id);
        })
        .fail(function(xhr, status, errorThrow){
            reject({
                xhr:xhr,
                status : status,
                error : errorThrow
            });
        });

}

function modifLieu(lieu){
    let idLieu = jQuery("#sortie_lieu").val();

    jQuery.ajax({
        url:'http://127.0.0.1:8000/api/lieu/api/recuplieuById-' +idLieu,
        method: 'GET',
    })
        .done(function(lieu){
            jQuery('#monLieu').empty();
            jQuery('#monLieu').append(jQuery('<p/>').text("Rue: " + lieu.rue));
            jQuery('#monLieu').append(jQuery('<p/>').text("Code postal: " + lieu.ville.codePostal));
            jQuery('#monLieu').append(jQuery('<p>').text("Latitude: " + lieu.latitude));
            jQuery('#monLieu').append(jQuery('<p>').text("Longitude: " + lieu.longitude));
        })
        .fail(function(xhr, status, errorThrow){
            reject({
                xhr:xhr,
                status : status,
                error : errorThrow
            });
        });
}