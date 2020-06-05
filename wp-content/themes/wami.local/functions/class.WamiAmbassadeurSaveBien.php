<?php
class WamiAmbassadeurSaveBien {

	public static function bien_save_post($post_id) {
		$user = wp_get_current_user();	
				
		// On update le bien
		$bien = array(
			'ID'         	=> $post_id,
			'author'     	=> $user->ID, 
        );	
        
		if( isset($_POST['acf']['field_597872816a779']) ) {
			$bien['post_title'] = $_POST['acf']['field_597872816a779'];
			$bien['post_name']  = $post_id.'-'.normalize_string($_POST['acf']['field_597872816a779']);
		} else if( get_the_title($post_id) == "" ){
			$type = get_field("bien_mandat", $post_id);			
			$bien['post_title'] = $type['label'].' - ';
			$bien['post_title'] .= get_field('bien_ref', $post_id) ? get_field('bien_ref', $post_id) : current_time('Y-m-d');
			update_field('bien_titre_du_bien', $bien['post_title'], $post_id);
		}

		if( isset($_POST['acf']['field_597872b76a77a']) ) {
			$text = $_POST['acf']['field_597872b76a77a'];
			$text = str_replace('’', "'", $text);	
			$bien['post_content'] = $text;
		}
		wp_update_post($bien);

		
		// Si ce n'est pas un ambassadeur externe 
        $type_amb = get_field('type_ambassadeur', 'user_'.$user->ID);
        if( !$type_amb || (is_array($type_amb) && $type_amb['value']!='externe') ){
			// et on update la référence du bien
			$ref = get_field('bien_ref', $post_id);
	        if( !$ref ){
	            $last = get_last_reference_de_bien();     
	            $ref = intval($last) + 1;
	            update_field('bien_ref', $ref, $post_id);
	            // et on update sa date de référencement
	            update_field('bien_date_de_referencement', date('m/d/Y'), $post_id);    
	        }
	        // et on update notre table de registre des mandats
	        if( class_exists('wami_module_registre_mandat') ){
	        	$wami_module_registre_mandat = new wami_module_registre_mandat();
	        	$wami_module_registre_mandat->update_table_registre_mandat($post_id, false, false, $ref);
	        }
	    }

		// On update l'image à la une
		if( isset($_POST['acf']['field_590090484c4d7']) && is_array($_POST['acf']['field_590090484c4d7']) ) :
			$img_id = '';
			foreach( $_POST['acf']['field_590090484c4d7'] as $img ){
				if($img_id == '') $img_id = $img['field_590090664c4d8'];
			}
			set_post_thumbnail( $post_id, $img_id );
		endif;

		// On update les catégories (le lieu)
		if(isset($_POST['tax_input']['lieu']))
			wp_set_post_terms( $post_id, $_POST['tax_input']['lieu'], 'lieu' );
		
		// On update l'adresse de google map avec le champs d'adresse renseigné
		if(isset($_POST['acf']['field_5975f83884133'])) : 
			$address = $_POST['acf']['field_5975f83884133'].' '.$_POST['acf']['field_597601ad2fc38'].' '.$_POST['acf']['field_597601ba2fc39'].' '.$_POST['acf']['field_5975f9198413d'];
			$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key=AIzaSyBAJvniglIbexoXNEuq3hOGfukVBWaEOkM&sensor=false');
			$geocode = json_decode($geocode);
			//debug(json_decode($geocode)); exit;
			if( is_array($geocode->results) && !empty($geocode->results) ){
				$value = array("address" => $address, "lat" => $geocode->results[0]->geometry->location->lat, "lng" => $geocode->results[0]->geometry->location->lng);	
				update_field("field_5901b59d67593", $value, $post_id);				
			}
		endif; 

		// On update le montant des honoraires 
        if( !isset($_POST['acf']['field_59088607dfe53']) && isset($_POST['acf']['field_597603552fc45']) && isset($_POST['acf']['field_5900742378a55']) ) :
			$prix_honoraire_inc = intval($_POST['acf']['field_597603552fc45']); // Prix honoraires inclus
			$prix_honoraire_exc = intval($_POST['acf']['field_5900742378a55']); // Prix sans honoraires
			$value = $prix_honoraire_inc - $prix_honoraire_exc;
            update_field('field_59088607dfe53', $value, $post_id);   			
		endif;

		// Si le mandat est signé
		if( isset($_POST['acf']['field_5c7fcc8148e41']) ) :	
			// On passe en true le fait que le mandat a été enregistré signé
			update_post_meta( $post_id, 'mandat_print', 1 );
			// on update le champs de date d'enregistrement du mandat
			if(!get_field('field_59888ce99d6dc', $post_id)) update_field("field_59888ce99d6dc", date('m/d/Y'), $post_id);			
			// on update le prix d'origine du mandat si il n'a pas déjà été renseigné (donc si c'est bien la 1er fois qu'on l'a enregistré/imprimé)
			if(!get_field('field_59b0fe92f3c5e', $post_id)){ 
				$prix_origine = get_field('field_597603552fc45', $post_id) ? get_field('field_597603552fc45', $post_id) : ""; // Prix honoraires inclus
				update_field("field_59b0fe92f3c5e", $prix_origine, $post_id);				
			}
			// On passe son statut en publié 
			wp_update_post(array('ID' => $post_id, 'post_status' => 'publish'));
			
			// et si on publie le mandat sur DLCAJ ou un autre support
			if(isset($_POST['publication_input']['support']) && is_array($_POST['publication_input']['support'])) {		
				// On update l'etat du mandat
				update_field('field_597721a9edd7a', 'en_vente', $post_id);
				// On le publie sur le site			
				WamiAmbassadeurSaveBien::publier_le_bien_sur_le_site($post_id);	
				// on le publie sur un autre support
				WamiAmbassadeurSaveBien::publier_bien_sur_les_autres_reseaux($post_id);
			} else {
				// On update l'etat du mandat
				update_field('field_597721a9edd7a', 'en_attente', $post_id);
				// on selectionné aucun support de diffusion alors on les décoche tous 
				update_field('field_5901b5b467594', 0, $post_id); // Bien disponible à la vente/location
				update_field('field_5980a3e646181', 0, $post_id); // Bien publié sur Le Bon Coin
				update_field('field_5980a40846182', 0, $post_id); // Bien publié sur Se Loger
				update_field('field_5980a41e46183', 0, $post_id); // Bien publié sur International
			}	
		endif;

		/*// Si on à une promesse de vente on update le champs Bien disponible à la vente/location (dans Détail de la publication du bien) en false 
		if(isset($_POST['acf']['field_597eebec1cebb']))
			update_field('field_5901b5b467594', 0, $post_id);*/
		

		// Si on déclare la vente 
		if(isset($_POST['declarer_la_vente'])){
			update_field('field_597eebb61ceba', date('m/d/Y'), $post_id);
			// Et on update l'etat du mandat
			update_field('field_597721a9edd7a', 'signe', $post_id);
		} 

		// Si on vend le bien on update à la fois le champs Bien disponible à la vente/location (dans Détail de la publication du bien) et le champs vrai/faux de vendu (bien_vente_vendu)
		if(isset($_POST['acf']['field_597eebec1cebb']) && $_POST['acf']['field_597eebec1cebb'] != ''){
			update_field('field_5983301bd237a', 1, $post_id);			
			update_field('field_5901b5b467594', 0, $post_id);
			//  et on update l'etat du mandat
			update_field('field_597721a9edd7a', 'vendu', $post_id);
		} 
		
		// On retourne sur la page
		if(is_page('ajouter-annonce')){
			$get_value = isset($_GET[
				'subbloc']) ? '?bloc='.$_GET['bloc'].'&subbloc='.$_GET['subbloc'] : (isset($_GET['bloc']) ? '?bloc='.$_GET['bloc'] : '');
			wp_redirect( home_url('tableau-de-bord/ajouter-annonce/'.$post_id.'/'.$get_value) ); exit;
		}
		else if(is_page('annonces')){
			wp_redirect( home_url('tableau-de-bord/annonces/') ); exit;
		}		
	}
	

