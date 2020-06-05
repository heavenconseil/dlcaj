<?php

/* Modifier le footer en zone admin */
add_filter('admin_footer_text', 'remove_footer_admin');
function remove_footer_admin(){
	echo 'WAMI Concept - '.date_i18n('Y');
}


/* On retire les accents sur les fichiers uploadés */
add_filter('sanitize_file_name', 'remove_accents');


/* On cache certains liens de la barre d'admin */
add_action( 'wp_before_admin_bar_render', 'wami_admin_bar' );
function wami_admin_bar(){
    global $wp_admin_bar;
	/* front */
        $wp_admin_bar->remove_menu('wp-logo');
        $wp_admin_bar->remove_menu('appearance');
        //$wp_admin_bar->remove_menu('customize');
        $wp_admin_bar->remove_menu('about');
        $wp_admin_bar->remove_menu('wporg');
        $wp_admin_bar->remove_menu('documentation');
        $wp_admin_bar->remove_menu('support-forums');
        $wp_admin_bar->remove_menu('feedback');
        $wp_admin_bar->remove_menu('view-site');
        //$wp_admin_bar->remove_menu('');
        //$wp_admin_bar->remove_menu('new-content');
    /* back */
        $wp_admin_bar->remove_menu('themes');
        $wp_admin_bar->remove_menu('dashboard');
        $wp_admin_bar->remove_menu('customize-background');
        $wp_admin_bar->remove_menu('menus');
        $wp_admin_bar->remove_menu('widgets');
        $wp_admin_bar->remove_menu('header');
        $wp_admin_bar->remove_menu('background');
}

/* On cache certains liens de la sidebar du BO */
add_action( 'admin_menu', 'remove_menus' );
function remove_menus(){
    //remove_menu_page( 'index.php' );                  //Dashboard
    //remove_menu_page( 'edit.php' );                   //Posts
    //remove_menu_page( 'upload.php' );                 //Media
    //remove_menu_page( 'edit.php?post_type=page' );    //Pages
    //remove_menu_page( 'edit-comments.php' );          //Comments
    //remove_menu_page( 'themes.php' );                 //Appearance
    //remove_menu_page( 'plugins.php' );                //Plugins
    //remove_menu_page( 'users.php' );                  //Users
    //remove_menu_page( 'tools.php' );                  //Tools
    //remove_menu_page( 'options-general.php' );        //Settings
}


/* Annuler les remplacements de texte par les emojicons de wordpress */
add_action('init', 'wami_disable_wp_emojicons');
function wami_disable_wp_emojicons(){
	// actions pour styles & scripts
	//remove_action('admin_print_styles', 'print_emoji_styles');
	//remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	//remove_action('wp_print_styles', 'print_emoji_styles');
	//remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	//remove_filter('the_content_feed', 'wp_staticize_emoji');
	//remove_filter('comment_text_rss', 'wp_staticize_emoji');

	// filter pour l'éditeur TinyMCE
	add_filter('tiny_mce_plugins', 'wami_disable_emojicons_tinymce');
}

function wami_disable_emojicons_tinymce($plugins){
	if(is_array($plugins)){
		return array_diff($plugins, array('wpemoji'));
	}else{
		return array();
	}
}


/* Pour gérer l'ordre du menu de l'admin du BO */
// add_filter('custom_menu_order', 'custom_menu_order');
// add_filter('menu_order', 'custom_menu_order');
function custom_menu_order($menu_ord) {
   if (!$menu_ord) return true;
   return array(
    'index.php', // this represents the dashboard link
    'upload.php', // the media manager        
    'edit.php?post_type=page', //the posts tab
    'edit.php', //the posts tab
    );
}


/* Pour transformer le titre "article" en "Rendez-vous" */
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );
function change_post_menu_label() {
    global $menu;
    global $submenu;
    //$menu[10][0] = 'Image et Docs';
    $menu[25][0] = "Rappels";
    $submenu['edit-comments.php'][0][0] = "Tous les rappels";
    $menu[5][0] = 'Rendez-vous';
    $submenu['edit.php'][5][0] = 'Rendez-vous';
    $submenu['edit.php'][10][0] = 'Ajouter un rendez-vous';
    $submenu['edit.php'][15][0] = 'Catégories'; // Change name for categories
    $submenu['edit.php'][16][0] = 'Etiquettes'; // Change name for tags
    echo '';
}

function change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Rendez-vous';
    $labels->singular_name = 'Rendez-vous';
    $labels->add_new = 'Ajouté un rendez-vous';
    $labels->add_new_item = 'Ajouté une rendez-vous';
    $labels->edit_item = 'Voir les rendez-vous';
    $labels->new_item = 'Rendez-vous';
    $labels->view_item = 'Voir le rendez-vous';
    $labels->search_items = 'Rechercher une actulité';
    $labels->not_found = 'Pas de rendez-vous trouvée';
    $labels->not_found_in_trash = 'Pas de rendez-vous trouvée dans la poubelle';
}


/* Pour gérer l'ordre du menu de l'admin du BO */
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'my_menu_order' );
function my_menu_order($menu_ord) {
    if (!$menu_ord) return true;
    return array(
        'index.php', // the dashboard
        'upload.php', // les médias
        'edit.php?post_type=page', // les pages
        'edit.php', // les "post" (devenu ici rendez-vous)        
        'edit.php?post_type=biens', // les biens
        'admin.php?page=theme-options', // page d'option ACF
        'edit-comments.php', // les "post" (devenu ici rendez-vous)     '
    );
}