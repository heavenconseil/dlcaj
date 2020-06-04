<?php
/* Créer une page d'options ACF */
if( function_exists('acf_add_options_page') ) {	
	acf_add_options_page(array(
		'page_title' 	=> 'Options du site',
		'menu_title'	=> 'Options du site',
		'menu_slug' 	=> 'theme-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'position'      => 25
	));
	// Créer une sous-page d'options ACF
	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Publicité',
	// 	'menu_title'	=> 'Publicité',
	// 	'parent_slug'	=> 'theme-options',
	// ));
	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Sidebar',
	// 	'menu_title'	=> 'Sidebar',
	// 	'parent_slug'	=> 'theme-options',
	// ));
}


function wami_acf_get_archives_month(){
	global $wpdb;
	$results = $wpdb->get_results( 'SELECT DISTINCT meta_value FROM wp_postmeta WHERE meta_key LIKE "date_rdv"', OBJECT );      
    //debug($results);
	if($results && is_array($results)) :
		$dates_dispo = array();
	    foreach($results as $r) : 
	    	$dateTime = strtotime($r->meta_value);
	    	if( !$dates_dispo[date_i18n("m", $dateTime).date_i18n("Y", $dateTime)] )
				$dates_dispo[date_i18n("m", $dateTime).date_i18n("Y", $dateTime)] = (object) array(
					'mois'	=> date_i18n("F", $dateTime),
				  	'month' => date_i18n("m", $dateTime),
				  	'year'  => date_i18n("Y", $dateTime)	
				);		
	    endforeach;
	    krsort($dates_dispo);
	    return (object) $dates_dispo;
	 endif;    
}


function wami_test_acf_required_fields($post_id, $sauf_lien_mandat = false){
	$error = array();	
	// On va chercher tous les champs du groupfield	
	$json_data = file_get_contents(get_template_directory_uri()."/functions/json/group_bien.json"); 
	//$json_data = file_get_contents(get_template_directory_uri()."/acf-json/group_59e9b0ba1424d.json"); 
	//group_5980a329718c3//"/acf-json/group_5980a3603bd4a.json"); //group_59804b5e717a2 //group_59d77f98e136a
	$all_fields = json_decode($json_data, true); 
	// On va regarder tous les champs qui sont renseignés
	$post_fields = get_field_objects($post_id); 	
	if( !is_array($post_fields) ) {
		$error[] = array('key'=>'000', 'name'=>'Tous les champs du formulaire (aucun n\'a été renseigné)', 'label'=>'Tous les champs du formulaire (aucun n\'a été renseigné)');
		return($error); 
	}
	// si le champs est obligatoire on va checker qu'il exite pour ce post_id
	foreach($all_fields['fields'] as $field){		
		if( $field['required']!=0 && !array_key_exists($field['name'], $post_fields) ) {			
			if($sauf_lien_mandat == 1 && $field['name'] == "lien_mandat") continue;
			if( is_array( $field['conditional_logic'] ) ) {
				$type_accept = array();
				$type_mandat = get_field('field_597609cc2fc49', $post_id);
				foreach($field['conditional_logic'] as $to_check){ 
					foreach($to_check as $check){
						if( $check['field']=='field_597609cc2fc49' && $check['operator'] == "==" ){
							$type_accept[] = $check['value'];
						}
					}
				}
				if( in_array($type_mandat['value'], $type_accept) ) $error[] = array('key'=>$field['key'], 'name'=>$field['name'], 'label'=>$field['label']);
			} 			
			else $error[] = array('key'=>$field['key'], 'name'=>$field['name'], 'label'=>$field['label']);
		}
	}		
	// et on test que les champs obligatoires renseignés pour ce post ne sont pas vides
	foreach($post_fields as $field) {
		if($sauf_lien_mandat == 1 && $field['name'] == "lien_mandat") continue;
		if( $field['required']!=0 && $field['type'] == 'repeater' ){
			$i = 0;
			foreach($field['sub_fields'] as $subfield) { 
				$val = (string) $subfield['name'];
				if( $subfield['required']!=0 && $field['value'][$i][$val]=="" ) {
					// On regarde si il est soumis à une logique conditionnel et si oui on test sa logique conditionnelle
					if($subfield['conditional_logic']!=0){ 
						foreach($subfield['conditional_logic'] as $to_check){
							foreach($to_check as $check){
								if($check['operator'] == "==" && $check['value']=='1' && get_field($check['field'], $post_id)){
									$error[] = array('key'=>$subfield['key'], 'name'=>$subfield['name'], 'label'=>$field['label'].' '.$subfield['label']);
								}
							}
						}
					} 
					// et sinon si il n'est pas soumis à des condition alors on a bien l'erreur
					else {
						$error[] = array('key'=>$subfield['key'], 'name'=>$subfield['name'], 'label'=>$field['label'].' : '.$subfield['label']);
					}		
				}				
			}
			$i++;
		}
		elseif( $field['required']!=0 && $field['value']=="" ) {
			$error[] = array('key'=>$field['key'], 'name'=>$field['name'], 'label'=>$field['label']);		
		}
	}	
	return($error); 
}



function include_add_action_for_acf_form(){
	// On ajoute nos actions au moment de l'enregistrement des champs acf 
	if(is_page('ajouter-annonce') || is_page('annonces')){
	    add_action( 'acf/save_post', array('WamiAmbassadeurSaveBien', 'bien_save_post'));
	}
	else if(is_page('carnet-adresses')){
	    add_action( 'acf/save_post', array('WamiAmbassadeurSaveContact', 'contact_save_post'));
	}
	else if(is_page('mon-profil')){
	    add_action( 'acf/save_post', array('WamiAmbassadeurSaveProfil', 'contact_save_profil'));
	}
	acf_form_head();
}


function return_french_date_to_timestamp($format, $mydate){
	$dt = DateTime::createFromFormat($format, $mydate);
    return $dt->getTimestamp();
}