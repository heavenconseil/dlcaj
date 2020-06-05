<?php 

add_action('wp_ajax_nopriv_wami_header_add_bulle_demande_attente', 'wami_header_add_bulle_demande_attente');
add_action('wp_ajax_wami_header_add_bulle_demande_attente', 'wami_header_add_bulle_demande_attente');
function wami_header_add_bulle_demande_attente(){
	$compteur = 0;
	
	$user = wp_get_current_user();
	$args = array(
        'post_author__in'   => array( $user->ID ),
        'status'            => 'hold',
        'hierarchical'      => true,
     );
    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query( $args );  
    if(is_array($comments)) $compteur = count($comments); 
    
    echo $compteur;
   	die();
}