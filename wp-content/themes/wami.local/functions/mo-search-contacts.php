<?php

function wami_return_alphabetic_list_contact(){
	$user = wp_get_current_user();	
	$alpha_list_name = array();	
	$args = array(
        'post_type'     => 'bien_contact',
        'post_status'   => 'publish',
        'author__in'    => array( $user->ID ),                
        'posts_per_page'=> -1
    );
    $query_contacts = new WP_Query( $args );
    if($query_contacts->have_posts()) :
    	    
        while($query_contacts->have_posts()) :
            $query_contacts->the_post();
        		$alpha_list_name[] = substr(get_field('contact_nom'), 0, 1);
        endwhile; 
        wp_reset_postdata(); 
    endif; 
    return $alpha_list_name;
}


// POUR LES FILTRES DE CONTACTS
add_filter( 'posts_clauses', 'filter_by_leader_id', 10, 2 );
function filter_by_leader_id( $clauses, $query_object ){    
    if ( $query_object->get('custom_requete_by_initiale') ){
        global $wp_query, $wpdb;
        $initiale = $query_object->get('custom_requete_by_initiale');
        //debug($clauses, 'clauses');
        // debug($query_object, 'query_object'); exit; 
        $join = &$clauses['join'];
        $join .= " INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->posts}.ID = {$wpdb->prefix}postmeta.post_id ) "; 
        $where = &$clauses['where'];
        $where .= " AND ( ( {$wpdb->prefix}postmeta.meta_key = 'contact_nom' AND SUBSTR({$wpdb->prefix}postmeta.meta_value, 1, 1) LIKE '%{$initiale}%' ) ) ";
        $groupby = &$clauses['groupby'];
        $groupby = "{$wpdb->posts}.ID";
        $orderby = &$clauses['orderby'];
        $orderby = "{$wpdb->posts}.post_date DESC";
        //debug($clauses, 'clauses after'); exit;
    }  
    return $clauses;
}

add_action('wp_ajax_nopriv_wami_filtres_les_contacts', 'wami_filtres_les_contacts');
add_action('wp_ajax_wami_filtres_les_contacts', 'wami_filtres_les_contacts');
function wami_filtres_les_contacts(){
    if( isset($_REQUEST['data']) ) {
        $user = wp_get_current_user();
        $mes_filtres = $_REQUEST['data'];  
        
        $args = array(
            'post_type'     => 'bien_contact',
            'post_status'   => 'publish',
            'author__in'    => array( $user->ID ),                
            'posts_per_page'=> -1
        );

        if( !empty($mes_filtres['search']) ){
            $args['s'] = $mes_filtres['search'];
        };
        if( !empty($mes_filtres['initiale']) ){             
            $args['custom_requete_by_initiale'] = $mes_filtres['initiale'];   
        };

        $filtres = '';
        $filtres .= !empty($mes_filtres['search']) ? $mes_filtres['search'] : '';
        $filtres .= (!empty($mes_filtres['search']) && !empty($mes_filtres['initiale'])) ? ' ' : '';
        $filtres .= !empty($mes_filtres['initiale']) ? $mes_filtres['initiale'] : '';

        $query_contacts = new WP_Query( $args ); 
        if($query_contacts->have_posts()) : ?> 
            <?php while($query_contacts->have_posts()) :
                $query_contacts->the_post(); ?>
                    <?php echo get_template_part('page_part/loop', 'contact_list'); ?> 
            <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p>Il n'y a pas de résultat pour votre recherche : "<?php echo $filtres; ?>"</p>
        <?php endif; 
    }   
    die();
}; 