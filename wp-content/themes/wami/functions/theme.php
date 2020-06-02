<?php

/* capacité du thème */
add_action('after_setup_theme', 'wami_setup_theme_capabilities');
function wami_setup_theme_capabilities(){
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
	add_theme_support('post-formats', array('status', 'quote', 'gallery', 'image', 'video', 'audio', 'link', 'aside', 'chat'));
	add_post_type_support('post', 'post-formats');
}


/* inclusion du style pour l'éditeur wysiwyg */
add_action('after_setup_theme', 'wami_setup_editor_style');
function wami_setup_editor_style(){
	add_editor_style('css/main.css');
}


/* cacher la bar d'admin sur le front-office */
add_filter( 'show_admin_bar' , 'wami_front_admin_bar');
function wami_front_admin_bar(){
	if(!current_user_can('edit_posts') && !is_admin()){
		return false;
	}
	return true;
}


/* On affiche un message en barre d'admin si le site n'est pas référencé */
$current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$public = get_option('blog_public');

if( $public==0 && !strstr($current_url, '192.168.0.101') && !strstr($current_url, 'clients.wami-concept.com') ){
	add_action('admin_bar_menu', 'wami_robots_noindex_admin_menu', 1001 );
	function wami_robots_noindex_admin_menu($wp_admin_bar){
		if ( !current_user_can('edit_posts') ) return null;
		$wp_admin_bar->add_node(
			array(
				'id' => 'wami_robots_noindex',
				'title' => 'Indexation désactivée',
				'href' => admin_url('options-reading.php')
			)
		);
	}

	add_action('wp_head', 'wami_robots_noindex_admin_menu_styles');
	add_action('admin_head', 'wami_robots_noindex_admin_menu_styles');
	function wami_robots_noindex_admin_menu_styles($wp_admin_bar){
		if ( !current_user_can('edit_posts') ) return null;
		?><style type="text/css">
			#wp-admin-bar-wami_robots_noindex a,
			#wp-admin-bar-wami_robots_noindex a:hover{
				padding-left:10px;
				padding-right:10px;
				background:#e74c3c !important;
				color:white !important;
			}
		</style><?php
	}
}