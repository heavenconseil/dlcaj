<?php

/* Déclaration des formats d'image du thème */
add_image_size('paysage_xsmall', 100, 70, true);
add_image_size('paysage_small', 150, 105, true);
add_image_size('paysage_medium', 350, 230, true);
add_image_size('paysage_reg', 550, 330, true);
add_image_size('paysage_big', 750, 330, true);
add_image_size('paysage_xbig', 820, 440, true); //add_image_size('paysage_xbig', 580, 440, true)
add_image_size('paysage_slider', 900, 600, true);
add_image_size('paysage_full', 1440, 1024, true);
add_image_size('carre_small', 150, 150, true);
add_image_size('carre_medium', 300, 300, true);
add_image_size('portrait_small', 150, 190, true);


/* formats à l'afficher lors de l'intégration dans le wysiwyg */
add_filter('image_size_names_choose', 'wami_image_size_choose');
function wami_image_size_choose($sizes){
	return array_merge($sizes, array(
		// identifiant du format => Nom du format
		//'wami_small' => __("Petit"),
	));
}


/* On retire les formats d'image par défaut pour éviter le stockage des fichiers */
add_filter('intermediate_image_sizes_advanced', 'wami_remove_image_sizes');
function wami_remove_image_sizes($sizes){
	unset($sizes['thumbnail'], $sizes['medium'], $sizes['large']);
	return $sizes;
}


/* Permet d'ajouter en BO une colonne avec la miniature de l'image à la Une du post */
add_action('manage_posts_custom_column', 'custom_columns_data', 10, 2); 
function custom_columns_data($column, $post_id){
	switch($column){
		case 'image':
			$image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'wami_small');
			if($image_data[0]){
				echo '<img src="'.$image_data[0].'" style="display:block;max-width:110px;" />';
			}
			break;
	}
}
add_filter('manage_posts_columns' , 'custom_columns');
function custom_columns($columns){
	return array_merge(array(
		'cb'    => '<input type="checkbox" />',
		'image' => 'Image',
	), $columns);
}

add_filter('sanitize_file_name', 'wpc_sanitize_french_chars', 10);
function wpc_sanitize_french_chars($filename) {	
	// Force the file name in UTF-8 (encoding Windows / OS X / Linux) 
	$filename = mb_convert_encoding($filename, "UTF-8");

	$char_not_clean = array('/À/','/Á/','/Â/','/Ã/','/Ä/','/Å/','/Ç/','/È/','/É/','/Ê/','/Ë/','/Ì/','/Í/','/Î/','/Ï/','/Ò/','/Ó/','/Ô/','/Õ/','/Ö/','/Ù/','/Ú/','/Û/','/Ü/','/Ý/','/à/','/á/','/â/','/ã/','/ä/','/å/','/ç/','/è/','/é/','/ê/','/ë/','/ì/','/í/','/î/','/ï/','/ð/','/ò/','/ó/','/ô/','/õ/','/ö/','/ù/','/ú/','/û/','/ü/','/ý/','/ÿ/', '/©/', '/@/');
	$clean = array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y','copy', 'at');

	$friendly_filename = preg_replace($char_not_clean, $clean, $filename);

	// After replacement, we destroy the last residues 
	$friendly_filename = utf8_decode($friendly_filename);
	$friendly_filename = preg_replace('/\?/', '', $friendly_filename);

	// Lowercase 
	$friendly_filename = strtolower($friendly_filename);

	return $friendly_filename;
}
