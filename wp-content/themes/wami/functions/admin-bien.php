<?php

add_filter("manage_edit-biens_columns", "biens_columns");
function biens_columns($columns){
    $columns = array(
        'cb'        => '<input type="checkbox" />',
        'bien_ref'  => 'Référence du bien',
        'title'     => 'Titre',
        'image'     => 'Image',
        'bien_prix_et_honoraires' => 'Prix du bien',
        'author'    =>  'Auteur',
        'date'      =>  'Date',
    );
    return $columns;
}

add_action("manage_posts_custom_column", "my_custom_columns");
function my_custom_columns($column){
    global $post;
    if($column == 'bien_ref') echo '<strong>' . sprintf("%06d", get_field('bien_ref')) . '</strong>';
    else if($column == 'bien_prix_et_honoraires' && get_field('bien_prix_et_honoraires')) echo number_format(get_field('bien_prix_et_honoraires'), 0, '', ' ').' &#8364';
}

add_filter("manage_edit-biens_sortable_columns", "my_column_register_sortable_ref" );
function my_column_register_sortable_ref( $columns ){
    $columns['bien_ref'] = 'bien_ref';
    return $columns;
}

add_filter("manage_edit-biens_sortable_columns", "my_column_register_sortable_prix");
function my_column_register_sortable_prix( $columns ){
    $columns['bien_prix_et_honoraires'] = 'bien_prix_et_honoraires';
    return $columns;
}

add_filter( 'request', 'my_column_orderby_ref' );
function my_column_orderby_ref( $vars ) {
    if ( isset( $vars['orderby'] ) && 'linked_issue_post' == $vars['orderby'] ) {       
        $vars = array_merge( $vars, array(
            'meta_key' => 'bien_ref',
            'orderby' => 'meta_value_num'
        ) );
    }
    return $vars;
}

add_filter('pre_get_posts', 'my_custom_column_order');
function my_custom_column_order( $wp_query ) {
  if ( is_admin() && array_key_exists('post_type', $wp_query->query) ) {
    // Get the post type from the query
    $post_type = $wp_query->query['post_type'];    
    if ( $post_type == 'biens') {
      $wp_query->set('orderby', 'bien_ref');
      $wp_query->set('order', 'DESC');
    }
  }
}


add_action('acf/save_post', 'my_acf_save_post', 999);
function my_acf_save_post( $post_id ) {
    $bien = get_post($post_id);
    if ($bien->post_type != 'biens'){
        return;
    } else {
        $ambID = $bien->post_author;
        $type_amb = get_field('type_ambassadeur', 'user_'.$ambID);
        $statut_mandat = get_field('mandat_etat', $post_id);

        // Si ce n'est pas un ambassadeur externe alors on update la réf du bien
        if( !$type_amb || (is_array($type_amb) && $type_amb['value']!='externe') ):
            $ref = get_field('bien_ref', $post_id);
            if( !$ref ){
                $last = get_last_reference_de_bien(); 
                if($last == 0) $last = 209;        
                $ref = intval($last) + 1;
                $ref = sprintf("%06d", $ref);
                update_field('bien_ref', $ref, $post_id);    
                // et on update sa date de référencement
                update_field('bien_date_de_referencement', date('m/d/Y'), $post_id);             
            } 
            if( class_exists('wami_module_registre_mandat') ){
                $wami_module_registre_mandat = new wami_module_registre_mandat();
                $wami_module_registre_mandat->update_table_registre_mandat($post_id, false, false, $ref);
            }
        endif;

        // Si on a changé le statut du mandat pour vendu par un tier on passe le mandat dans la corbeille  
        if( is_array($statut_mandat) && $statut_mandat['value'] == 'vendu_par_tiers' ):           
            wp_trash_post($post_id);
            wp_redirect( admin_url().'edit.php?post_type=biens' ); die();
        endif;

    }    
}


function get_last_reference_de_bien(){
    global $wpdb;  
    if( class_exists('wami_module_registre_mandat') ){
        $wami_module_registre_mandat = new wami_module_registre_mandat();
        $plop = "SELECT MAX( CAST(`bien_ref` AS DECIMAL) ) as max FROM $wami_module_registre_mandat->table";
    } else{
        $plop = "SELECT MAX( CAST(`meta_value` AS DECIMAL) ) as max FROM {$wpdb->prefix}postmeta WHERE meta_key='bien_ref'";
    }  
    $last_ref = $wpdb->get_var($plop);
    return $last_ref;
}  


add_action('wp_ajax_nopriv_wami_delete_mandat', 'wami_delete_mandat');
add_action('wp_ajax_wami_delete_mandat', 'wami_delete_mandat');
function wami_delete_mandat(){ 
    if( isset($_REQUEST['mandat_id']) ) { 
        $post_id = $_REQUEST['mandat_id'];
        $plop = wp_trash_post($post_id);
        wp_send_json($plop);       
    }
    die();
}