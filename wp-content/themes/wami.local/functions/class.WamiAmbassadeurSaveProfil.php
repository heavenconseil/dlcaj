<?php
class WamiAmbassadeurSaveProfil {
	
	public static function contact_save_profil($user_id) {	
		$user = wp_get_current_user();
		// On update le user	
		$userdata = array(
			'ID'		 => $user->ID,
			'first_name' => $_POST['first_name'],
			'last_name'  => $_POST['last_name'],
		);	
		wp_update_user($userdata);	
		// On retourne sur la page
		wp_redirect( home_url('tableau-de-bord/mon-profil/') ); exit;
	}
	
} 