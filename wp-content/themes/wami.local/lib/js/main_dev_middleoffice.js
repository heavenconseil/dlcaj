jQuery(function($){

    $('.lost_password').on('click', 'a', function(event){
        event.preventDefault();
        $('.connect').hide();
        $('.motdepasseperdu').removeClass('undisplay');
    });


    $('#lostpasswordform').on('submit', function(event){
        event.preventDefault();
        if( !$('.message_erreur').length ) {            
            var form        = $(this);
            var postData    = form.serialize();
            //var destination = form.attr('action');
            $.ajax({
                url:wami_js.ajaxurl,
                data:{
                    action:'wami_reset_password',
                    data:postData,
                },
                success:function(response){
                    if( $.trim(response)=='ok' ){
                        //window.location.href = destination;
                        $('#ressetpassform').hide();
                        $('.message').empty().append("<p class='password_modifie'>Un mail contenant un lien de confirmation vient de vous être envoyé, vérifiez votre messagerie pour y trouver le lien de renouvellement de votre mot de passe.</p>");
                    }else{
                        $('.message').empty().append(response);
                        //console.log(response);
                        return false;
                    }
                }
            });
        }        
    });

    $('#ressetpassform').on('submit', function(event){
        event.preventDefault();
        if( !$('.message_erreur').length ) {            
            var form        = $(this);
            var postData    = form.serialize();
            $.ajax({
                url:wami_js.ajaxurl,
                data:{
                    action:'wami_change_password',
                    data:postData,
                },
                success:function(response){
                    if( $.trim(response)=='ok' ){
                        //window.location.href = destination;
                        $('#ressetpassform').hide();
                        $('.changepassword').append("<p class='password_modifie'>Merci, votre mot de passe vient d'être mis à jour.</p>");
                    }else{
                        //console.log(response);
                        $('.changepassword').append('<div class="popin popin_log"><div class="popin_content"><h4>'+response+'</h4><div class="close_popin">X</div></div></div>');
                        setTimeout(function() {
                          $('.popin_log').fadeOut();
                        }, 5000);
                        //return false;
                    }
                }
            });
        }        
    });


    //SEARCH DU TABLEAU DE BORD
    $('.search_form_stat').on('submit', function(e){
        e.preventDefault();
        var mon_bloc = $(this).parents('.stat-bloc');
        var search = $(this).find('input').val();       
        if( $(this).parents('.stat-bloc').find('.dossier_'+search).length )
            mon_bloc.find('.resultat_recherche').html( $(this).parents('.stat-bloc').find('.dossier_'+search).clone() );
        else            
            mon_bloc.find('.resultat_recherche').html( 'Aucun dossier ne correspond à votre recherche.' );
    });
    // IDEM avec le filtre des mois
    $('.filtre_form_stat').on('submit', function(e){
        e.preventDefault();
        var filtre = $(this).find('select').val(); 
        console.log(filtre);
        if( $(this).parents('.stat-bloc-with-filtre').find('.dossier').length ){
            $(this).parents('.stat-bloc-with-filtre').find('.dossier').each(function(){    
                if($(this).hasClass('periode_'+filtre) || filtre == "" ){                
                    $(this).removeClass('undisplay'); 
                }
                else $(this).addClass('undisplay'); 
            }); 
        }            
    });
    // IDEM pour les ambassadeurs
    $('.search_form_amba').on('submit', function(e){
        e.preventDefault();
        var mon_bloc = $(this).parents('.stat-bloc');
        var search = $(this).find('input').val();  
        if( $(this).parents('.stat-bloc').find('.'+search).length )
            mon_bloc.find('.resultat_recherche').html( $(this).parents('.stat-bloc').find('.'+search).clone() );
        else            
            mon_bloc.find('.resultat_recherche').html( 'Aucun dossier ne correspond à votre recherche.' );
    });


    function get_GET(param) {
        var vars = {};
        window.location.href.replace( location.hash, '' ).replace( 
            /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
            function( m, key, value ) { // callback
                vars[key] = value !== undefined ? value : '';
            }
        );
        if ( param ) {
            return vars[param] ? vars[param] : null;    
        }
        return vars;
    }

    
    // FONCTIONS DE TRIE ET DE PAGINATION DES PAGES D'AFFICHAGE DES MANDATS/ANNONCES  
    function appel_ajax_mandat_trie_par_and_load_more(pageToLoad, qte_post){  
        var tpl             = $('.annonces_triees').data('tpl');
        var wamiforceuid    = get_GET('wamiforceuid');
        var trie_par        = 'ID'; 
        if($('.trie.actif').length)
            trie_par        = $('.trie.actif').data('triepar');
        //console.log('trie_par : '+trie_par);
        $('.loader').addClass('actif');

        $.ajax({
            url: wami_js.ajaxurl,
            data: {                 
                'action': 'wami_mandat_trie_par_and_load_more',
                'data': 1,
                'tpl': tpl,
                'paged': pageToLoad,
                'qte_post': qte_post,
                'trie_par': trie_par,
                'wamiforceuid': wamiforceuid
            }, 
            success:function(data) {             
                if(data) {
                    $('.pagination').remove();
                    if(pageToLoad == '1') $('.annonces_triees').empty();
                    $('.annonces_triees').append(data);
                } else {
                    $('.pagination').remove();
                }
                $('.loader').removeClass('actif');
            },
            error: function(errorThrown){
                $('.loader').removeClass('actif');
                alert('Une erreur est survenue, merci de réessayer.');
            }
        });
    };
    $('.trie').on('click', function(e){        
        e.preventDefault();       
        if( !$(this).hasClass('actif') ) {
            $('.trie.actif').removeClass('actif');
            $(this).addClass('actif');
        }
        else $(this).removeClass('actif'); 
        appel_ajax_mandat_trie_par_and_load_more('1', '3');        
    });
    $('.annonces_triees').on('click', '.more-article', function(e){
        e.preventDefault();
        var paged       = $(this).attr('data-paged');
        var pageToLoad  = (paged*1)+1; 
        var qte_post    = $(this).attr('data-qte-post');
        appel_ajax_mandat_trie_par_and_load_more(pageToLoad, qte_post);
    });

    $('.annonces_triees').on('click', '.show-more-article', function(e){
        e.preventDefault();
        $('.loader').addClass('actif');  
        
        var qte_post    = parseInt( $(this).attr('data-qte-post') );
        var $start      = parseInt( $(this).attr('data-from') );
        var $end        = parseInt( $(this).attr('data-to') );
        for (i = $end; i < $end+qte_post; i++) {
            $('.ligne-'+i).show();
        } 

        var totalpost   = parseInt( $(this).attr('data-total') );  
        if(totalpost <= $end+qte_post) $('.pagination').remove();
        else {
            $('.pagination .show-more-article').attr('data-start', $end + 1);
            $('.pagination .show-more-article').attr('data-to', $end + qte_post);
        }

        $('.loader').removeClass('actif'); 
    });



    // FILTRES DU FORMULAIRE DE CONTACT    
    $('.search_contacts_by_letter').on('click', function(e){
        e.preventDefault();
        filtre_et_appel_ajax_contacts($(this).data('lettre'));
    })
    $('#filtre_les_contacts').on('submit', function(e){ 
        e.preventDefault();         
        filtre_et_appel_ajax_contacts(false);         
    });


    function filtre_et_appel_ajax_contacts($letter){
        // on enregistre les filtres actif :
        var mesFiltres = { search: $('#mon_contact').val() };
        if($letter!=false)
            mesFiltres = { initiale: $letter };
        // On gere la fonction appelée :
        var action          = 'wami_filtres_les_contacts';
        var list_to_append  = 'contact-list';              
        // on recharge juste le contenu souhaité :
        $.ajax({
            url:wami_js.ajaxurl,
            data:{
                action: action,
                data: mesFiltres
            },                
            success:function(data){  
                //console.log(mesFiltres);
                //console.log(data);   
                $('.contact-list').html(data);                  
                $('html, body').animate({
                    scrollTop:$('body').offset().top 
                }, 10);   
                         
            },
            error: function(errorThrown){
                $('.loader').removeClass('actif');
                alert('Une erreur est survenue, merci de réessayer.');
            }
        });  
    }


    //GESTION DU TELECHARGEMENT DU MANDAT
    $('.dl_mandat').on('click', function(event){
        event.preventDefault();
        $('.loader').addClass('actif');
        var mandat_id = $(this).data('mandatid');
        var agent_id = $(this).data('cid');
        if(mandat_id && mandat_id!="" && mandat_id!=undefined){
            $.ajax({
                url:wami_js.ajaxurl,
                data:{
                    action: 'wami_get_mandat_to_pdf',
                    mandat_id: mandat_id,
                    agent_id: agent_id
                },                
                success:function(data){ 
                    //$('.loader').removeClass('actif');
                    window.open(data, '_blank');
                    $.ajax({
                        url:wami_js.ajaxurl,
                        data:{
                            action: 'wami_get_form_retractation_to_pdf',
                            mandat_id: mandat_id,
                            agent_id: agent_id
                        },                
                        success:function(data){ 
                            $('.loader').removeClass('actif');
                            window.open(data, '_blank');
                        },
                        error:function(e){console.log('Une erreur est survenue, merci de réessayer.');}
                    })  
                },
                error: function(errorThrown){
                    $('.loader').removeClass('actif');
                    alert('Une erreur est survenue, merci de réessayer.');
                }
            });  
        }
    });


    $('.dl_doc_precontractuel').on('click', function(event){
        event.preventDefault();
        $('.loader').addClass('actif');
        var mandat_id = $(this).data('mandatid');
        var agent_id = $(this).data('cid');
        if(mandat_id && mandat_id!="" && mandat_id!=undefined){
            $.ajax({
                url:wami_js.ajaxurl,
                data:{
                    action: 'wami_get_doc_precontractuel_to_pdf',
                    mandat_id: mandat_id,
                    agent_id: agent_id
                },                
                success:function(data){ 
                    $('.loader').removeClass('actif');
                    window.open(data, '_blank');
                },
                error: function(errorThrown){
                    $('.loader').removeClass('actif');
                    alert('Une erreur est survenue, merci de réessayer.');
                }
            });  
        }
    });
     


    $('body').on('click','.middle-office-accordion.chevron', function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
    });


    $('.update_rappel_statut').on('click', function(e){
        $.ajax({
            url:wami_js.ajaxurl,
            data:{
                action: 'wami_update_rappel_statut',
                rappel_id: $(this).data('rappelid')
            }
        });  
    });

    if( $('.demandes_attente_with_clone_bulle').length ){
         $.ajax({
            url:wami_js.ajaxurl,
            data:{
                action: 'wami_header_add_bulle_demande_attente'
            },                
            success:function(data){  
                $('.demandes_attente_with_clone_bulle').append('<span class="bulle compteur_to_clone_in_menu">'+data+'</span>');
            },
            error: function(errorThrown){
                $('.loader').removeClass('actif');
                alert('Une erreur est survenue, merci de réessayer.');
            }
        });
    };



    //finalement ajout d'un form acf ou dans class->saveBien on a ajouter l'action de wami_update_bien_niveau_vente
    if( $('.annonces_triees').length ){
        var bien_id = 0;
        $('.annonces_triees').on('click', '.declare_vente', function(){
            bien_id = $(this).data('bid');
        });
        $('.confirme_declare_vente').on('click', function(){
            var niveau_vente = $(this).data('closepopin');
            if(bien_id){
                $('.loader').addClass('actif');
                $.ajax({
                    url:wami_js.ajaxurl,
                    data:{
                        action: 'wami_update_bien_niveau_vente',
                        data: 1,
                        bien_id: bien_id,
                        niveau_vente: niveau_vente
                    },                
                    success:function(data){  
                        location.reload();
                    },
                    error: function(errorThrown){
                        $('.loader').removeClass('actif');
                        alert('Une erreur est survenue, merci de réessayer.');
                    }
                });
            }
        });
    };


    // AJOUT POUR TRAITER LES POINTS DEMANDES EN PLUS PAR CL DESIGN
    if( $('.acf-field-597ef77c58edb').length ){
        var note = $('.key-notation');
        $('.acf-field-597ef77c58edb .titre_sub_title').append(note.removeClass('undisplay'));
    }
    if( $('.acf-field-597ef79f58edc').length ){
        var note = $('.star-notation');
        $('.acf-field-597ef79f58edc .titre_sub_title').append(note.removeClass('undisplay'));
    }

    if( $('#acf-field_59087c1b5ce07').length && $('#acf-field_59087c1b5ce07').val() == '' ){
        $('#acf-field_59087c1b5ce07').val($('.tpl-annonces-ajouts').data('commercial'));
    }
    if( $('#acf-field_59087c305ce08').length && $('#acf-field_59087c305ce08').val() == '' ){
        $('#acf-field_59087c305ce08').val($('.tpl-annonces-ajouts').data('ctel'));
    }
    if( $('#acf-field_59a80271aaa10').length && $('#acf-field_59a80271aaa10').val() == '' ){
        $('#acf-field_59a80271aaa10').val($('.tpl-annonces-ajouts').data('cmail'));
    }

    if( $('#acf-field_597603552fc45').length && $('#acf-field_5900742378a55').length ){
        calcul_le_pourcentage_du_prix_de_vente();
    };
    $('body').on('change', '#acf-field_597603552fc45', function(){ 
        calcul_le_pourcentage_du_prix_de_vente(); 
    }); 
    $('body').on('change', '#acf-field_5900742378a55', function(){ 
        calcul_le_pourcentage_du_prix_de_vente(); 
    });

    function calcul_le_pourcentage_du_prix_de_vente(){
        // On calcul uniquement si on est pas dans un mandat de recherche 
        // cf. mail = Date : Mon, 9 Sep 2019 08:13:45 +0200 De : Arnaud Vayssières <avayssieres@delacouraujardin.com> (envoyé sur wami)
        if( !$('#acf-field_597609cc2fc49-mandat_de_recherche').is(":checked") ) {
            var prix_avec_honoraire = $('#acf-field_597603552fc45').val(); 
            var prix_sans_honoraire = $('#acf-field_5900742378a55').val(); 
            var prix_honoraire = prix_avec_honoraire - prix_sans_honoraire; 
            if( prix_sans_honoraire != 0 && prix_avec_honoraire != 0 ){
                $('.acf-field-5900742378a55').parent().find('.pourcentage').remove();
                var pourcentage = prix_honoraire / prix_sans_honoraire * 100;
                $('.acf-field-5900742378a55').after('<p class="info_divers pourcentage">Soit '+pourcentage.toFixed(2)+'% du prix de vente</p>');
            }
        } 
    };


    
    // CREER UNE ANNONCE : LOCALITE DU BIEN 
    // affichage en fonction de la région et du lieu 
    //on teste si on trouve sur la page localite du bien et si elle est ouverte
    if( $('.sub-section_region').length ){
        ///au chargement de la page on affiche les bons bloc correspondant aux input checked
        var region_prev   = $('.region li input:checked').attr('id'),
            ville_prev    = $('.ville li input:checked').attr('id'),
            district_prev = $('#tax_district li input:checked').length ? $('#tax_district li input:checked').attr('id') : '',
            region_new    = region_prev,
            ville_new     = ville_prev, 
            district_new  = district_prev;

        $('.ville li input[data-region="'+ region_prev +'"]').parents('li').addClass('visible');
        $('.lieu li input[data-ville="'+ ville_prev +'"]').parents('li').addClass('visible');

        //au changement de valeur des input radio on lanca la fonction choix localite
        $( ".region input[type=radio]" ).on( "change", function(){
            choix_localite('region', 'ville', region_prev);
            region_new = $(this).attr('id');
            if( region_new != '' && $(this).data('type')!='hors-carte' ) $('.bloc_localite.ville').removeClass('undisplay');
            //change_hotspot_list($(this).attr('id'), region_new, ville_new, district_new);
        });

        $( ".ville input[type=radio]" ).on( "change", function(){            
            $('.bloc_localite.lieu').removeClass('undisplay');
            $('.loader').addClass('actif');
            district_new = '';
            $("#add_ville").val('');
            choix_localite('ville', 'lieu', ville_prev);
            ville_new = $(this).attr('id');
            change_hotspot_list($(this).attr('id'), region_new, ville_new, district_new);  
        });  
        //$("#add_ville").blur(function() {
        $('body').on('submit', '#add_ville_popin', function(e){
            if( $("#add_ville_name").val() != '' && $("#add_ville_cp").val() != '' ){                
                $('.ville li input:checked').prop( "checked", false );
                $('.lieu li input:checked').prop( "checked", false );
                $('.lieu li.visible').removeClass('visible');
                ville_new = {
                    'new':1, 
                    'nom':$("#add_ville_name").val(),                    
                    'cp':$("#add_ville_cp").val()
                };
                $('#add_ville_popin').addClass('close');
                if($('#selected_new_ville').length)
                    $('#selected_new_ville').html('<label class="selectit"><input name="tax_input[lieu][ville]" checked="checked" type="radio"> '+ville_new.nom+'</label>');
                else 
                    $('<ul><li class="wpseo-term-unchecked visible" id="selected_new_ville">\
                                    <label class="selectit"><input name="tax_input[lieu][ville]" checked="checked" type="radio"> '+ville_new.nom+'</label>\
                                    </li></ul>').insertBefore('.bloc_localite.ville .addville');
                change_hotspot_list($(this).attr('id'), region_new, ville_new, district_new);  
            }
        });  

        $( ".lieu input[type=radio]" ).on( "change", function(){
            $('.loader').addClass('actif');
            district_new = $(this).attr('id');
            change_hotspot_list($(this).attr('id'), region_new, ville_new, district_new);  
        });          
        //$("#add_lieudit").blur(function() {
        $('body').on('submit', '#add_lieudit_popin', function(e){
            if( $("#add_lieudit_name").val() != '' && $("#add_lieudit_cp").val() != '' ){                
                $('.lieu li input:checked').prop( "checked", false );
                district_new = {
                    'new':1, 
                    'nom':$("#add_lieudit_name").val(),
                    'cp':$("add_lieudit_cp").val()
                };
                $('#add_lieudit_popin').addClass('close');
                if($('#selected_new_district').length)
                    $('#selected_new_district').html('<label class="selectit"><input name="tax_input[lieu][arrondissement]" checked="checked" type="radio"> '+district_new.nom+'</label>');
                else 
                    $('<ul><li class="wpseo-term-unchecked visible" id="selected_new_district">\
                                    <label class="selectit"><input name="tax_input[lieu][arrondissement]" checked="checked" type="radio"> '+district_new.nom+'</label>\
                                    </li></ul>').insertBefore('.bloc_localite.lieu .addlieudit');
                change_hotspot_list($(this).attr('id'), region_new, ville_new, district_new);  
            }
        });   
    }


    function choix_localite(type_parent, type_enfant, choix) {
        //deselectionne l'ancien choix enfant et on le cache
        $('.lieu li.visible').removeClass('visible');
        $('.'+ type_enfant +' li input:checked').prop( "checked", false );
        $('.'+ type_enfant +' li.visible').removeClass('visible');
        choix = $('.'+ type_parent +' li input:checked').attr('id');
        //on affiche les nouveaux bloc enfant
        $('.'+ type_enfant +' li input[data-'+ type_parent +'="'+ choix +'"]').parents('li').addClass('visible'); 

        $('.ps-scrollbar-y-rail.ws').removeClass('ws');
        
        var contenu_actif_height = 0;
        $('.'+ type_enfant +' li input[data-'+ type_parent +'="'+ choix +'"]').parents('li').each(function(){
            contenu_actif_height += $(this).height() + 5;
        });
        var conteneur_height     = $('.'+ type_enfant).parent().height() - 65;
        //console.log(contenu_actif_height+' >< '+conteneur_height);
        if(contenu_actif_height>0 && contenu_actif_height>conteneur_height)
            $('.'+ type_enfant +' li input[data-'+ type_parent +'="'+ choix +'"]').parents('ul').find('.ps-scrollbar-y-rail').addClass('ws');
        
              
        //if(contenu_actif_height>0 && contenu_actif_height<conteneur_height) $('.'+ type_enfant).parent().css('height', contenu_actif_height);
        
        //var contenur_height = $('.limited-content').height();
        //var ville_height    = $('.ville').height();
        //var lieu_height     = $('.lieu').height();
        /*if(ville_height > contenur_height) {
            $('.ville').css({
              'background': ''
            });
            $('.ville .ps__rail-x, .ville .ps__rail-y').css({
              'opacity': '0.6'
            });
            $('.ps-container').addClass('ws');
        } */ 
    };


    function change_hotspot_list(lieu, region_new, ville_new, district_new){
        $.ajax({
            url:wami_js.ajaxurl,
            data:{
                action:'wami_add_bien_localite',
                data:lieu,
                region: region_new, 
                ville: ville_new, 
                district: district_new
            },
            success:function(data){
                $('.hotspot_conteneur').html(data);
                $('.loader').removeClass('actif');
            },
            error: function(){
                $('.hotspot_conteneur').html('Une erreur est survenue, merci de réessayer.');
                $('.loader').addClass('actif');
            }
        });
    };


    $('body').on('submit', '#form_to_add_hotspot', function(e){
        e.preventDefault();
        var hotspot  =  '<li class="new_hotspot" data-nom="'+$('#add_hotspot_name').val()+'" data-adresse="'+$('#add_hotspot_adresse').val()+'">\
                            <p><b>'+$('#add_hotspot_name').val()+'</b></p>\
                            <p>'+$('#add_hotspot_adresse').val()+'</p>\
                        </li>';
        // On append notre nouvel hotspot
        if( $('.hotspot_conteneur ul.hotspot').length ) { 
            $('.hotspot_conteneur ul.hotspot').append(hotspot);                
        }
        else {
            $('<ul class="hotspot">'+hotspot+'</ul>').insertBefore('.hotspot_conteneur .addhotspot');    
        }
        // on change le message d'info
        if($('.hotspot_conteneur p.infos').length) 
            $('.hotspot_conteneur p.infos').html('Cliquez sur "enregistrer" pour valider la localité et ce/ces hotspot(s).');
        else 
            $('<p class="infos">Cliquez sur "enregistrer" pour valider la localité et ce/ces hotspot(s).</p>').insertBefore('.hotspot_conteneur ul.hotspot');
        // on ferme la popin        
        $('#add_hotspot').addClass('close');  
        // on resset les champs  
        $('#add_hotspot_name').val( function(){ return this.defaultValue; } ); 
        $('#add_hotspot_adresse').val( function(){ return this.defaultValue; } );   
    });


    $('#enregistre_localite_et_hotspot').on('click', function(e){        
        e.preventDefault();

        $('.loader').addClass('actif');
        
        var hotspots = [];
        $('.hotspot_conteneur .new_hotspot').each(function(){
            hotspots.push({
                'nom':$(this).data('nom'), 
                'adresse':$(this).data('adresse')
            });
        });
        var datas = {
            post_id: $(this).data('postid'),
            region: region_new, 
            ville: ville_new, 
            district: district_new, 
            hotspot: hotspots
        };

        $.ajax({
            url:wami_js.ajaxurl,
            data:{
                action:'wami_enregistre_lieu_et_hotspot',
                data: datas,
            },
            success:function(data){  
                // $('.loader').removeClass('actif');
                // $('.hotspot_conteneur').html(data);
                window.location.href = data;
            },
            error:function(){
                console.log('Une erreur est survenue, merci de réessayer.');
            }
        });
    });


    $('.popin.vendupartiers').on('click', '.confirmer', function(event){
        event.preventDefault();
        $('.loader').addClass('actif');
        var mandat_id = $(this).data('mandatid');
        var go_to = $(this).data('goto');
        if(mandat_id && mandat_id!="" && mandat_id!=undefined){
            $.ajax({
                url:wami_js.ajaxurl,
                data:{
                    action: 'wami_delete_mandat',
                    mandat_id: mandat_id,
                },                
                success:function(data){ 
                    //console.log(data);
                    //$('.loader').removeClass('actif');
                    //$('#'+ $(this).data('closepopin') ).addClass('close');
                    if(data.ID == mandat_id){                    
                        //$('.annonce_mandat_'+mandat_id).remove(); 
                        window.location.href = go_to;
                    }
                    else alert('Une erreur est survenue, merci de réessayer.');
                },
                error:function(e){console.log('Une erreur est survenue, merci de réessayer.');}
            }); 
        } 
    });



});