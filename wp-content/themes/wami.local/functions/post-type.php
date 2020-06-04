<?php
/* Fonction et addaction permettant de créer un nouveau type de post avec si besoin sa taxonomie */
add_action( 'init', 'wami_create_posttype' ); 
function wami_create_posttype() {
	
	register_post_type( 'actualites', array(
		'labels' 		=> array(
			'name' 			=> __( 'Actualités' ),
			'singular_name' => __( 'Actualité' )
		),
		'public' 		=> true,
		'has_archive' 	=> true,
		'rewrite' 		=> array('slug' => 'actualites'),
		'menu_position' => 15,
		'menu_icon' 	=> 'dashicons-megaphone',
		'supports' 		=> array(
			'title',
			'editor',
			//'author',
			'thumbnail',
			'revisions',
			//'comments',
		)         
	));

	register_post_type( 'offres-emploi', array(
		'labels' 		=> array(
			'name' 			=> __( 'Offres d\'emploi' ),
			'singular_name' => __( 'Offre d\'emploi' )
		),
		'public' 		=> true,
		'has_archive' 	=> true,
		'rewrite' 		=> array('slug' => 'offres-emploi'),
		'menu_position' => 35,
		'menu_icon' 	=> 'dashicons-groups',
		'supports' 		=> array(
			'title',
			//'editor',
			//'author',
			//'thumbnail',
			'revisions',
			//'comments',
		)         
	));



	register_post_type( 'biens', array(
		'labels' 		=> array(
			'name' 			=> __( 'Biens en vente' ),
			'singular_name' => __( 'Bien' )
		),
		'public' 		=> true,
		'has_archive' 	=> true,
		'rewrite' 		=> array('slug' => 'biens'),
		'menu_position' => 21,
		'menu_icon' 	=> 'dashicons-admin-multisite',
		'supports' 		=> array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'revisions',
			'comments',
		),
		'taxonomies' => array(
			'lieu',
			'type_bien',
			//'contrat'
		),		
		//'capability_type'    => array('bien', 'biens'),
		'capabilities' 		=> array(
	        'edit_post' 			=> 'edit_bien',
	        'read_post' 			=> 'read_bien',
	        'delete_post' 			=> 'delete_bien',	     
	        'edit_posts' 			=> 'edit_biens',
	        'publish_posts' 		=> 'publish_biens',
	        'delete_posts' 			=> 'delete_biens',
	        'edit_others_posts' 	=> 'edit_other_biens',
	        'delate_others_posts' 	=> 'delate_other_biens',
	        'read_private_posts' 	=> 'read_private_biens',   
	    ),
        'map_meta_cap'        => true,        
	));

	register_post_type( 'bien_contact', array(
		'labels' 		=> array(
			'name' 			=> __( 'Contacts des biens' ),
			'singular_name' => __( 'Contact de bien' )
		),
		'public' 		=> true,
		'has_archive' 	=> true,
		'rewrite' 		=> array('slug' => 'bien_contact'),
		'menu_position' => 22,
		'menu_icon' 	=> 'dashicons-id',
		'supports' 		=> array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'revisions',
		),
		//'capability_type'    => array('bien', 'biens'),
		'capabilities' 		=> array(
	        'edit_post' 			=> 'edit_bien',
	        'read_post' 			=> 'read_bien',
	        'delete_post' 			=> 'delete_bien',	     
	        'edit_posts' 			=> 'edit_biens',
	        'publish_posts' 		=> 'publish_biens',
	        'delete_posts' 			=> 'delete_biens',
	        'edit_others_posts' 	=> 'edit_other_biens',
	        'delate_others_posts' 	=> 'delate_other_biens',
	        'read_private_posts' 	=> 'read_private_biens',   
	    ),
        'map_meta_cap'        => true
	));

	register_post_type( 'temoignage', array(
		'labels' 		=> array(
			'name' 			=> __( 'Temoignages' ),
			'singular_name' => __( 'Temoignage' )
		),
		'public' 		=> true,
		'has_archive' 	=> true,
		//'rewrite' 		=> array('slug' => 'temoignage'),
		'menu_position' => 23,
		'menu_icon' 	=> 'dashicons-format-quote',
		'supports' 		=> array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'revisions',
		),
		/*'taxonomies' => array(
			'lieu',
		),*/
		//'capability_type'    => array('temoignage', 'temoignages'),
		'capabilities' 		=> array(
	        'edit_post' 			=> 'edit_temoignage',
	        'edit_posts' 			=> 'edit_temoignages',
	        'edit_others_posts' 	=> 'edit_other_temoignages',
	        'publish_posts' 		=> 'publish_temoignages',
	        'read_post' 			=> 'read_temoignage',
	        'read_private_posts' 	=> 'read_private_temoignages',
	        'delete_post' 			=> 'delete_temoignage',	        
	        'delete_posts' 			=> 'delete_temoignages',
	        'delate_others_posts' 	=> 'delate_other_temoignages',
	    ),
        'map_meta_cap'        => true
	));

	register_taxonomy('lieu', array('biens', 'post'/*, 'temoignage'*/), array(
		'labels' 			=> array(
			'name'         	=> 'Lieu',
			'singular_name' => 'Lieu',
		),
		'public' 			=> true,
		'show_ui' 			=> true,
		'show_in_nav_menus' => true,
		'show_tagcloud' 	=> false,
		'show_admin_column' => true,
		'hierarchical' 		=> true,
	));
	/*register_taxonomy('type_bien', array('biens'), array(
		'labels' 			=> array(
			'name'         	=> 'Types',
			'singular_name' => 'Type',
		),
		'public' 			=> true,
		'show_ui' 			=> true,
		'show_in_nav_menus' => true,
		'show_tagcloud' 	=> false,
		'show_admin_column' => true,
		'hierarchical' 		=> true,
	));*/
	/*register_taxonomy('contrat', array('biens'), array(
		'labels' 			=> array(
			'name'         	=> 'Contrats',
			'singular_name' => 'Contrat',
		),
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'hierarchical'      => true,
	));*/

} 