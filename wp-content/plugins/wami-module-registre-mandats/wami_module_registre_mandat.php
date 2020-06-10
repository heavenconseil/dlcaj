<?php
/*
Plugin Name: Wami Module Registre des Mandats
Description: Création du fichier de registre des mandats
Version: 1.0.0
Author: WAMI Concept
Author URI: http://www.wami-concept.com/
*/

if ( ! defined( 'ABSPATH' ) )	exit;


if( !class_exists('wami_module_registre_mandat') ):
	
	require_once('wami_module_registre_mandat_table.php');	
	class wami_module_registre_mandat {
		
		function __construct(){			
			register_activation_hook(__FILE__, array($this, 'active_plugin'));

			$this->plugin_page_title  	= 'Registre des mandats';
			$this->plugin_menu_title  	= '<div class="w-menu-item">Registre des mandats</div>';
			$this->plugin_menu_slug   	= 'wami-module-de-registre-des-mandats';
			$this->capability         	= 'level_8';		

			$this->plugin_url 			= plugins_url('/', __FILE__);

			// Pour la BDD
			global $wpdb;
			$this->table 				= $wpdb->base_prefix.'wami_registre_mandat';
			$this->charset 				= $wpdb->get_charset_collate();			

			add_action('admin_menu', array($this,'display_menu'), 6);

			//et l'update de ma table à chaque maj/creation d'un mandat
			add_action('post_updated',  array($this, 'update_table_registre_mandat'), 16, 4 );

			// Pour l'export en CSV des stats d'un module
			add_action('admin_init', array($this, 'export_csv_registre_des_mandats'));			
		}


		public function active_plugin(){
			global $wpdb;

			$sql = "CREATE TABLE $this->table (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`post_id` int(11) NOT NULL,
				`bien_ref` int(40) NOT NULL,				
				`statut` varchar(255) NOT NULL DEFAULT '',	
				`date_mandat` varchar(255) NOT NULL DEFAULT '',	
				`noms_mandants` longtext NULL DEFAULT '',	
				`adresses_mandants` longtext NULL DEFAULT '',	
				`objet_mandat` varchar(255) NULL DEFAULT '',
				`nature_situation_bien` longtext NULL DEFAULT '',		
				`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',			
				`updated` datetime NULL,
				UNIQUE KEY id (id),
				KEY post_id (post_id)
			) $this->charset;";
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta($sql);
		}


		public function update_table_registre_mandat($post_ID, $post_after=false, $post_before=false, $ref_bien=false){	
			$post_object = $post_after ? $post_after : get_post($post_ID);
			$ref_bien = $ref_bien ? sprintf("%06d", $ref_bien) : sprintf("%06d", get_field('bien_ref', $post_ID));

			if($post_object->post_type == "biens" && $ref_bien):

				$mandant_nom = $mandant_adresse = "";
				if(have_rows('proprietaires', $post_ID)) :
		        	$i=0;
		            while(have_rows('proprietaires', $post_ID)): the_row(); 
		            	$nom = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social').' représentée par : '.get_sub_field('proprietaire_société_attention_de') : get_sub_field('proprietaire_nom').' '.get_sub_field('proprietaire_prenom');  
		            	$adresse = get_sub_field('proprietaire_adresse').' '.get_sub_field('proprietaire_cp').' '.get_sub_field('proprietaire_ville').' '.get_sub_field("proprietaire_pays");      
		                if($i==0) {
		                	$mandant_nom .= $nom;
		                	$mandant_adresse .= $adresse;
		                }
		                if($i==1) {
		                	$mandant_nom .= ' - '.$nom;  
		                	$mandant_adresse .= ' - '.$adresse;
		                }  
		                $i++;             
		            endwhile;
		        endif; 	
				$adresse_bien = ( get_field('bien_adresse_1', $post_ID) && get_field('bien_adresse_cp', $post_ID) && get_field('bien_adresse_ville', $post_ID) && get_field("bien_adresse_pays", $post_ID) ) ? get_field('bien_adresse_1', $post_ID).' '.get_field('bien_adresse_cp', $post_ID).' '.get_field('bien_adresse_ville', $post_ID).' '.get_field("bien_adresse_pays", $post_ID) : "";
				$now = date("Y-m-d H:i:s");

				global $wpdb;
				$row = $wpdb->get_row( "SELECT * FROM $this->table WHERE post_id = $post_ID");	
				$status = $post_object->post_status;$status = $post_object->post_status;
				if($status == 'draft' || $status == 'pending')		$status = 'Mandat en cours de rédaction';
				elseif($status == 'trash') 							$status = 'Mandat annulé';
				elseif($status == 'publish') 						$status = 'Mandat publié';

				$objet_mandat = get_field('bien_mandat', $post_ID) ? get_field('bien_mandat', $post_ID) : false;
				if( $objet_mandat && is_array($objet_mandat) ){
					// Mandat de recherche
					if($objet_mandat['value'] == 'mandat_de_recherche') $objet_mandat = "Mandat de recherche";
					// Délégation de mandat
					else if($objet_mandat['value'] == 'mandat_delegation') $objet_mandat = "Délégation de mandat"; //Mandat de vente (simple, exclusif ou exigence reste des mandats de vente)
					else $objet_mandat = "Mandat de vente";
				}
				
				if($row) :
					$wpdb->update(
						// table :
						$this->table,
						// data :
						array( 
						'post_id'      			=> $post_ID,
						'bien_ref'     			=> $ref_bien,
						'statut'				=> $status,
						'date_mandat'      		=> get_field('bien_date_de_referencement', $post_ID) ? get_field('bien_date_de_referencement', $post_ID) : "Mandat en cours de référencement",
						'noms_mandants'     	=> $mandant_nom,
						'adresses_mandants'     => $mandant_adresse,
						'objet_mandat'      	=> $objet_mandat,
						'nature_situation_bien' => get_field('bien_type', $post_ID) ? get_field('bien_type', $post_ID).' : '.$adresse_bien : "",		
						'updated'      			=> $now,
						), 
						//where :
						array( 
							'post_id' 			=> $post_ID, 
						)
					);
				; else :
					 $row = $wpdb->insert($this->table, array(
						'post_id'      			=> $post_ID,
						'bien_ref'     			=> $ref_bien,						
						'statut'				=> $status,
						'date_mandat'      		=> get_field('date_mandat', $post_ID) ? get_field('date_mandat', $post_ID) : "Mandat en cours de signature",	
						'noms_mandants'     	=> $mandant_nom,
						'adresses_mandants'     => $mandant_adresse,
						'objet_mandat'      	=> $objet_mandat,
						'nature_situation_bien' => get_field('bien_type', $post_ID) ? get_field('bien_type', $post_ID).' : '.$adresse_bien : "",	
						'created'      			=> $now,		
						'updated'      			=> $now,
					));
				endif;	

				//debug($row); exit;

			endif;
		}


		public function display_menu(){
			add_menu_page(
				$this->plugin_page_title,
				$this->plugin_menu_title,
				$this->capability,
				$this->plugin_menu_slug,
				array($this,'display_page_registre_des_mandats'), 
				'dashicons-book', 59);
		}


		public function display_page_registre_des_mandats(){
			$this->display_header();

			if(!empty($_POST['action'])): 
				$myAction = $_POST['action'];
				$this->$myAction();			
			; else : 
				$this->display_form();
			endif;
			
			$this->display_footer();			
		}


		public function display_header($with_menu = true){ ?>
			<div class="wrap">
			<h2> <?php echo $this->plugin_page_title; ?></h2>	<?php }

		public function display_footer(){ ?>
			</div><!-- /.wrap -->
		<?php }


		public function display_form(){
			$this->return_all_mandats();?>			
			<form class="export_csv" action="" method="post" enctype="multipart/form-data"> 
				<p>Télécharger le fichier CSV d'export de registre des mandats</p>
				<input type="hidden" name="action" value="export_csv_registre_des_mandats">
				<button type="submit" name='export_csv' class="button button-primary">Exporter</button>
			</form>	
			<?php 
			$wami_user_list_table = new wami_manage_registre_mandats_table();
		    $wami_user_list_table->prepare_items();
		    $wami_user_list_table->display(); 
		}



		public function wami_format_csv($text){
			$text = stripslashes($text);
			$text = utf8_decode($text);
			return $text;
		}

		public function export_csv_registre_des_mandats(){	
			if(isset($_POST['action']) && $_POST['action']=='export_csv_registre_des_mandats') :	

			    header('Content-Description: File Transfer');
				header('Content-Type: text/csv');
		        header('Content-Disposition: attachment;filename='.date("Y-m-d").'_registre_des_mandats.csv');
			    ob_clean();

				$out = fopen('php://output', 'w');	

				fputcsv($out, array('Registre des mandats'), ';');

				$rows = $this->return_all_mandats(); 	

				$titres = array();
				foreach( $rows['titre'] as $k=>$r ): 
					$titres[] =  $this->wami_format_csv($r);
				endforeach; 
				fputcsv($out, $titres, ';');
				
				foreach( $rows as $l=>$row ):
					if($l != "titre"):
						$ligne = array();
						foreach( $row as $k=>$r ): 
							$ligne[] =  $this->wami_format_csv($r);
						endforeach;
						fputcsv($out, $ligne, ';');
					endif; 
					
				endforeach; 		

				fclose($out);
				die();	

			endif;
		}

		function return_all_mandats(){		
			// On traite la 1er ligne (titre des colonnes)
			$resultat_global['titre'] = array(
				'mandat_numero'		=> 'N° du mandat',
				'mandat_date'		=> 'Date du mandat',
				'mandant_nom'		=> 'Nom du ou des mandant(s)',
				'mandant_adresse'	=> 'Adresse du ou des mandant(s)',
				'mandat_objet'     	=> 'Objet du mandat',
				'mandat_bien'		=> 'Nature et situation du bien',
				'statut'			=> 'Statut'
			);	
 			
 			global $wpdb;
			//$existing_columns = $wpdb->get_results("DESCRIBE $this->table", 0);
			//debug($existing_columns);        
			$rows = $wpdb->get_results("SELECT * FROM $this->table");
			
			foreach($rows as $r){
				$resultat_global[] = array(
            		'mandat_numero'		=> $r->bien_ref,
					'mandat_date'		=> $r->date_mandat,
					'mandant_nom'		=> $r->noms_mandants,
					'mandant_adresse'	=> $r->adresses_mandants,
					'mandat_objet'     	=> $r->objet_mandat,
					'mandat_bien'		=> $r->nature_situation_bien,
					'statut'			=> $r->statut,
            	);  
			}
            /*$args = array(
                'post_type'     => 'biens',                        
                'post_status'   => array('publish', 'draft', 'pending'),
                'meta_key'      => 'bien_ref',
                'orderby'       => 'meta_value',
                'order'         => 'DESC',
                'posts_per_page'=> -1
            );
            $query_biens = new WP_Query( $args ); 
            if($query_biens->have_posts()) :
            	while($query_biens->have_posts()) :
                    $query_biens->the_post(); 

                		$mandant_nom = $mandant_adresse = "";
				        if(have_rows('proprietaires', $mandat_id)) :
				        	$i=0;
				            while(have_rows('proprietaires', $mandat_id)): the_row(); 
				            	$nom = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social').' représentée par : '.get_sub_field('proprietaire_société_attention_de') : get_sub_field('proprietaire_nom').' '.get_sub_field('proprietaire_prenom');  
				            	$adresse = get_sub_field('proprietaire_adresse').' '.get_sub_field('proprietaire_cp').' '.get_sub_field('proprietaire_ville').' '.get_sub_field("proprietaire_pays");      
				                if($i==0) {
				                	$mandant_nom .= $nom;
				                	$mandant_adresse .= $adresse;
				                }
				                if($i==1) {
				                	$mandant_nom .= ' - '.$nom;  
				                	$mandant_adresse .= ' - '.$adresse;
				                }  
				                $i++;             
				            endwhile;
				        endif; 	

						$adresse_bien 		= ( get_field('bien_adresse_1') && get_field('bien_adresse_cp') && get_field('bien_adresse_ville') && get_field("bien_adresse_pays") ) ? get_field('bien_adresse_1').' '.get_field('bien_adresse_cp').' '.get_field('bien_adresse_ville').' '.get_field("bien_adresse_pays") : "";

                    	$resultat_global[] = array(
                    		'mandat_numero'		=> get_field('bien_ref'),
							'mandat_date'		=> get_field('date_mandat') ? get_field('date_mandat') : "brouillon - Mandat en cours de signature",
							'mandant_nom'		=> $mandant_nom,
							'mandant_adresse'	=> $mandant_adresse,
							'mandat_objet'     	=> "Mandat de vente",
							'mandat_bien'		=> get_field('bien_type') ? get_field('bien_type').' : '.$adresse_bien : "",
                    	);  

                endwhile;                     
                wp_reset_postdata(); 
            endif;*/

			return $resultat_global;			
		}	


	} // FIN DE LA CLASS
endif;

$wami_module_registre_mandat = new wami_module_registre_mandat(); 