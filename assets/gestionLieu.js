window.onload = init;

function init(){
    jQuery("#sortie_ville").on("change", recuplieu);
    jQuery("#sortie_lieu").on("change", modifLieu);
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
            rue.append(jQuery('<input/>').attr('class','form-control').attr('id','rueInput').attr('readonly', true).val(lieu.rue));

            let cp =  jQuery('#monLieuCodePostal');
            cp.empty();
            cp.append(jQuery('<label/>').attr('class','form-check-label').text("Code postal"));
            cp.append(jQuery('<input/>').attr('class','form-control').attr('id','rueInput').attr('readonly', true).val(lieu.ville.codePostal));

            let lat = jQuery('#monLieuLat');
            lat.empty();
            lat.append(jQuery('<label/>').attr('class','form-check-label').text("Latitude"));
            lat.append(jQuery('<input/>').attr('class','form-control').attr('id','rueInput').attr('readonly', true).val(lieu.latitude));

            let long = jQuery('#monLieuLong');
            long.empty();
            long.append(jQuery('<label/>').attr('class','form-check-label').text("Longitude"));
            long.append(jQuery('<input/>').attr('class','form-control').attr('id','rueInput').attr('readonly', true).val(lieu.longitude));
        })
        .fail(function(xhr, status, errorThrow){
            reject({
                xhr:xhr,
                status : status,
                error : errorThrow
            });
        });
}