<?php

	// VENTE EFFECTUEES SUR LA REGION
    function ventes_effectuees_sur_la_region($ambassadeur_id, $region_slug=false, $region=false){  
    	global $post;
        $class = "";
        $ca = 0; 
        $args = array(
            'post_type'     => 'biens',
            'post_status'   => 'publish',
            //'author__in'    => array( $ambassadeur_id ),            
            'meta_query'    => array( 
                'relation'  => 'AND', 
                array(
                    'key'       => 'lien_mandat',
                    'value'     => '',
                    'compare'   => '!='
                ),              
                array(
                    'key'       => 'bien_vente_vendu',
                    'value'     => 1,
                    'compare'   => '='
                ),                 
            ),
            'posts_per_page'=> -1
        );
        if($region)
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'lieu',
                    'field'    => 'slug',
                    'terms'    => $region_slug,
                ),
            );
        $query_biens = new WP_Query( $args );
        if($query_biens->have_posts()) :
            $total = $query_biens->post_count; 
            $i = 0;
            $titre = $total>1 ? 'Ventes effectuées'.( $region ? ' sur la région' : "") : 'Vente effectuée'.( $region ? ' sur la région' : "");           
            $month_archive = array();

            $bloc = '<h4>'.$total.' '.$titre.'</h4>';
            $bloc .= '<div class="grid-col col_size-6">';
                $bloc .= '<form role="search" method="get" class="single-field filtre_form_stat" id="filtre_annee">';
                    $bloc .= '<select name="periode">';                    
                    $bloc .= '<option value="">Sélectionner un mois</option>';
                    while($query_biens->have_posts()) :
                        $query_biens->the_post(); 
                            $date_my = get_field('bien_vente_date_signature_acte', $post->ID) ? date_i18n('m-y', strtotime(get_field('bien_vente_date_signature_acte', $post->ID))) : get_the_date('m-y', $post->ID);
                            $date_FY = get_field('bien_vente_date_signature_acte', $post->ID) ? date_i18n('F Y', strtotime(get_field('bien_vente_date_signature_acte', $post->ID))) : get_the_date('F Y', $post->ID);
                    		if( !array_key_exists($date_my, $month_archive) ){
                            	$bloc .= '<option value="'.$date_my.'">'.$date_FY.'</option>';
                            	$month_archive[$date_my] = $date_my;
                            }
                    endwhile;
                    $bloc .= '</select>';
                    $bloc .= '<input type="submit" value="ok" class="right-arrow'.$class.'">';
                $bloc .= '</form>';
            $bloc .= '</div>';
            $bloc .= '<div class="grid-col col_size-6">';
                $bloc .= '<div class="liste">';
                    $bloc .= '<h5>liste des ventes effectuées</h5>';
                    $year = date("Y"); 
                    $date_debut_exo = strtotime("01 september ".$year); 
                    // attention si la date de debut d'exo n'est pas encore passé c'est qu'e l'année n'est pas bonne
                    if( strtotime($date_debut_exo) < strtotime('now') ) {    
                        $year = date("Y")-1;                     
                        $date_debut_exo = strtotime("01 september ".$year);
                    }
                    while($query_biens->have_posts()) :
                        $query_biens->the_post();                                 
                                $date_my    = get_the_date('m-y', $post->ID);
                                $date_vente = get_field('bien_vente_date_signature_acte', $post->ID);
                                if($date_vente) {
                                    $format_in  = 'd/m/Y';
                                    $format_out = 'm-y';
                                    $date       = DateTime::createFromFormat($format_in, $date_vente);
                                    $date_my    = $date->format($format_out);
                                    $n_date_vente = $date->format('U');  
                                }
                                if($i == 0 ) $bloc .= '<div>'; 
                                if($i == 3 ) $bloc .= '<div class="liste_deroulante hide">';
                                $bloc .= '<p><a href="'.wami_get_page_link('ajouter-annonce').'/'.$post->ID.'" class="dossier dossier_'.sprintf("%06d", get_field('bien_ref', $post->ID)).' periode_'.$date_my.'">Dossier n°'.sprintf("%06d", get_field('bien_ref', $post->ID)).'</a></p>';
                                if($i == 2 || $i == $total-1) $bloc .= '</div>';
                            $i++; 
                            // demande "Il faudrait permettre l'arrêt de la comptabilisation des honoraires au 31/08 (début de compta au 01/09)"
                            if( $n_date_vente > $date_debut_exo ){ 
                                $bloc .= $post->ID;   
                                $ca = $ca + get_field('bien_honoraires_montant',$post->ID);
                            }
                    endwhile; 
                $bloc .= '</div>';
            $bloc .= '</div>';
            wp_reset_postdata(); 
        endif;
        
        return (object) array(
        	'total' => $total,
        	'ca'	=> $ca,
        	'titre' => '€ d\'honoraires générés depuis le 01/09/'.$year,
        	'bloc'  => $bloc,
        );
    }

    // PREVISION DES VENTES SUR LA REGION
    function prevision_des_ventes_sur_la_region($ambassadeur_id, $region_slug=false, $region=false){ 
        global $post;
        $class = "";
        $args = array(
            'post_type'     => 'biens',
            'post_status'   => 'publish',
            //'author__in'    => array( $ambassadeur_id ),           
            'meta_query'    => array( 
                'relation'  => 'AND', 
                array(
                    'key'       => 'lien_mandat',
                    'value'     => '',
                    'compare'   => '!='
                ),            
                array(
                    'key'       => 'bien_vente_date_signature_promesse',
                    'value'     => '',
                    'compare'   => '!='
                ),        
                array(
                    'relation' => 'OR',
                    array(
                        'key'       => 'bien_vente_vendu',
                        'value'     => 0,
                        'compare'   => '='
                        ),
                    array(
                      'key' => 'bien_vente_vendu',
                      'compare' => 'NOT EXISTS'
                    ),   
                ),           
            ),
            'posts_per_page'=> -1
        );        
        if($region)
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'lieu',
                    'field'    => 'slug',
                    'terms'    => $region_slug,
                ),
            );
        $query_biens = new WP_Query( $args );
        if($query_biens->have_posts()) :
            $total = $query_biens->post_count; 
            $i = 0;
            $titre = $total>1 ? 'Prévisions de vente'.( $region ? ' sur la région' : "") : 'Prévision de vente'.( $region ? ' sur la région' : "");
            $month_archive = array();
            
            $bloc = '<h4>'.$total.' '.$titre.'</h4>';
            $bloc .= '<div class="grid-col col_size-12">';
                $bloc .= '<form role="search" method="get" class="single-field filtre_form_stat" id="filtre_annee">';
                    $bloc .= '<select name="periode">';
                    $bloc .= '<option value="">Sélectionner un mois</option>';
                    while($query_biens->have_posts()) :
                        $query_biens->the_post(); 
                            $date_my = get_field('bien_vente_date_signature_promesse', $post->ID) ? date_i18n('m-y', return_french_date_to_timestamp('d/m/Y', get_field('bien_vente_date_signature_promesse', $post->ID))) : get_the_date('m-y', $post->ID);
                            $date_FY = get_field('bien_vente_date_signature_promesse', $post->ID) ? date_i18n('F Y', return_french_date_to_timestamp('d/m/Y', get_field('bien_vente_date_signature_promesse', $post->ID))) : get_the_date('F Y', $post->ID);
                            if( !array_key_exists($date_my, $month_archive) ){
                            	$bloc .= '<option value="'.$date_my.'">'.$date_FY.'</option>';
                            	$month_archive[$date_my] = $date_my;
                            }
                    endwhile;
                    $bloc .= '</select>';
                    $bloc .= '<input type="submit" value="ok" class="right-arrow'.$class.'">';
                $bloc .= '</form>';
            $bloc .= '</div>';
            $bloc .= '<div class="grid-col col_size-12">';
                $bloc .= '<div class="liste">';
                    $bloc .= '<h5>liste des ventes à venir</h5>';
                    while($query_biens->have_posts()) :
                        $query_biens->the_post(); 
                                $date_my = get_field('bien_vente_date_signature_promesse', $post->ID) ? date_i18n('m-y', return_french_date_to_timestamp('d/m/Y', get_field('bien_vente_date_signature_promesse', $post->ID))) : get_the_date('m-y', $post->ID);
                                if($i == 0 ) $bloc .= '<div>'; 
                                if($i == 2 ) $bloc .= '<div class="liste_deroulante hide">';
                                $bloc .= '<p><a href="'.wami_get_page_link('ajouter-annonce').'/'.$post->ID.'" class="dossier dossier_'.sprintf("%06d", get_field('bien_ref', $post->ID)).' periode_'.$date_my.'">Dossier n°'.sprintf("%06d", get_field('bien_ref', $post->ID)).'</a></p>';
                                if($i == 1 || $i == $total-1) $bloc .= '</div>';
                            $i++; 
                    endwhile; 
                $bloc .= '</div>';
            $bloc .= '</div>';
            wp_reset_postdata(); 
        endif;  

        return (object) array(
        	'total' => $total,
        	'titre' => $total>1 ? 'Ventes en cours' : 'Vente en cours',
        	'bloc'  => $bloc,
        );
    }

    // MANDATS SUR LA REGION
    function mandats_en_cours_sur_la_region($ambassadeur_id, $region_slug=false, $region=false){  
        global $post;
        $class = "";
        $args = array(
            'post_type'     => 'biens',
            //'post_status'   => 'publish',
            //'author__in'    => array( $ambassadeur_id ), 
            'posts_per_page'=> -1
        );       
        if($region)
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'lieu',
                    'field'    => 'slug',
                    'terms'    => $region_slug,
                ),
            );
        $query_biens = new WP_Query( $args );
        if($query_biens->have_posts()) :
            $total = $query_biens->post_count; 
            $i = 0;
            $titre = $total>1 ? 'Mandats en cours'.( $region ? ' sur la région' : "") : 'Mandat en cours'.( $region ? ' sur la région' : "");
            
            $bloc = '<h4>'.$total.' '.$titre.'</h4>';
            $bloc .= '<div class="grid-col col_size-4 tablet_size-12 mobile_size-12">';
                $bloc .= '<form role="search" method="get" class="single-field search_form_stat" id="search_vente_annee">';
                    $bloc .= '<input type="text" placeholder="'.__("Rechercher", 'wami').'" value="" />';
                    $bloc .= '<input type="submit" value="ok" class="right-arrow'.$class.'">';                    
                $bloc .= '</form>';
            $bloc .= '</div>';
            $bloc .= '<div class="grid-col col_size-4 tablet_size-12 mobile_size-12">';
                $bloc .= '<div class="liste">';
                    $bloc .= '<h5>liste des ventes effectuées</h5>';
                    while($query_biens->have_posts()) :
                        $query_biens->the_post(); 
                                if($i == 0 ) $bloc .= '<div>'; 
                                if($i == 3 ) $bloc .= '<div class="liste_deroulante hide">';
                                $bloc .= '<p><a href="'.wami_get_page_link('ajouter-annonce').'/'.$post->ID.'" class="dossier dossier_'.sprintf("%06d", get_field('bien_ref', $post->ID)).'">Dossier n°'.sprintf("%06d", get_field('bien_ref', $post->ID)).'</a></p>';
                                if($i == 2 || $i == $total-1) $bloc .= '</div>';
                            $i++; 
                    endwhile; 
                $bloc .= '</div>';
            $bloc .= '</div>';
            $bloc .= '<div class="grid-col col_size-4 tablet_size-12 mobile_size-12">';
                $bloc .= '<p class="resultat_recherche_titre">Résultat de votre recherche : </p><div class="resultat_recherche"></div>';
            $bloc .= '</div>';
            wp_reset_postdata(); 
        endif;      

        return (object) array(
        	'total' => $total,
        	'titre' => $total>1 ? 'Mandats en cours' : 'Mandat en cours',
        	'bloc'  => $bloc,
        );
    }

    // COLLABORATEURS SUR LA REGION 
    function collaborateurs_sur_la_region($ambassadeur_id, $region_slug=false, $region=false){  
        global $post;
        $class = "";
        $args = array(
            //'role'         => 'ambassadeur',
            'role__in'     => array('ambassadeur', 'ambassadeur_responsable_de_region', 'administrator'),
            'exclude'      => array($ambassadeur_id),
            'meta_query'   => array(
                'relation'  => 'AND',    
                array(
                    'relation' => 'OR',
                    array(
                        'key'       => 'ambassadeur_de_la_cour_au_jardin',
                        'value'     => 1,
                        'compare'   => '='
                    ),
                    array(
                      'key' => 'ambassadeur_de_la_cour_au_jardin',
                      'compare' => 'NOT EXISTS'
                    ), 
                )
            ),
         );               
        if($region){
            $args['meta_key']     = 'ambassadeur_region';
            $args['meta_value']   = $region->slug;
            $args['meta_compare'] = '=';  
        }
            
        $ambassadeur_list = get_users( $args ); 
        if(!empty($ambassadeur_list)) :
            //debug($ambassadeur_list);        
            $total = count($ambassadeur_list); 
            $i = 0; 
            $titre = $total>1 ? 'Collaborateurs'.( $region ? ' sur la région' : "").' <span class="small">(cliquez sur un collaborateur pour accéder à ses statistiques personnels)</span>' : 'Collaborateur '.( $region ? ' sur la région' : "").' <span class="small">(cliquez sur un collaborateur pour accéder à ses statistiques personnels)</span>';
                    
            $bloc = '<h4>'.$total.' '.$titre.'</h4>';
            $bloc .= '<div class="grid-col col_size-4 tablet_size-12 mobile_size-12">';
                $bloc .= '<form role="search" method="get" class="single-field search_form_amba" id="search_collaborateur">';
                    $bloc .= '<input type="text" placeholder="'.__("Rechercher", 'wami').'" value="" />';
                    $bloc .= '<input type="submit" value="ok" class="right-arrow'.$class.'">';
                $bloc .= '</form>';
            $bloc .= '</div>';
            $bloc .= '<div class="grid-col col_size-4 tablet_size-12 mobile_size-12">';
                $bloc .= '<div class="liste">';
                    $bloc .= '<h5>liste des ambassadeurs</h5>';
                    foreach( $ambassadeur_list as $ambassadeur ) : 
                        if($i == 0 ) $bloc .= '<div>'; 
                        if($i == 3 ) $bloc .= '<div class="liste_deroulante hide">';                               
                        $bloc .= '<p><a href="'.wami_get_page_link('management-team').'/'.$ambassadeur->ID.'" class="dossier ambassadeur_'.$ambassadeur->ID.' '.$ambassadeur->data->display_name.'">'.$ambassadeur->data->display_name.'</a></p>';
                        if($i == 2 || $i == $total-1) $bloc .= '</div>'; 
                        $i++; 
                    endforeach; 
                $bloc .= '</div>';
            $bloc .= '</div>';
            $bloc .= '<div class="grid-col col_size-4 tablet_size-12 mobile_size-12">';
                $bloc .= '<p class="resultat_recherche_titre">Résultat de votre recherche : </p><div class="resultat_recherche"></div>';
            $bloc .= '</div>';
            wp_reset_postdata();
        endif; 

        return (object) array(
        	'total' => $total,
        	'titre' => $total>1 ? 'Collaborateurs' : 'Collaborateur',
        	'bloc'  => $bloc,
        );
    }