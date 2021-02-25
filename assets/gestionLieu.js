window.onload = init;

function init(){
    jQuery("#sortie_ville").on("change", recuplieu);
}

function recuplieu(){
    let idVille = jQuery(this).val();

    jQuery.ajax({
        url:'http://127.0.0.1:8000/api/lieu/api/recuplieu-' +idVille,
        method: 'GET',
    })
        .done(function(datas){
            jQuery('#sortie_lieu').empty();
            datas.forEach(function (lieu){
                jQuery('#sortie_lieu').append(jQuery('<option>').attr('value',lieu.id).text(lieu.nom));
            });

        })
        .fail(function(xhr, status, errorThrow){
            reject({
                xhr:xhr,
                status : status,
                error : errorThrow
            });
        });

}