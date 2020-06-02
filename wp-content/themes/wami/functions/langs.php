<?php

/* déclaration du textdomain du theme, ici wami */
add_action('after_setup_theme', 'wami_setup_text_domain');
function wami_setup_text_domain(){
	load_theme_textdomain('wami', get_template_directory().'/languages');
}


/* Obtenir le code courant de la langue avec Polylang */
function wami_get_current_lang_code($default='fr'){
	if( function_exists('pll_current_language') ){
		return pll_current_language('slug');
	}else{
		return $default;
	}
}

/* Obtenir l'équivalent de l'url demandée dans la langue courante avec Poylang */
//ou aussi : 
//$page_lang = split('-', get_bloginfo("language")); $page_lang = $page_lang[0]; 
//get_permalink(pll_get_post('13', $page_lang))
function wami_get_page_link($nameorid){
	if(!function_exists('pll_get_post')){
		if(is_numeric($nameorid)){
			return get_permalink($nameorid);
		}else{
			return site_url($nameorid);
		}
	}

	$post_id = false;
	if(is_numeric($nameorid)){
		$post_id = $nameorid;
	}else{
		$post = get_page_by_path($nameorid, OBJECT, array('post','page','dossier','document'));
		if($post){
			$post_id = $post->ID;
		}
	}
	if($post_id){
		$post_id_lang = pll_get_post($post_id);
		if($post_id_lang){
			return get_permalink($post_id_lang);
		}
		return get_permalink($post_id);
	}else{
		return site_url($nameorid);
	}
}