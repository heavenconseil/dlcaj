<?php

/* Gestion des noms des formats dans l'editeur de text des articles et pages */
add_filter('tiny_mce_before_init', 'wami_restrict_wysiwyg_tags');
function wami_restrict_wysiwyg_tags($arr){
	$arr['block_formats'] = 'Paragraphe=p; Titre=h2; Sous-titre=h3; Titre h4=h4; Titre h5=h5; Titre h6=h6; Préformaté=pre';
	return $arr; 
}

/* Fonctions permettant de géré la forme des extraits de WP */
// add_filter( 'excerpt_length', 'wamitheme_excerpt_length' );
function wamitheme_excerpt_length( $length ) {
    return 42;
}
// add_filter( 'excerpt_more', 'wami_continue_reading' );
function wami_continue_reading() {
    return ' ...<br /><a href="'.get_permalink().'" class="voir_plus suite">'.__('> Lire la suite', 'wami').'</a>';
}

/* Fonction pour retourner un extrait personnalisé */
// via le post_id
function wami_return_extrait($post_id, $longueur=250){   
    $post = get_post($post_id);
    $monextrait = strip_tags($post->post_content);
    $monextrait = strip_shortcodes($monextrait);   
    if(strlen($monextrait) > $longueur){
        $monextrait = substr($monextrait, 0, $longueur);
        $monextrait = substr($monextrait, 0,  strrpos($monextrait," "))." ...";
    } 
    return $monextrait;
}
// via le contenu (à n'utiliser que si celle par ID n'est pas utilisable)
function wami_return_small($contenu, $longueur=250){    
    $monextrait = strip_tags($contenu);
    $monextrait = strip_shortcodes($monextrait);   
    if(strlen($monextrait) > $longueur){
        $monextrait = substr($monextrait, 0, $longueur);
        $monextrait = substr($monextrait, 0,  strrpos($monextrait," "))." ...";
    } 
    return $monextrait;
}

function wami_get_regiondubien($postid){
    $args = array(
        //'childless'=>1,
        'meta_query'    => array(                   
            array(
                'key'       => 'type_de_lieu',
                'value'     => 'region',
                'compare'   => '='
            ),
        ),
    );
    $lieu = wp_get_post_terms($postid, 'lieu', $args);
    if( $lieu && is_array($lieu) )  return $lieu[0]->name;
    else return false;
}


function wami_get_villedubien($postid){
	$args = array(
		//'childless'=>1,
		'meta_query'	=> array(            		
    		array(
    			'key' 		=> 'type_de_lieu',
    			'value' 	=> 'ville',
    			'compare' 	=> '='
    		),
    	),
	);
	$lieu = wp_get_post_terms($postid, 'lieu', $args);
    if( $lieu && is_array($lieu) )  return $lieu[0]->name;
    else return false;
}

function wami_get_cpdelavilledubien($postid){
    $args = array(
        //'childless'=>1,
        'meta_query'    => array(                   
            array(
                'key'       => 'type_de_lieu',
                'value'     => 'ville',
                'compare'   => '='
            ),
        ),
    );
    $lieu = wp_get_post_terms($postid, 'lieu', $args);
    if( $lieu && is_array($lieu) )  return get_field("code_postale", $lieu[0]->taxonomy.'_'.$lieu[0]->term_id);
    else return false;
}

function wami_get_cpdubien($postid){
    $args = array(
        //'childless'=>1,
        'meta_query'    => array(                   
            array(
                'key'       => 'type_de_lieu',
                'value'     => 'district',
                'compare'   => '='
            ),
        ),
    );
    $lieu = wp_get_post_terms($postid, 'lieu', $args);
    if(!$lieu) { 
        $args = array(
            //'childless'=>1,
            'meta_query'    => array(                   
                array(
                    'key'       => 'type_de_lieu',
                    'value'     => 'ville',
                    'compare'   => '='
                ),
            ),
        );
        $lieu = wp_get_post_terms($postid, 'lieu', $args);
    }   
    if( isset($lieu) && is_array($lieu) && !empty($lieu) )
        return get_field("code_postale", $lieu[0]->taxonomy.'_'.$lieu[0]->term_id);
    else 
        return false;
}

function wami_get_last_localite_bien($postid){
    $my_lieu = array();
    $args = array(
        'meta_query'    => array(                   
            array(
                'key'       => 'type_de_lieu',
                'value'     => 'district',
                'compare'   => '='
            ),
        ),
    );
    $lieu = wp_get_post_terms($postid, 'lieu', $args);
    if($lieu){
        $my_lieu['type']  = 'district';
        $my_lieu['lieu']  = $lieu[0];        
        $ville = get_term_by( 'id', $lieu[0]->parent, 'lieu');
        $my_lieu['ville'] = $ville;  
        $my_lieu['region'] = get_term_by( 'id', $ville->parent, 'lieu');  
    }
    else {
        $args = array(
            'meta_query'    => array(                   
                array(
                    'key'       => 'type_de_lieu',
                    'value'     => 'ville',
                    'compare'   => '='
                ),
            ),
        );
        $lieu = wp_get_post_terms($postid, 'lieu', $args);
        $my_lieu['type'] = 'ville';
        if(isset($lieu) && is_array($lieu) && !empty($lieu) ) {
            $my_lieu['lieu'] = $lieu[0]; 
            $region = get_term_by( 'id', $lieu[0]->parent, 'lieu');
            $my_lieu['region'] = $region;
        } else {
            $my_lieu['type'] = "";
            $my_lieu['lieu'] = ""; 
            $my_lieu['region'] = "";
        }   
    } 
    return (object) $my_lieu;
}


