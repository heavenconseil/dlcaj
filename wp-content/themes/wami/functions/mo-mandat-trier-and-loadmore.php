<?php 

add_action('wp_ajax_nopriv_wami_mandat_trie_par_and_load_more', 'wami_mandat_trie_par_and_load_more');
add_action('wp_ajax_wami_mandat_trie_par_and_load_more', 'wami_mandat_trie_par_and_load_more');
function wami_mandat_trie_par_and_load_more(){  

    if( isset($_REQUEST['data']) ) :

        $user     = wp_get_current_user();
        $user_id  = $_REQUEST['wamiforceuid'] ? $_REQUEST['wamiforceuid'] : $user->ID;
        $paged    = $_REQUEST['paged'];
        $qte_post = $_REQUEST['qte_post'];
        $trie_par = $_REQUEST['trie_par'];
        $tpl      = $_REQUEST['tpl'];       

        if($tpl == 'annonces'):  
            // ATTENTION 
            // ici on ne passe qu'en cas de trie car la pagination est géré en php pr eviter une requete en bdd trop lourde !!!!
            //$paged    = intval($paged);
            //$qte_post = intval($qte_post);
            //$start    = ($qte_post + 1) * ($paged - 1); 
            //$end      = $start + $qte_post;  
            //$offset  = $qte_post * ($paged - 1); //debug($offset);                  
            $query_biens = wami_mo_request_annonces($user_id, '0', $qte_post, $trie_par);            
            if($query_biens && is_array($query_biens)) :
                foreach($query_biens as $k=>$b) :
                    echo $k<3 ? '<div class="grid-col col_size-12 all-annonces ligne-'.$k.'">' : '<div class="grid-col col_size-12 all-annonces ligne-'.$k.'" style="display:none;">';
                        wami_mo_request_annonces_display( get_post($b->ID) );
                    echo '</div>';
                endforeach; ?>
                <div class="pagination">
                    <a class="button btn-secondary show-more-article" data-from="0" data-to="<?php echo $qte_post; ?>"  data-qte-post="<?php echo $qte_post; ?>" data-total="<?php echo count($query_biens); ?>">Voir plus de biens</a>
                </div>
            <?php endif;    

        ; else :          

            $args = array(
                'post_type'         => 'biens',
                'author__in'        => array( $user_id ),            
                'paged'             => $paged,
                'posts_per_page'    => $qte_post,
            );

            if($tpl == 'mandats'){
                $args['post_status'] = array('publish', 'draft', 'pending');
            }
            if($tpl == 'dossiers-de-vente') {
                $args['post_status'] = 'publish'; 
                $args['meta_query']  = array( 
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
                        'key'       => 'bien_vente_vendu',
                        'value'     => 0,
                        'compare'   => '='
                    ),
                );
            }
            if($tpl == 'dossiers-vendus') {
                $args['post_status'] = 'publish'; 
                $args['meta_query']  = array( 
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
                );            
            }
            if($trie_par == 'ID'){
                $args['meta_key']    = 'bien_ref';
                $args['meta_type']   = 'NUMERIC';
                $args['orderby']     = 'meta_value_num';
                $args['order']       = 'DESC';
            }
            else if($trie_par == 'date'){
                $args['orderby']     = $trie_par;
                $args['order']       = 'DESC';
            }
            else if($trie_par == 'prix' ) {
                $args['meta_key']    = 'bien_prix_et_honoraires';
                $args['orderby']     = 'meta_value_num';
                $args['order']       = 'ASC';
            }  
            else if($trie_par == 'lieu' ) {
                $args['meta_key']    = 'bien_adresse_cp';
                $args['orderby']     = 'meta_value';
                $args['order']       = 'ASC';
            } 

            $query_biens = new WP_Query( $args ); 
            /*$vars = $query_biens->query_vars;
            foreach($vars as $k=>$v){
                if($k=="orderby") $query_biens->set('orderby', $args['orderby']);//$v = $args['orderby'];
            }
            //debug($vars['orderby'], $args['orderby']); 
            //debug($query_biens); */
           
            if($query_biens->have_posts()) : ?>
                <?php while($query_biens->have_posts()) :
                    $query_biens->the_post(); ?>
                        <div class="grid-col col_size-12"> 
                            <?php get_template_part('page_part/mo-biens-loop-'.$tpl); ?>
                        </div>
                <?php endwhile; ?>                      
                <div class="pagination">
                    <a class="button btn-secondary more-article" data-paged="<?php echo $paged; ?>" data-qte-post="<?php echo $qte_post; ?>">Voir plus de biens</a>
                </div>  
                <?php wp_reset_postdata(); ?>
            <?php endif;        

        endif;

    endif;
    die();
} 