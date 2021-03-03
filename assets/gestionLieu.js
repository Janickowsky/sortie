window.onload = init;

function init(){
    jQuery("#sortie_ville").on("change", recuplieu);
    jQuery("#sortie_lieu").on("change", modifLieu);
    //jQuery("#addLieu").on("click",afficherModal);
}

function afficherModal(){
    jQuery("#myModal").show();
}

function recuplieu(lieu){
    let idVille = jQuery("#sortie_ville").val();
    jQuery('#sortie_lieu').empty();

    jQuery.ajax({
        url:'http://127.0.0.1:8000/api/lieu/api/recuplieuByIdVille-' +idVille,
        method: 'GET',
    })
        .done(function(datas){
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
            let rue =  jQuery('#monLieuRue');
            rue.empty();
            rue.append(jQuery('<label/>').attr('class','form-check-label').text("Rue"));
            rue.append(jQuery('<p/>').attr('class','form-control').text(lieu.rue));

            let cp =  jQuery('#monLieuCodePostal');
            cp.empty();
            cp.append(jQuery('<label/>').attr('class','form-check-label').text("Code postal"));
            cp.append(jQuery('<p/>').attr('class','form-control').text(lieu.ville.codePostal));

            let lat = jQuery('#monLieuLat');
            lat.empty();
            lat.append(jQuery('<label/>').attr('class','form-check-label').text("Latitude"));
            lat.append(jQuery('<p/>').attr('class','form-control').text(lieu.latitude));

            let long = jQuery('#monLieuLong');
            long.empty();
            long.append(jQuery('<label/>').attr('class','form-check-label').text("Longitude"));
            long.append(jQuery('<p/>').attr('class','form-control').text(lieu.longitude));
        })
        .fail(function(xhr, status, errorThrow){
            reject({
                xhr:xhr,
                status : status,
                error : errorThrow
            });
        });
}