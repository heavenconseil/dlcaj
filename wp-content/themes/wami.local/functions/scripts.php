<?php

add_action('wp_enqueue_scripts', 'wami_theme_enqueue_script');
function wami_theme_enqueue_script(){
	$in_footer = true;
	$theme_dir = get_template_directory_uri();

	/*** Styles ***/
	wp_enqueue_style('theme', get_stylesheet_uri());
	wp_enqueue_style('myfont', $theme_dir.'/lib/css/MyFontsWebfontsKit.css');
	wp_enqueue_style('fontawsome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
	wp_enqueue_style('perfect-scrollbar', $theme_dir.'/lib/js/perfect-scrollbar/css/perfect-scrollbar.css');
	wp_enqueue_style('wami-jquery-lightbox', $theme_dir.'/lib/css/component/wami-jquery-lightbox/wami-jquery-lightbox.css');
	wp_enqueue_style('main', $theme_dir.'/lib/css/main.css');
	wp_enqueue_style('print', $theme_dir.'/lib/css/print.css');

	// Pour le middle office
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style('wami_mo_style',  $theme_dir.'/lib/css/wami_mo_style.css');


	/*** Scripts ***/
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js", false, '', $in_footer);
	wp_enqueue_script('owlcarousel', $theme_dir.'/lib/js/owlcarousel/owl.carousel.min.js', false, '', $in_footer);
	wp_enqueue_script('perfect-scrollbar', $theme_dir.'/lib/js/perfect-scrollbar/js/perfect-scrollbar.jquery.js', array('jquery'), '', $in_footer);
	wp_enqueue_script('jquery-mobile-events', $theme_dir.'/lib/js/jquery-mobile-events/jquery-mobile-events.js', array('jquery'), '', $in_footer);
	wp_enqueue_script('wami-jquery-lightbox', $theme_dir.'/lib/js/wami-jquery-lightbox/wami-jquery-lightbox.js', array('jquery', 'jquery-mobile-events'), '', $in_footer);

	if(defined('GOOGLE_API_KEY')) wp_enqueue_script('googlemap', 'https://maps.googleapis.com/maps/api/js?key='.GOOGLE_API_KEY, false, '', $in_footer); // Local
	//wp_enqueue_script('googlemap', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB1WS3VkEJmZJlQS6Uz-msTEShNWfT3Lq4', false, '', $in_footer); // Prod
	//wp_enqueue_script('markerclusterer', $theme_dir.'/lib/js/map/markerclusterer_compiled.js', false, '', $in_footer);
	wp_enqueue_script('markerclusterer', $theme_dir.'/lib/js/map/markerclusterer.js', false, '', $in_footer);

	wp_enqueue_script('main_inte', $theme_dir.'/lib/js/main.js', array('jquery'), '', $in_footer);
	wp_enqueue_script('wami_main', $theme_dir.'/lib/js/main_dev.js', array('jquery'), '', $in_footer);
	wp_enqueue_script('wami_inte_middleoffice', $theme_dir.'/lib/js/main_inte_middleoffice.js', array('jquery', 'wami_main'), '', $in_footer);
	wp_enqueue_script('wami_main_middleoffice', $theme_dir.'/lib/js/main_dev_middleoffice.js', array('jquery', 'wami_main'), '', $in_footer);
	wp_enqueue_script('maps', $theme_dir.'/lib/js/map/maps.js', array('jquery', 'wami_main'), '', $in_footer);

	if(is_page_template('tpl-estimation.php'))
		wp_enqueue_script('estimation', $theme_dir.'/lib/js/form-estimation.js', array('jquery', 'wami_main'), '', $in_footer);

	wp_localize_script('wami_main', 'wami_js', array(
		'themeurl' => $theme_dir,
		'ajaxurl'  => admin_url('admin-ajax.php'),
		'siteurl'  => site_url()
	));
}

/* Ajoute les favicons */
add_action('wp_head', 'wami_favicon');
function wami_favicon() { ?>
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/lib/img/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/lib/img/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/lib/img/favicon-96x96.png" size="96x96" />
<?php }


/*add_action('admin_enqueue_scripts', 'wami_admin_enqueue_script');
function wami_admin_enqueue_script(){
	$in_footer = true;
	$theme_dir = get_template_directory_uri();

	wp_enqueue_style('myfont', $theme_dir.'/lib/css/MyFontsWebfontsKit.css');
	//wp_enqueue_style('wami_bo_style',  $theme_dir.'/lib/css/wami_bo_style.css');
}*/


add_filter('tiny_mce_before_init', 'load_custom_fonts');  // Note #4
function load_custom_fonts($init) {
	$theme_dir 		= get_template_directory_uri();
    $stylesheet_url = $theme_dir.'/lib/css/wami_bo_style.css';  // Note #1
    if(empty($init['content_css'])) {  // Note #2
        $init['content_css'] = $stylesheet_url;
    } else {
        $init['content_css'] = $init['content_css'].','.$stylesheet_url;
    }
    return $init;  // Note #3
}
