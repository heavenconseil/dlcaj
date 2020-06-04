jQuery(function($){    

    //$(document).ready(function(){   });           
    //dépliement liste mandat tableau de bord
    $('.liste h5').on('click', function(){
        if($(this).parent('.liste').find('.liste_deroulante').hasClass('hide'))
            $(this).parent('.liste').find('.liste_deroulante').removeClass('hide');
        else
            $(this).parent('.liste').find('.liste_deroulante').addClass('hide');
    });
    /**********************
        CREER UNE ANNONCE
    **********************/
    // Ancre de la sidebar de la page d'enregistrement d'une annonce :
    $('.titre_bloc_with_ancres').on('click', function(e){  
        e.preventDefault(); 
        if( $(this).parents('.bloc_with_ancres').hasClass('close') ){
            $('.bloc_with_ancres.open').removeClass('open').addClass('close'); 
            $(this).parents('.bloc_with_ancres').removeClass('close').addClass('open');  
            // et sa section :
            var section_to_open = $(this).parents('.bloc_with_ancres').data('open');
            $('.section-form-part.open').removeClass('open').addClass('close');
            $('.section-form-part.'+section_to_open).removeClass('close').addClass('open');
            //$('.section-form-part.'+section_to_open).children().first().removeClass('close').addClass('open');
            //et on remonte en haut de la page
            $('html, body').scrollTop(0);
            //et dans l'url
            window.history.pushState("", "", e.currentTarget.href);
        } else {
            //toggle open/close
            if( $(this).parents('.bloc_with_ancres').hasClass('open') ){
                $(this).parents('.bloc_with_ancres').removeClass('open').addClass('close'); 
                // et sa section :
                $('.section-form-part.open').removeClass('open').addClass('close');                
                //et dans l'url
                window.history.pushState("", "", window.location.href.split("?")[0]);
            }
        }        
    });  
    //Ancre de la sidebar sous-section
    $('.titre_bloc_with_sub_ancres').on('click', function(e){        
        e.preventDefault(); 
        if( $(this).hasClass('close') ){
            $('.titre_bloc_with_sub_ancres').removeClass('open').addClass('close'); 
            $(this).removeClass('close').addClass('open');
            // et sa section :
            var section_to_open = $(this).data('open');
            $("div[class^='sub-section']").addClass('close').removeClass('open');
            $('.sub-section_'+section_to_open).removeClass('close').addClass('open');
            //et on remonte en haut de la page
            $('html, body').animate({
                scrollTop:0
            }, 'fast');             
            //et dans l'url
            window.history.pushState("", "", e.currentTarget.href);
        } else {
            $(this).removeClass('open').addClass('close'); 
            //et sa section
            var section_to_open = $(this).data('open');
            $("div[class^='sub-section']").addClass('close').removeClass('open');
            //et dans l'url
            window.history.pushState("", "", window.location.href.split("?")[0]);
        }
    }); 

    //au click sur les acf-field-number on sélect le champ input
    $('.acf-field-number').on('click', function(){
        $(this).find('input').focus() ;
    });
    $('.acf-field-text').on('click', function(){
        $(this).find('input').focus() ;
    });

    
    //perfectscrollbar
    if($('tpl-annonces-ajouts')){    
        //$('.ville').perfectScrollbar();      
        $('.tax_ville').each(function(){
            $(this).perfectScrollbar();  
        }); 
        //$('.lieu').perfectScrollbar(); 
        $('.tax_district').each(function(){
            $(this).perfectScrollbar();  
        });              
    }


    // Au clic sur le type de mandat    
    $('.acf-field-597609cc2fc49 li').on('click', function(){
        var type_mandat = $(this).find('input').val();
        if(type_mandat == "mandat_de_recherche" || type_mandat =="mandat_delegation" ){
            $('.hide_for_mandatrecherche_or_delegationmandat').hide();
        } else {
            $('.hide_for_mandatrecherche_or_delegationmandat').show().css('display', 'block');
        }
        if(type_mandat =="mandat_delegation" ){
            $('.hide_for_delegationmandat').hide();
        } else {
            $('.hide_for_delegationmandat').show().css('display', 'block');
        }
    });


    /**********************
        ANNONCES
    **********************/
    //lire la suite
    $('.lire_la_suite').on('click', function(){
        if($(this).hasClass('open')){
            $(this).removeClass('open');
            $('.full_text').hide();
            $('.extrait').show();
        } else {
            $(this).addClass('open');
            $('.full_text').show();
            $('.extrait').hide();
        }
    })


});