function get_palier_conso_kwh($value){
    $pallier = "7";
    $palliers = array("6"=>450, "5"=>330, "4"=>230, "3"=>150, "2"=>90, "1"=>50);
    foreach($palliers as $k=>$p){
        if($value<=$p) $pallier=$k;
    }
    return $pallier;
}

function get_palier_conso_co2($value){
    $pallier = "7";
    $palliers = array("6"=>79, "5"=>55, "4"=>35, "3"=>20, "2"=>10, "1"=>5);
    foreach($palliers as $k=>$p){
        if($value<=$p) $pallier=$k;
    }
    return $pallier;
}

function wami_cacul_charme_du_bien($post_ID){
    $points = $total = 0;
    if(get_field('bien_charme_vu', $post_ID)) $points += 3;
    if(get_field('bien_charme_terrasse', $post_ID)) $points += 3;
    if(get_field('bien_charme_veranda', $post_ID)) $points += 2;
    if(get_field('bien_charme_piscine', $post_ID)) $points += 2;
    if(get_field('bien_charme_volumes', $post_ID)) $points += 3;
    if(get_field('bien_charme_cour', $post_ID)) $points += 3;
    if(get_field('bien_charme_jardin', $post_ID)) $points += 3;
    if(get_field('bien_charme_construction', $post_ID)) $points += 2;
    if(get_field('bien_charme_plan', $post_ID)) $points += 2;
    if(get_field('bien_charme_luminosite', $post_ID)) $points += 2;
    if(get_field('bien_charme_materiaux', $post_ID)) $points += 2;
    if(get_field('bien_charme_mezzanine', $post_ID)) $points += 2;
    if(get_field('bien_charme_perron', $post_ID)) $points += 1;
    if(get_field('bien_charme_loft', $post_ID)) $points += 3;
    if(get_field('bien_charme_cheminee', $post_ID)) $points += 2;
    if(get_field('bien_charme_atmosphere', $post_ID)) $points += 1;
    if(get_field('bien_charme_authenticite', $post_ID)) $points += 1;
    if(get_field('bien_charme_bienclasse', $post_ID)) $points += 3;
    if(get_field('bien_charme_visavis', $post_ID)) $points += 2;
    if(get_field('bien_charme_patio', $post_ID)) $points += 2;
    if(get_field('bien_charme_etage', $post_ID)) $points += 2;    
    $bareme = array(
        5=>1,
        8=>1.5,
        10=>2,
        13=>2.5,
        15=>3,
        18=>3.5,
        20=>4,
        23=>4.5,
        25=>5
    );
    foreach( $bareme as $b=>$v ){
        if( $points >= $b ) $total = $v;
    };
    return $total;
}

function wami_cacul_environnement_du_bien($post_ID){
    $points = $total = 0;
    if(get_field('bien_environnement_acces_tgv', $post_ID)) $points += 3;
    if(get_field('bien_environnement_acces_transport_route_excellents', $post_ID)) $points += 3;
    if(get_field('bien_environnement_acces_transport_route_faciles', $post_ID)) $points += 2;
    if(get_field('bien_environnement_commodites_tres_proches', $post_ID)) $points += 3;
    if(get_field('bien_environnement_commodites_proches', $post_ID)) $points += 2;
    if(get_field('bien_environnement_grand_charme', $post_ID)) $points += 3;
    if(get_field('bien_environnement_charme', $post_ID)) $points += 2;
    if(get_field('bien_environnement_tres_calme', $post_ID)) $points += 3;
    if(get_field('bien_environnement_calme', $post_ID)) $points += 2;
    if(get_field('bien_environnement_tres_urbain', $post_ID)) $points += 3;
    if(get_field('bien_environnement_urbain', $post_ID)) $points += 2;
    $bareme = array(
        2=>1,
        4=>1.5,
        5=>2,
        6=>2.5,
        7=>3,
        8=>3.5,
        9=>4,
        10=>4.5,
        11=>5
    );
    foreach( $bareme as $b=>$v ){
        if( $points >= $b ) $total = $v;
    };
    return $total;
}


