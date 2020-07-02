<?php 

function wami_mo_request_annonces($user_id, $start, $offset, $trie_par){      
    global $wpdb; 

   /* $req = "SELECT p.ID 
            FROM ".$wpdb->prefix."posts AS p  
            LEFT JOIN ".$wpdb->prefix."postmeta ON ( p.ID = ".$wpdb->prefix."postmeta.post_id )  
            LEFT JOIN ".$wpdb->prefix."postmeta AS mt1 ON ( p.ID = mt1.post_id )  
            LEFT JOIN ".$wpdb->prefix."postmeta AS mt2 ON (p.ID = mt2.post_id AND mt2.meta_key = 'bien_vente_date_signature_promesse' )  
            LEFT JOIN ".$wpdb->prefix."postmeta AS mt3 ON ( p.ID = mt3.post_id )  
            LEFT JOIN ".$wpdb->prefix."postmeta AS mt4 ON (p.ID = mt4.post_id AND mt4.meta_key = 'bien_vente_vendu' ) 
            AND p.post_author IN (".$user_id.")  
            AND ( ".$wpdb->prefix."postmeta.meta_key = 'bien_ref' 
              AND 
              ( 
                ( 
                  ( mt1.meta_key = 'bien_vente_date_signature_promesse' AND mt1.meta_value = '' ) 
                  OR 
                  mt2.post_id IS NULL
                ) 
                AND 
                ( 
                  ( mt3.meta_key = 'bien_vente_vendu' AND mt3.meta_value = '0' ) 
                  OR 
                  mt4.post_id IS NULL
                )
              )
            ) 
            AND p.post_type = 'biens' 
            AND ((p.post_status = 'publish' OR p.post_status = 'draft' OR p.post_status = 'pending')) 
            GROUP BY p.ID ORDER BY ".$wpdb->prefix."postmeta.meta_value DESC LIMIT ".$start.", ".$offset.""; */
    $req = "
        SELECT p.ID
        FROM ".$wpdb->prefix."posts AS p  
        LEFT JOIN ".$wpdb->prefix."postmeta ON ( p.ID = ".$wpdb->prefix."postmeta.post_id ) 
        WHERE p.post_author IN (".$user_id.")          
    ";
    if($trie_par == 'mandat') $req .= "AND ".$wpdb->prefix."postmeta.meta_key = 'bien_ref'";
    elseif($trie_par == 'prix') $req .= "AND ".$wpdb->prefix."postmeta.meta_key = 'bien_prix'";
    $req .= "
        AND p.post_type = 'biens' 
        AND ((p.post_status = 'publish' OR p.post_status = 'draft' OR p.post_status = 'pending')) 
        GROUP BY p.ID 
    ";
    if($trie_par == 'mandat' ) $req .= "ORDER BY ".$wpdb->prefix."postmeta.meta_value + 0 DESC";   
    if($trie_par == 'prix' ) $req .= "ORDER BY ".$wpdb->prefix."postmeta.meta_value + 0  ASC";  
    elseif($trie_par == 'date') $req .= "ORDER BY p.post_date DESC";

    $results = $wpdb->get_results($req);

    if( $results && is_array( $results) ){
        foreach($results as $k => $r){
            if(get_field('bien_vente_date_signature_promesse', $r->ID)) unset($results[$k]);
            if(get_field('bien_vente_vendu', $r->ID)) unset($results[$k]);
        }
    }
    $results = array_values($results);

    return $results;
} 


