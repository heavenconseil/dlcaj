<?php 

add_action('admin_init','gere_new_roles', 999);
function gere_new_roles() {
    // On ajoute le role d'ambassadeur et on retire les autres roles inutiles
    if( !get_role('ambassadeur') || !get_role('ambassadeur_responsable_de_region') ){  
        remove_role('subscriber'); 
        remove_role('contributor'); 
        remove_role('author');  
        remove_role('editor');   
        remove_role("temoin");     
        //add_role( "temoin", "Témoin", array( 'read' => true, 'edit_dashboard' => true ) ); 
        // remove_role("ambassadeur");
        add_role( "ambassadeur", "Ambassadeur",  array( 
            'read' => false, 
            'edit_dashboard' => false 
        ));    
        add_role('ambassadeur_responsable_de_region', 'Ambassadeur Responsable Région', array( 
            'read' => false, 
            'edit_dashboard' => false, 
            'level_1' => true 
        ));
    }    

    // on gere les capabilities
    // de l'admin   
    $admin = get_role('administrator');      
    $admin->add_cap('edit_temoignage');
    $admin->add_cap('edit_temoignages');
    $admin->add_cap('publish_temoignages');
    $admin->add_cap('read_temoignage');
    $admin->add_cap('delete_temoignage');   
    $admin->add_cap('edit_other_temoignages');
    $admin->add_cap('read_private_temoignages');
    $admin->add_cap('edit_bien');
    $admin->add_cap('edit_biens');
    $admin->add_cap('publish_biens');
    $admin->add_cap('read_bien');
    $admin->add_cap('delete_bien');           
    $admin->add_cap('delete_biens');
    $admin->add_cap('read_private_biens'); 
    $admin->add_cap('edit_other_biens');
    $admin->add_cap('delate_other_biens');

    // celle de notre "temoin"
   /* $role = get_role( 'temoin');
    $role->add_cap('read');
    $role->add_cap('edit_dashboard');
    $role->add_cap('edit_temoignage');
    $role->add_cap('edit_temoignages');
    $role->add_cap('publish_temoignages');
    $role->add_cap('read_temoignage');
    $role->add_cap('delete_temoignage');    */   
    //debug($role); exit;
    
    // Et celle de notre ambassadeur    
    $amb = get_role('ambassadeur');   
    $amb->add_cap('level_1'); 
    $amb->add_cap('edit_bien');
    $amb->add_cap('edit_biens');
    $amb->add_cap('publish_biens');
    $amb->add_cap('publish_others_biens');
    $amb->add_cap('edit_published_post');
    $amb->add_cap('edit_published_posts');
    $amb->add_cap('read_bien');
    $amb->add_cap('read_biens');
    $amb->add_cap('delete_bien');   
    $amb->add_cap('delete_biens');   
    $amb->add_cap('upload_files');
    //debug(get_role('ambassadeur')); exit;

    // Et celle de notre ambassadeur responsable de région  
    $amb = get_role('ambassadeur_responsable_de_region');  
    $amb->add_cap('level_1'); 
    $amb->add_cap('edit_bien');
    $amb->add_cap('edit_biens');
    $amb->add_cap('publish_biens');
    $amb->add_cap('publish_others_biens');
    $amb->add_cap('edit_published_post');
    $amb->add_cap('edit_published_posts');
    $amb->add_cap('read_bien');
    $amb->add_cap('read_biens');
    $amb->add_cap('delete_bien');   
    $amb->add_cap('delete_biens');   
    $amb->add_cap('upload_files');
    //debug(get_role('ambassadeur_responsable_de_region')); exit;
    
}

add_action('pre_get_posts','users_own_attachments');
function users_own_attachments( $wp_query_obj ) {
    global $current_user, $pagenow;
    $is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');
    if( !$is_attachment_request )
        return;
    if( !is_a( $current_user, 'WP_User') )
        return;
    if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
        return;
    if( !current_user_can('delete_pages') )
        $wp_query_obj->set('author', $current_user->ID );
    return;
}



function wami_get_all_users($qsearch, $role){
    global $wpdb;

    $query = "SELECT *
        FROM {$wpdb->prefix}users as u, {$wpdb->prefix}usermeta as m
        WHERE u.ID = m.user_id
            AND (m.meta_key = 'wp_capabilities' AND m.meta_value LIKE '%\"{$role}\"%' )
            AND (
                u.user_login LIKE '%{$qsearch}%' 
                OR u.user_nicename LIKE '%{$qsearch}%' 
                OR u.user_email LIKE '%{$qsearch}%' 
                OR u.user_url LIKE '%{$qsearch}%'                 
                OR u.display_name LIKE '%{$qsearch}%'               
        )       
        GROUP BY u.ID
    ";

    $users = $wpdb->get_results($query);    
    return $users;
}


// get users with specified roles
function getUsersWithRole( $roles ) {
    global $wpdb;
    if ( ! is_array( $roles ) )
        $roles = array_walk( explode( ",", $roles ), 'trim' );
    $sql = '
        SELECT  ID, display_name
        FROM        ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
        ON          ' . $wpdb->users . '.ID             =       ' . $wpdb->usermeta . '.user_id
        WHERE       ' . $wpdb->usermeta . '.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'
        AND     (
    ';
    $i = 1;
    foreach ( $roles as $role ) {
        $sql .= ' ' . $wpdb->usermeta . '.meta_value    LIKE    \'%"' . $role . '"%\' ';
        if ( $i < count( $roles ) ) $sql .= ' OR ';
        $i++;
    }
    $sql .= ' ) ';
    $sql .= ' ORDER BY display_name ';
    $userIDs = $wpdb->get_col( $sql );
    return $userIDs;
}


function user_id_exists($user){
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));
    if($count == 1)
        return true;
    else
        return false;
}