function incremente_le_compteur_de_visite_de_ce_bien($post_id, $author_id){
    // si la personne qui consulte la page n'est pas l'auteur de l'annonce 
    $user = wp_get_current_user();
    if( (is_object($user) && $user->ID != $author_id) || !$user ) {
        // On incremente le nb de visite
        $prev_value = intval( get_post_meta( $post_id, 'stat_nombre_de_visite', true ) );
        $meta_value = $prev_value + 1; 
        update_post_meta( $post_id, 'stat_nombre_de_visite', $meta_value ); 
    }  
}

function wami_return_annonces_medium($post){
    //debug($post->post_title);    
    $title = wami_return_small($post->post_title, 48);
    $region = wami_get_regiondubien($post->ID);
    $ville = wami_get_villedubien($post->ID);
    $content = '
        <div class="annonce-cover '.$post->id.'">
            <a href="'.get_permalink($post->ID).'">';
                if(has_post_thumbnail($post->ID))  
                    $content .= '<img src="'.get_the_post_thumbnail_url($post->ID, 'paysage_medium').'">';           
                 else 
                    $content .= '<img src="http://placehold.it/350x230">'; 
                
                if(get_field("lien_de_la_visite_360", $post->ID) || get_field("iframe_de_la_visite_360", $post->ID)) : 
                    //<!-- <a href="'.get_field("lien_de_la_visite_360").'"> -->
                        $content .= '<span class="cover-label label-360">visiter</span>';
                    //<!-- </a> -->
                endif; 
            $content .= '</a>
            <span class="annonce-hover-bar"></span>
        </div>
        <article>   
            <h4 class="annonce-title"><a href="'.get_permalink($post->ID).'">'.$title.'</a></h4>
            <ul class="annonce-primary">';
                $content .= get_field('bien_prix_et_honoraires', $post->ID) ? '<li class="annonce-price">'.number_format(get_field('bien_prix_et_honoraires', $post->ID), 0, '', ' ').' €</li>' : ""; 
            $content .= '</ul>    
            <ul class="annonce-localite">
                <li class="annonce-region">'.$region.'</li>
                <li class="annonce-city">'.$ville.'</li>
            </ul>
            <ul class="annonce-secondary">';
                $content .= get_field('bien_surface_habitable', $post->ID) ? '<li class="annonce-metter">'.get_field('bien_surface_habitable', $post->ID).' m²</li>' : ""; 
               
                if(get_field('bien_nb_chambre', $post->ID)) {
                    $content .= '<li class="annonce-bedroom">';
                        $content .= get_field('bien_nb_chambre', $post->ID).' chambre'; 
                        if(get_field('bien_nb_chambre', $post->ID)>1) $content .= 's'; 
                    $content .= '</li>';
                } 
                if(get_field('bien_nb_piece_eau', $post->ID)) {
                    $content .= '<li class="annonce-bathroom">';
                        $content .= get_field('bien_nb_piece_eau', $post->ID); 
                        if(get_field('bien_nb_piece_eau', $post->ID)>1) $content .= ' salles d\'eau'; else $content .= ' salle d\'eau';
                    $content .= '</li>';
                } 
                if(get_field('bien_nb_piece_de_bain', $post->ID)) {
                    $content .= '<li class="annonce-bathroom">';
                        $content .= get_field('bien_nb_piece_de_bain', $post->ID); 
                        if(get_field('bien_nb_piece_de_bain', $post->ID)>1) $content .= ' salles de bain'; else $content .= ' salle de bain';
                    $content .= '</li>';
                } 
            $content .= '</ul>
            <ul class="annonce-complementary">
                <li class="annonce-environnement">
                    <span class="label">Environnement</span>
                    <ul class="star-notation">';
                        $note = wami_cacul_environnement_du_bien($post->ID);
                        for( $i=1; $i<6; $i++) {
                            if($note>=1) $content .=  '<li class="star star-on"></li>';
                            else if($note>0) $content .=  '<li class="star star-float"></li>';
                            else $content .=  '<li class="star star-off"></li>';
                            $note--;
                        } 
                    $content .= '</ul>
                </li>
                <li class="annonce-charme">
                    <span class="label">Charme</span>
                    <ul class="key-notation">';
                        $note = wami_cacul_charme_du_bien($post->ID);
                        for( $i=1; $i<6; $i++) {
                            if($note>=1) $content .= '<li class="key key-on"></li>';
                            else if($note>0) $content .= '<li class="key key-float"></li>';
                            else $content .= '<li class="key key-off"></li>';
                            $note--;
                        } 
                    $content .= '</ul>
                </li>
            </ul>';
            if(have_rows('infos_complementaires')) : $i = 0;
            $content .= '<ul class="annonce-complementary">';       
                while(have_rows('infos_complementaires')): 
                    the_row(); 
                    if($i < 2) :            
                       $content .= '<li>'.get_sub_field("informations", $post->ID).'</li>';
                    endif; 
                $i++;
                endwhile; 
            $content .= '</ul>';
            endif;    
        $content .= '</article>';

    return $content;
}