function wami_mo_request_annonces_display($myp){
    ?>
    <div class="annonce_bien">

        <div class="infos_bien">
            <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$myp->ID; ?>" class="dossier">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $myp->ID)); ?></a>
            <?php echo wami_return_bien_proprietaire($myp); ?>
            
            <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$myp->ID; ?>" class="button btn-accent edit_annonce">Editer l'annonce</a>
            <?php if($myp->post_status != 'publish') : ?>
                <a href="#diffusion-annonce" class="button btn-accent open_popin diffuse_annonce" data-openpopin="diffusion-annonce-<?php echo $myp->ID; ?>" data-bid="<?php echo $myp->ID; ?>">Diffuser l'annonce</a>   
            <?php ; else : ?>
                <a href="" class="button btn-accent open_popin declare_vente" data-openpopin="confirme-declarer-vente-<?php echo $myp->ID; ?>" data-bid="<?php echo $myp->ID; ?>">Déclarer la vente</a>  
            <?php endif; ?>   
        </div>

        <div class="details_bien">        
            <?php echo wami_return_bien_annonce_mo($myp); ?>            
            <div class="stat">
                <p class="button btn-secondary open_popin" data-openpopin="statistique-<?php echo $myp->ID; ?>" data-bid="<?php echo $myp->ID; ?>">Statistiques</p>
                <div id="statistique-<?php echo $myp->ID; ?>" class="popin-layer close">
                    <div class="popin statistique">
                        <?php 
                            $consultation = intval(get_post_meta( $myp->ID, 'stat_nombre_de_visite', true ));
                            if($consultation && $consultation > 0) echo 'Cette annonce à été consultée '.$consultation.' fois';
                            else echo "Cette annonce n'a pas encore été consultée";
                        ?>    
                    </div>
                </div>          
            </div>   
        </div>

        <?php echo wami_return_bien_presentation_mo($myp); ?>

    </div>


    <div id="diffusion-annonce-<?php echo $myp->ID; ?>" class="popin-layer close">
        <div class="popin diffusion-annonce">
            <?php wami_add_mo_bien_publish_section( $myp->ID, 'diffusion-annonce-'.$myp->ID ); ?>   
        </div>
    </div>


    <div id="confirme-declarer-vente-<?php echo $myp->ID; ?>" class="popin-layer close">
        <div class="popin">
        <p class="titre">Confirmez-vous la promesse de vente de ce bien ?</p>
        <p class="green">Une fois confirmé le bien vendu sera placé automatiquement dans la catégorie DOSSIER DE VENTE</p>
        <?php $args_bien = array(
                'post__in'  => array($myp->ID),
                'post_type' => 'biens',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
                'fields'            => array(  
                    //'field_597609cc2fc49', // Type de mandat (A LAISSER ET CACHER EN CSS CAR LES AUTRES CHAMPS EN DEPENDENT)                
                    'field_59088607dfe53', // Montant TTC des honoraires
                ),
                'field_el'          => 'div',
                'form'              => true,             
                'html_after_fields' =>  '<input type="hidden" name="declarer_la_vente" value="1">',      
                'submit_value' => 'Confirmer'
            ); 
            acf_form($args_bien); ?>
            <a href='#annuler' class='button btn-primary annuler close_popin' data-closepopin="confirme-declarer-vente-<?php echo $myp->ID; ?>">Annuler</a>
        </div>
    </div>
    <?php
}

// AVANT WP_Query ==
    /*
    $paged    = 1;
    $qte_post = 3;
    $args = array(
        'post_type'     => 'biens',                        
        'post_status'   => array('publish', 'draft', 'pending'),
        'author__in'    => array( $user_id ),
        'meta_query'    => array( 
            'relation'  => 'AND',    
            array(
                'relation' => 'OR',
                array(
                    'key'       => 'bien_vente_date_signature_promesse',
                    'value'     => '',
                    'compare'   => '='
                ),
                array(
                  'key' => 'bien_vente_date_signature_promesse',
                  'compare' => 'NOT EXISTS'
                ), 
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
        'meta_key'      => 'bien_ref',
        'orderby'       => 'meta_value',
        'order'         => 'DESC',
        'paged'         => $paged,
        'posts_per_page'=> $qte_post
    );
    $query_biens = new WP_Query( $args );
    debug($query_biens);*/

    /*$query_biens = new WP_Query( $args );
    debug($query_biens);
    if($query_biens->have_posts()) : ?>
        <?php while($query_biens->have_posts()) :
            $query_biens->the_post(); ?>
                <div class="grid-col col_size-12"> 
                    <?php $plop = "annonces"; ?>
                    <?php get_template_part('page_part/mo-biens-loop-'.$tpl); ?>
                </div>
        <?php endwhile; ?>
        <?php //wami_pagination($query_biens); ?>                        
        <div class="pagination">
            <a class="button btn-secondary more-article" data-paged="<?php echo $paged; ?>" data-qte-post="<?php echo $qte_post; ?>">Voir plus de biens</a>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p>Aucune annonce publiée.</p>
    <?php endif;*/