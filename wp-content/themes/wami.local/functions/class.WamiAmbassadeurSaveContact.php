<?php
class WamiAmbassadeurSaveContact {

	
	public static function contact_save_post($post_id) {	
		$user = wp_get_current_user();						
		// On update le titre du contact
   		$titre = isset($_POST['acf']['field_59760096b949d']) ? ucfirst($_POST['acf']['field_59760096b949d']) : "";
   		$titre .= isset($_POST['acf']['field_59760079b949b']) ? ' '.$_POST['acf']['field_59760079b949b'] : "";
   		$titre .= isset($_POST['acf']['field_59760084b949c']) ? ' '.$_POST['acf']['field_59760084b949c'] : ""; 
		if( $titre != '' ) : 
			$contact = array(
				'ID'         	=> $post_id,
				'post_title' 	=> $titre,
				'author'     	=> $user->ID,
			);		
			wp_update_post($contact);
		endif;				
		// On retourne sur la page
		wp_redirect( home_url('tableau-de-bord/carnet-adresses/'.$post_id) ); exit;
	}

} 