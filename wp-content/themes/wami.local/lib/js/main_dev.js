jQuery(function($){
    
    $(document).ready(function(){
        filtres_les_biens();
    });    

    /* On ouvre tous les liens externes dans un nouvel onglet */
    $('a').each(function(index, elem){
        var a = new RegExp('/'+window.location.host+'/');
        if(!a.test(this.href) && !$(this).hasClass('no_target')) $(this).attr('target', '_blank');
    });    

    /* On gere les ancres douces */
    $('a.ancredouce').click(function(){
        var the_id = $(this).attr("href"),
            the_header = $('#header'),
            amount_scroll =  $(the_id).offset().top-the_header.height() - 30;

        $('html, body').animate({
            scrollTop:amount_scroll
        }, 'slow');
        return false;
    });

    $('.retour_precedent').click(function(event) {
        event.preventDefault();
        history.back(1);
    });

   
    $('.imprimerlannonce').click(function(event) {
        event.preventDefault();
        print();
    });

    
    // GESTION DES POPINS
    $('body').on('click', '.open_popin', function(event){
        event.preventDefault();
        $('#'+ $(this).data('openpopin') ).removeClass('close');
    });
    $('body').on('click', '.close_popin', function(event){
        event.preventDefault();
        $('#'+ $(this).data('closepopin') ).addClass('close');
    });
   $('body').on('click', '.popin-layer', function() {
        $(this).addClass('close');        
    });
    $('body').on('click', '.popin', function(event){
        event.stopPropagation();
    });  


/* ---------------------------------------------------------------------------------------
----------------------------- LES FONCTIONS ----------------------------------------------
--------------------------------------------------------------------------------------- */

    function filtres_les_biens(){
        // en cas de recherche :
        $('#filtre_les_biens').on('submit', function(e){ 
            e.preventDefault();         
            filtre_et_appel_ajax();         
        });
        $('#filtre_les_actualites').on('submit', function(e){ 
            e.preventDefault();         
            filtre_et_appel_ajax();         
        });
    }


    function filtre_et_appel_ajax(){
        // on enregistre les filtres actif :
        var mesFiltres = {
            lieu: $('#mon_lieu').val(), 
            type_bien: $('#filtre_typedebien').val(), 
            prix: $('#filtre_prix').val(), 
            surface: $('#filtre_surface').val(), 
            ma_page: $('#ma_page').val(),
            periode: $('#filtre_mois').val()
        };
        // On gere la fonction appelée :
        var action          = 'wami_filtres_les_biens';
        var list_to_append  = 'annonce-list';
        if(mesFiltres.ma_page == 'actualites') {
            action          = 'wami_filtres_les_actualites';
            list_to_append  = 'workshop-list';
        }       
        // on recharge juste le contenu souhaité :
        $.ajax({
            url:wami_js.ajaxurl,
            data:{
                action: action,
                data: mesFiltres
            },                
            success:function(data){  
                //console.log(mesFiltres);
                //console.log(data.content);   
                $('.annonce_bien_trouve').remove();           
                $('.'+list_to_append).append(data.content);                
                $('html, body').animate({
                    scrollTop:$('body').offset().top 
                }, 10);   
                $('.submit-secondary').val( $('#mon_lieu').data('nicename')+' ('+data.total+')' );
                         
            },
            error:function(e){console.log('Une erreur est survenue, merci de réessayer.');}
        });  
    }

    /*if( $('#Map').length ){
        $('.region_active').each(function(){
            var region = $(this).data('region');             
            var values = $('.'+region).attr('coords').split(',');
            console.log(values);
        
            var x = [];
            var y = [];

            // fill in your X and Y arrays
            for (var i = 0; i < values.length; i++) {
                // push in x and increase i by 1.
                x.push(values[i++]);
                // push in y
                y.push(values[i]);
            }
            // get the min & max X values :
            var minX = Math.min.apply(null, x),
            maxX = Math.max.apply(null, x);
            console.log("minX : "+minX);
            console.log("maxX : "+maxX);
            // get the min & max Y values :
            var minY = Math.min.apply(null, y),
            maxY = Math.max.apply(null, y);
            console.log("minY : "+minY);
            console.log("maxY : "+maxY);  

            // Here is your result :
            var myTop = (maxX - minX); 
            var myLeft = (maxY - minY);
           

            $(this).css({
                'top' : myTop,
                'left' : myLeft,
            })

        })
    }*/
    
    
});