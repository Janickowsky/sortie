window.onload = init;

function init(){
    jQuery("#sortie_ville").on("change", recuplieu);
    jQuery("#sortie_lieu").on("change", modifLieu);
    jQuery("#enregistrer").on("click",ajouterLieu);
    jQuery("#addLieu").on("click",afficherModal);
    jQuery("#close").on("click",fermerModal);
}

function afficherModal(){
    jQuery.ajax({
        url:'http://127.0.0.1:8000/api/lieu/api/villes',
        method: 'GET',
    })
        .done(function(datas){
            datas.forEach(function (ville){
                jQuery('#villes').append(jQuery('<option>').attr('value',ville.id).text(ville.nom));
            });
            jQuery("#myModal").show();
        })
        .fail(function(xhr, status, errorThrow){
            console.log(errorThrow);
            console.log(status);
            console.log(xhr);
        });
}
function fermerModal(){
    jQuery("#myModal").hide();
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
            console.log(errorThrow);
            console.log(status);
            console.log(xhr);
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
            console.log(errorThrow);
            console.log(status);
            console.log(xhr);
        });
}

function ajouterLieu(){
    let nomlieu = jQuery("#nom").val();
    let ruelieu = jQuery("#rue").val();
    let idVille = jQuery("#villes").val();
    jQuery.ajax({
        url : 'http://127.0.0.1:8000/api/lieu/api/ville-'+idVille,
        method : 'GET',
    })
        .done(function(ville){
            let lieu = {
                'nom':nomlieu,
                'rue':ruelieu,
                'ville':ville,
            };
            console.log(JSON.stringify(lieu));
            jQuery.ajax({
                url : 'http://127.0.0.1:8000/api/lieu/api/lieu',
                method : 'POST',
                data : JSON.stringify(lieu)
            })
                .done(function(){
                    jQuery("#myModal").hide();
                })

                .fail(function (xhr, status, errorThrow){
                    console.log(errorThrow);
                    console.log(status);
                    console.log(xhr);
                });
        })

        .fail(function (xhr, status, errorThrow){
            console.log(errorThrow);
            console.log(status);
            console.log(xhr);
        });

}