	public static function publier_le_bien_sur_le_site($post_id){
		if(in_array('dlcaj', $_POST['publication_input']['support'])){
			// on check que tous les champs "obligatoires" sont bien renseignés
			$error = false;
			if( get_the_title($post_id) == '' ) $error .= urlencode('Titre du bien'.'%0A');	
			if( wami_test_acf_required_fields($post_id) != 0) {
				foreach(wami_test_acf_required_fields($post_id) as $e){
					if($e['key'] != 'field_5c7fcc8148e41')	$error .= urlencode($e['label'].'%0A');	
				}			
			}	
			// on le publie si tout est ok
			if( !$error ){
				wp_update_post(array('ID' => $post_id, 'post_status' => 'publish'));
				update_field('field_5901b5b467594', 1, $post_id); // Bien disponible à la vente/location
			} else {
				wp_update_post(array('ID' => $post_id, 'post_status' => 'pending'));
				wp_redirect( home_url('tableau-de-bord/ajouter-annonce/'.$post_id.'?erreur='.$error) ); exit;
			}
		} else {
			update_field('field_5901b5b467594', 0, $post_id); // Bien disponible à la vente/location
		}
	}


	public static function publier_bien_sur_les_autres_reseaux($post_id){
		if(in_array('le_bon_coin', $_POST['publication_input']['support'])){
			update_field('field_5980a3e646181', 1, $post_id); // Bien publié sur Le Bon Coin
		} else {
			update_field('field_5980a3e646181', 0, $post_id); // Bien publié sur Le Bon Coin
		}	
		if(in_array('se_loger', $_POST['publication_input']['support'])){
			update_field('field_5980a40846182', 1, $post_id); // Bien publié sur Se Loger
		} else {
			update_field('field_5980a40846182', 0, $post_id); // Bien publié sur Se Loger
		}
		if(in_array('international', $_POST['publication_input']['support'])){
			update_field('field_5980a41e46183', 1, $post_id); // Bien publié sur International
		} else {
			update_field('field_5980a41e46183', 0, $post_id); // Bien publié sur International
		}
	}

} 