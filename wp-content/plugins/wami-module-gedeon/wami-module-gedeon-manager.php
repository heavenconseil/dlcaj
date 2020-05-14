<?php
/*
Plugin Name: Wami Module for Gedeon
Description: Création du fichier xml d'export des annonces pour les diffuser via Gédeon
Version: 1.0.0
Author: WAMI Concept
Author URI: http://www.wami-concept.com/
*/

if ( ! defined( 'ABSPATH' ) )	exit;


if( !class_exists('wami_module_gedeon') ):
	class wami_module_gedeon {
		
		function __construct(){			
			register_activation_hook(__FILE__, array($this, 'active_plugin'));

			$this->plugin_page_title  = 'Module Gedeon';
			$this->plugin_menu_title  = '<div class="w-menu-item">Module Gedeon</div>';
			$this->plugin_menu_slug   = 'wami-module-gedeon';
			$this->capability         = 'level_8';

			$this->plugin_url = plugins_url('/', __FILE__);

			add_action('admin_menu', array($this,'display_menu'), 6);

			// Pour l'export en CSV du fichier xml
			add_action('admin_init', array($this, 'export_xml_for_gedeon'));	

			// Pour le cron de l'export en CSV du fichier xml
			add_filter('cron_schedules', array($this,'cron_add_recurrence'));
			if(!wp_next_scheduled('wami_xml_for_gedeon_cron')){
				wp_schedule_event(time(), 'every6hours', 'wami_xml_for_gedeon_cron');
				//wp_schedule_event(time(), 'daily', 'wami_xml_for_gedeon_cron');
			}
			add_action('wami_xml_for_gedeon_cron', array($this, 'cron_export_xml_for_gedeon'));	

			// Pour retirer le cron 
			wp_clear_scheduled_hook( 'wami_xml_for_gedeon_cron' );

			$this->prod  = false;	
			$this->debug = false;	
		}


		public function active_plugin(){
			//wami_module_gedeon_db::create_tables();
		}


		public function display_menu(){
			add_menu_page(
				$this->plugin_page_title,
				$this->plugin_menu_title,
				$this->capability,
				$this->plugin_menu_slug,
				array($this,'display_page_module_gedeon'), 
				'dashicons-networking', 58);
		}


		public function display_page_module_gedeon(){
			if(!empty($_POST['action'])): 
				$myAction = $_POST['action'];
				$this->$myAction();			
			; else : 
				$this->display_header();
				$this->display_form();
				$this->display_footer();
			endif;	
		}


		public function display_header($with_menu = true){ ?>
			<div class="wrap">
			<h2> <?php echo $this->plugin_page_title ?></h2>	<?php }


		public function display_footer(){ ?>
			</div><!-- /.wrap -->
		<?php }


		public function display_form(){
			?>			
			<form class="export_xml" action="" method="post" enctype="multipart/form-data"> 
				<p>Télécharger le fichier XML d'export pour Gedeon</p>
				<p>Avant son envoi, merci de valider la conformité du fichier : <a target="_blank" href="https://gedeon.im/import-validate-xml"><b>Validateur Gédéon</b></a></p>
				<input type="hidden" name="action" value="export_xml_for_gedeon">
				<button type="submit" name='export_xml' class="button button-primary">Exporter</button>
			</form>			
			<?php 
			//debug($this->get_liste_des_biens_a_exporter());
			$biens_to_export = $this->get_liste_des_biens_a_exporter();
			echo '<h3>'.count($biens_to_export).' biens à publier via Gedeon</h3>';
			foreach($biens_to_export as $b): 	
				echo '<p><b>Mandat '.$b->mandate['type'].' ref : '.$b->mandate['number'].' [ID : '.$b->reference.']</b>';	
				echo ' "'.$b->titre.'" publié sur : ';
				foreach ($b->diffusions as $d) echo '<br> - '.$d;		
				echo "</p>";					
				//debug($b, $b->titre.' - '.$b->reference);					
			endforeach;
		}


		public function cron_add_recurrence($schedules){
			$schedules['every6hours'] = array(
				//'interval' => 15*MINUTE_IN_SECONDS,
				//'display'  => __('Four times per hour')
				'interval' => 21600, // Every 6 hours
        		'display'  => __( 'Every 6 hours' ),
			);			
			return $schedules;
		}


		public function cron_export_xml_for_gedeon(){
				
			// FTP TEST DE WAMI
			// $hote  				= 'ftp.cluster006.ovh.net';
			// $identifiant 		= 'wamiconc-dlcaj';
			// $pass  				= 'H5Ti2n2xT4';			
			// FTP TEST DE WAMI SUR DELACOUR
			// $hote  				= 'ftp.cluster023.hosting.ovh.net';
			// $identifiant 		= 'delacourfe';
			// $pass  				= '2lacour0jardiN2015';	
			// FTP DE GEDEON  	        
			$hote  				= 'ftp.gedeon.im';
			$identifiant 		= 'import-specific-wami-concept';
			$pass  				= 'doax7Koo7ooj';
			 
			// on crée le fichier sur notre ftp
			$upload_dir  = wp_upload_dir();
	        $file_name 	 = 'dlcaj_export_xml_for_gedeon_'.time().'.xml';
			$open 		 = $upload_dir['basedir']."/export_xml/".$file_name;
			$local_file = $this->create_export_xml_for_gedeon($file_name, $open, 0);			 								 
				
			if( $this->debug ) :				
		        // METHODE : Mise en place d'une connexion basique
				if( $local_file ){	
					$fp 	= fopen($local_file, 'r');								
					
					// set up a connection or die
					$conn_id = @ftp_connect($hote);
					if ( $conn_id ) {
					    echo "Connected as $identifiant@$conn_id\n";
					} else {
					    echo "Couldn't connect as $conn_id\n";
					}

					// Identification avec un nom d'utilisateur et un mot de passe
					$login_result = @ftp_login($conn_id, $identifiant, $pass);
					if ( $login_result ) {
					    echo "login as $identifiant@$conn_id\n";
					} else {
					    echo "Couldn't login as $identifiant\n";
					}

					// Activation du mode passif (obligatoire sur serveur de prod avec OVH !)
					ftp_pasv($conn_id, true);

					// Tente de charger le fichier via ftp_put
					$remote_file = "/".$file_name;
					if (ftp_put($conn_id, $remote_file, $local_file, FTP_ASCII)) {
						echo "Le fichier $local_file a été chargé avec succès\n";
					} else {
						echo "Il y a eu un problème lors du chargement du fichier $local_file\n";
					}

					// Fermeture de la connexion et du pointeur de fichier
					ftp_close($conn_id);
					fclose($fp);
					die();		
			    } 

			; elseif( $this->prod ) : 
				if( $local_file ){	
					$fp 	= fopen($local_file, 'r');	
					// set up a connection or die
					$conn_id = @ftp_connect($hote);
					// Identification avec un nom d'utilisateur et un mot de passe
					$login_result = @ftp_login($conn_id, $identifiant, $pass);					
					// Activation du mode passif
					ftp_pasv($conn_id, true);
					// Tente de charger le fichier via ftp_fput
					$remote_file = "/".$file_name;
					ftp_put($conn_id, $remote_file, $local_file, FTP_ASCII);					
					// Fermeture de la connexion et du pointeur de fichier
					ftp_close($conn_id);
					fclose($fp);		
					die();						
			    }

			endif;

		}

		
		public function create_export_xml_for_gedeon($file_name, $open, $last_action){
			// Build your file contents as a string
			$file_contents  = $this->construct_my_xml_for_gedeon();
			$upload_dir 	= wp_upload_dir();
			header('Content-Description: File Transfer');
			header('Content-Type: text/xml; charset=utf-8');
			header('Content-Disposition: attachment; filename='.$file_name);
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			ob_clean();
			flush();
			$my_file = @fopen($open,"w");
			if ($my_file != false){
				fwrite($my_file, $this->wami_format_utf8($file_contents));
				//if($last_action){
					fclose($my_file); 
					//die();
				//} 
			}
			if($last_action) die();
			return $open; die();
		}

		
		public function export_xml_for_gedeon(){	
			if(isset($_POST['action']) && $_POST['action']=='export_xml_for_gedeon'){				
				/*if( $this->debug ){
					$this->cron_export_xml_for_gedeon(); 					
					exit;
				}*/	
				$file_name = 'dlcaj_export_xml_for_gedeon_'.time().'.xml';
				$this->create_export_xml_for_gedeon($file_name, 'php://output', 1);					
			}
		}


		public function wami_format_utf8($text){ 
			$text = stripslashes($text);
			$text = str_replace('&nbsp;', '', $text);				
			$text = str_replace('&', 'et', $text);
			$text = str_replace('’', "'", $text);	
			$text = utf8_decode($text);
			//$text = utf8_encode($text);
			return $text;
		}


		function construct_my_xml_for_gedeon(){
			$file_contents  = '<?xml version="1.0" encoding="iso-8859-1"?>';
			$file_contents .= '<agencies xmlns="http://www.gedeon.im/xmlns/agencies/v1" version="1.0" generator="mon-logiciel/3.1">';

				$file_contents .= '<agency id="dlcaj">';

					$file_contents .= '<contact>';
						$file_contents .= '<name>De La Cour Au Jardin</name>';

						$file_contents .= '<localization>';
							$file_contents .= '<address>157 Rue du Faubourg Saint Antoine</address>';
							$file_contents .= '<postCode>75011</postCode>';
							$file_contents .= '<city>Paris</city>';
							$file_contents .= '<country>FR</country>';
						$file_contents .= '</localization>';

						$file_contents .= '<phone>0123456789</phone>';

						$file_contents .= '<emails>';
							$file_contents .= '<email>contact@delacouraujardin.com</email>';
						$file_contents .= '</emails>';

						$file_contents .= '<web>http://www.delacouraujardin.com</web>';
						$file_contents .= '<logo>http://www.delacouraujardin.com/wp-content/themes/wami/lib/img/logo-middle-office.png</logo>';
					$file_contents .= '</contact>';


					$file_contents .= '<ads>';
						
						$biens_to_export = $this->get_liste_des_biens_a_exporter();
						foreach($biens_to_export as $b): 									
							$file_contents .= '<ad reference="'.$b->reference.'">';

								$file_contents .= '<transaction>'.$b->transaction.'</transaction>';

								$file_contents .= '<type sub="'.$b->type['sub'].'">'.$b->type['name'].'</type>';

								$file_contents .= '<localization>';
									$file_contents .= '<address>'.$b->localization['address'].'</address>';
									$file_contents .= '<postCode>'.$b->localization['postCode'].'</postCode>';
									$file_contents .= '<city>'.$b->localization['city'].'</city>';
									$file_contents .= '<country>'.$b->localization['country'].'</country>';
								$file_contents .= '</localization>';

								$file_contents .= '<price currency="euro">'.$b->price.'</price>';

								$file_contents .= '<rooms>'.$b->rooms.'</rooms>';

								$file_contents .= '<surface>'.$b->surface.'</surface>';

								$file_contents .= '<mandate>';
									$file_contents .= '<type>'.$b->mandate['type'].'</type>';
									$file_contents .= '<number>'.$b->mandate['number'].'</number>';
								$file_contents .= '</mandate>';

								$file_contents .= '<texts>';
									$file_contents .= '<text target="internet" lang="fr">'.$b->description.'</text>';
									$file_contents .= '<text target="press" lang="fr">'.$b->description.'</text>';
								$file_contents .= '</texts>';

								$file_contents .= '<titles>';
									$file_contents .= '<title lang="fr">'.$b->titre.'</title>';
								$file_contents .= '</titles>';
								$file_contents .= '<contact>';
									$file_contents .= '<name>'.$b->contact['name'].'</name>';
									$file_contents .= '<phone>'.$b->contact['phone'].'</phone>';
									$file_contents .= '<email>'.$b->contact['email'].'</email>';
								$file_contents .= '</contact>';

								if(is_array($b->medias) && !empty($b->medias)) :
									$file_contents .= '<medias>';
										foreach($b->medias as $m) {
											if($m && $m!='' && $m['url']!='' ){
												$file_contents .= '<media type="photo">';
												$file_contents .= '<url>'.$m['url'].'</url>';
												$file_contents .= '<updated>'.$m['updated'].'</updated>';
												$file_contents .= '</media>';
											}
										}
									$file_contents .= '</medias>';										
								endif; 

								$file_contents .= '<extras>';
									$file_contents .= '<extra key="arrondissement">'.$b->extras["arrondissement"].'</extra>';
									$file_contents .= '<extra key="nb_etages">'.$b->extras["nb_etage"].'</extra>';
									$file_contents .= '<extra key="etage">'.$b->extras["etage"].'</extra>';
									$file_contents .= '<extra key="ascenseur">'.$b->extras["ascenseur"].'</extra>'; 
									$file_contents .= '<extra key="nb_chambres">'.$b->extras["nb_chambres"].'</extra>';
									$file_contents .= '<extra key="nb_pieces">'.$b->extras["nb_pieces"].'</extra>';
									$file_contents .= '<extra key="nb_sdb">'.$b->extras["nb_sdb"].'</extra>';
									$file_contents .= '<extra key="nb_sde">'.$b->extras["nb_sde"].'</extra>';
									$file_contents .= '<extra key="nb_wc">'.$b->extras["nb_wc"].'</extra>';
									$file_contents .= '<extra key="wc_separes">'.$b->extras["wc_separes"].'</extra>';
									$file_contents .= '<extra key="niveau">'.$b->extras["niveau"].'</extra>';
									$file_contents .= '<extra key="sous_sol">'.$b->extras["sous_sol"].'</extra>';
									$file_contents .= '<extra key="veranda">'.$b->extras["veranda"].'</extra>';
									$file_contents .= '<extra key="salon">'.$b->extras["salon"].'</extra>';
									$file_contents .= '<extra key="sauna">'.$b->extras["sauna"].'</extra>';
									$file_contents .= '<extra key="grenier">'.$b->extras["grenier"].'</extra>';
									$file_contents .= '<extra key="balcon">'.$b->extras["balcon"].'</extra>';
									$file_contents .= '<extra key="terrasse">'.$b->extras["terrasse"].'</extra>';
									$file_contents .= '<extra key="garage">'.$b->extras["garage"].'</extra>';
									$file_contents .= '<extra key="cave">'.$b->extras["cave"].'</extra>';
									$file_contents .= '<extra key="cour">'.$b->extras["cour"].'</extra>';	
									$file_contents .= '<extra key="gardien">'.$b->extras["gardien"].'</extra>';
									$file_contents .= '<extra key="handicapes">'.$b->extras["handicapes"].'</extra>';
									$file_contents .= '<extra key="duplex">'.$b->extras["duplex"].'</extra>';
									$file_contents .= '<extra key="triplex">'.$b->extras["triplex"].'</extra>';
									$file_contents .= '<extra key="souplex">'.$b->extras["souplex"].'</extra>';
									$file_contents .= '<extra key="annee_construction">'.$b->extras["annee_construction"].'</extra>';
									$file_contents .= '<extra key="annee_renovation">'.$b->extras["annee_renovation"].'</extra>';
									$file_contents .= '<extra key="surface_hab">'.$b->extras["surface_hab"].'</extra>'; 
									$file_contents .= '<extra key="surface_carrez">'.$b->extras["surface_carrez"].'</extra>';
									$file_contents .= '<extra key="surface_terrain">'.$b->extras["surface_terrain"].'</extra>';
									$file_contents .= '<extra key="surface_terrasse">'.$b->extras["surface_terrasse"].'</extra>'; 
									$file_contents .= '<extra key="surface_balcon">'.$b->extras["surface_balcon"].'</extra>';
									$file_contents .= '<extra key="surface_sejour">'.$b->extras["surface_sejour"].'</extra>';
									$file_contents .= '<extra key="surface_annexes">'.$b->extras["surface_annexes"].'</extra>'; 
									$file_contents .= '<extra key="statut_nego">'.$b->extras["statut_nego"].'</extra>'; 
									$file_contents .= '<extra key="copropriete">'.$b->extras["copropriete"].'</extra>';
									$file_contents .= '<extra key="copropriete_nb_lots">'.$b->extras["copropriete_nb_lots"].'</extra>';
									$file_contents .= '<extra key="quote_part_annuelle">'.$b->extras["quote_part_annuelle"].'</extra>';
									$file_contents .= '<extra key="chauffage">'.$b->extras["chauffage"].'</extra>';  
	 								$file_contents .= '<extra key="cheminee">'.$b->extras["cheminee"].'</extra>';
									$file_contents .= '<extra key="climatisation">'.$b->extras["climatisation"].'</extra>';
									$file_contents .= '<extra key="cuisine">'.$b->extras["cuisine"].'</extra>'; 
									$file_contents .= '<extra key="jardin">'.$b->extras["jardin"].'</extra>'; 
									$file_contents .= '<extra key="piscine">'.$b->extras["piscine"].'</extra>'; 
									$file_contents .= '<extra key="orientation">'.$b->extras["orientation"].'</extra>'; 	
									$file_contents .= '<extra key="calme">'.$b->extras["calme"].'</extra>'; 
									$file_contents .= '<extra key="transport">'.$b->extras["transport"].'</extra>'; 
									$file_contents .= '<extra key="taxe_fonciere">'.$b->extras["taxe_fonciere"].'</extra>';
									$file_contents .= '<extra key="charges">'.$b->extras["charges"].'</extra>';
									$file_contents .= '<extra key="honoraires">'.$b->extras["honoraires"].'</extra>';
									$file_contents .= '<extra key="prix_hors_honoraires">'.$b->extras["prix_hors_honoraires"].'</extra>';
									$file_contents .= '<extra key="pourcentage_honoraires">'.$b->extras["pourcentage_honoraires"].'</extra>';
									$file_contents .= '<extra key="honoraires_a_charge_de">'.$b->extras["honoraires_a_charge_de"].'</extra>'; 
									$file_contents .= '<extra key="date_dispo">'.$b->extras["date_dispo"].'</extra>';
									$file_contents .= '<extra key="dpe_conso_en">'.$b->extras["dpe_conso_en"].'</extra>';
									$file_contents .= '<extra key="dpe_ges">'.$b->extras["dpe_ges"].'</extra>'; 
									$file_contents .= '<extra key="coup_de_coeur">'.$b->extras["coup_de_coeur"].'</extra>';
									$file_contents .= '<extra key="type_mandat">'.$b->extras["type_mandat"].'</extra>';
									$file_contents .= '<extra key="url">'.$b->extras["url"].'</extra>';
									$file_contents .= '<extra key="url_bareme">'.$b->extras["url_bareme"].'</extra>';
									$file_contents .= '<extra key="syndic_detail_procedure">'.$b->extras["syndic_detail_procedure"].'</extra>'; 
									$file_contents .= '<extra key="neg_tel_pro">'.$b->extras["neg_tel_pro"].'</extra>';
									$file_contents .= '<extra key="neg_prenom">'.$b->extras["neg_prenom"].'</extra>';
									$file_contents .= '<extra key="neg_nom">'.$b->extras["neg_nom"].'</extra>';
									$file_contents .= '<extra key="neg_num">'.$b->extras["neg_num"].'</extra>';
									$file_contents .= '<extra key="neg_mail">'.$b->extras["neg_mail"].'</extra>';
								$file_contents .= '</extras>';

								if(is_array($b->diffusions) && !empty($b->diffusions)) :
									$file_contents .= '<diffusions>';
										foreach($b->diffusions as $d) {
											$file_contents .= '<diffusion>'.$d.'</diffusion>';
										}
									$file_contents .= '</diffusions>';
								endif; 

							$file_contents .= '</ad>';
						endforeach;

					$file_contents .= '</ads>';

				$file_contents .= '</agency>';

			$file_contents .= '</agencies>';

			return $file_contents;
		}


		function get_liste_des_biens_a_exporter(){						

			global $wp_query;
			global $post;
			$biens = array();

			$args = array(
            	'post_type'		=> 'biens',
            	'post_status'	=> 'publish',
            	'meta_query'	=> array(
            		'relation' => 'AND',
            		array(
                        'relation' => 'OR',
                        array(
                            'key'       => 'bien_vente_vendu',
                            'value'     => 0,
                            'compare'   => '='
                        ),
                        array(
                          'key' => 'bien_vente_vendu',
                          'compare' => 'NOT EXISTS'
                        ), 
                    ),
            		array(
                        'relation' => 'OR',
                        array(
                            'key'       => 'bien_publie_sur_le_bon_coin',
                            'value'     => 1,
                            'compare'   => '='
                        ),
                        array(
                        	'key'       => 'bien_publie_sur_se_loger',
                            'value'     => 1,
                            'compare'   => '='
                        ), 
                        array(
                        	'key'       => 'bien_publie_sur_international',
                            'value'     => 1,
                            'compare'   => '='
                        ), 
                    ),  
            	),			            	
            	'posts_per_page'=> -1
            );
            $query_biens = new WP_Query( $args );
            if($query_biens->have_posts()) : 
            	while($query_biens->have_posts()) :
            		$query_biens->the_post();  
        				
        				$generique_type = "other";
	        				if(get_field('bien_type') == "maison") $generique_type = "house"; 
	        				elseif(get_field('bien_type') == "appartement") $generique_type = "flat";
	        				elseif(get_field('bien_type') == "garage") $generique_type = "garage";
	        				elseif(get_field('bien_type') == "terrain") $generique_type = "terrain";
	        				elseif(get_field('bien_type') == "local") $generique_type = "industrial";
	        				elseif(get_field('bien_type') == "bureau") $generique_type = "office";
	        				elseif(get_field('bien_type') == "commerce") $generique_type = "shop";

        				$medias = array();
	                    	if(have_rows('bien_image_slider')):
	                    	while(have_rows('bien_image_slider')): the_row(); 
	                    		$image = get_sub_field('image'); 
	                    		$medias[] = array(
	                    			'url' 		=> $image['url'],
		                    		'updated' 	=> date('c', strtotime($image['modified'])),
		                    		//'all_object'=> $image,
		                    	);
			        		endwhile; endif; 

		        		$diffusions = array();
	                    	if(get_field('bien_publie_sur_le_bon_coin')) $diffusions[] = 'ubiflow';
	                    	if(get_field('bien_publie_sur_se_loger')) $diffusions[] = 'seloger';
	                    	if(get_field('bien_publie_sur_international')) $diffusions[] = 'immofrance';

	                    $content = wpautop( $post->post_content );
	                    $content = strip_tags($content);

            			$ville = wami_get_villedubien($post->ID) ? wami_get_villedubien($post->ID) : get_field("bien_adresse_ville");
            			$cp = wami_get_cpdelavilledubien($post->ID) ? wami_get_cpdelavilledubien($post->ID) : get_field("bien_adresse_cp");
            			//$ville = get_field("bien_adresse_ville");
            			
            			if( 
            				get_field('bien_ref') && 
            				get_field('bien_type') && 
            				get_field("bien_adresse_1") && 
            				get_field("bien_adresse_cp") && 
            				$ville && 
            				get_field("bien_adresse_pays") && 
            				$this->return_code_pays(get_field("bien_adresse_pays")) && 
            				get_field("bien_prix_et_honoraires") && 
            				//is_numeric(get_field("bien_nb_piece")) && 
            				//is_numeric(get_field("bien_surface_habitable")) && 
            				//get_field("bien_agent_mail") && 
            				//get_field("bien_agent_telephone") && 
            				$content != "" &&
            				!empty($medias) ) :            	
	                    	
	                    	$amb_id = $post->post_author; 
							$amb_type = get_field("type_ambassadeur", "user_".$amb_id);							
							// Plusieurs choix possibles parmi : Gaz OU Electrique OU Fuel
							$chauffage = get_field("bien_chauffage_type") ? get_field("bien_chauffage_type") : ""; 
							if(get_field("bien_chauffage")=='electrique') $chauffage .= ' Electrique';
							if(get_field("bien_chauffage")=='gaz') $chauffage .= ' Gaz';
							if(get_field("bien_chauffage")=='fioul') $chauffage .= ' Fuel';

							$cuisine = "";
							if(get_field("bien_cuisine")=='Américaine'){
								$cuisine = get_field('bien_cuisine_equipee') ? "Américaine équipée" : "Américaine";
							}
							else if(get_field("bien_cuisine")=='Séparée'){
								$cuisine = get_field('bien_cuisine_equipee') ? "Equipée" : "Indépendante";
							} 

							$type_mandat = get_field("bien_mandat");	

							$tableau_infos_bien = array(
	                    		'reference' 	=> $post->ID,
								'transaction' 	=> "S",
								'type' 			=> array(
													'sub'  		=> get_field('bien_type'),
													'name' 		=> $generique_type,
								),
								'localization' 	=> array(
													'address'  	=> get_field("bien_adresse_1"),
													'postCode' 	=> $cp,
													'city' 	   	=> $ville,
													'country'  	=> $this->return_code_pays(get_field("bien_adresse_pays")),
								),			
								'price' 		=> get_field("bien_prix_et_honoraires"), //if(get_field("bien_honoraires_charge_de") == "charge_vendeur") $prix = get_field("bien_prix");,								
								'description' 	=> $content,
								'titre' 		=> $post->post_title,								
								'medias'		=> $medias, 
								'diffusions'	=> $diffusions,
							);

							if(get_field("bien_nb_piece")) {
								$tableau_infos_bien['rooms']	= get_field("bien_nb_piece");
							}

							if(get_field("bien_surface_habitable")) {
								$tableau_infos_bien['surface']	= get_field("bien_surface_habitable");
							}

							if(get_field("bien_mandat") && get_field('bien_ref')) {
								$bien_mandat_type = get_field('bien_mandat');
								$tableau_infos_bien['mandate']	= array(
									'type' 		=> $bien_mandat_type['value']=="mandat_exclusif" || $bien_mandat_type['value']=="mandat_exigence" ? "exclusive" : "simple",
									'number'	=> get_field('bien_ref'),
								);
							}

							if(get_field("bien_agent_telephone") && get_field('bien_agent_mail')) {
								$tableau_infos_bien['contact']	= array(
									'name'		=> get_the_author(),
									'phone' 	=> $this->return_phone_format(get_field("bien_agent_telephone")),
									'email' 	=> get_field("bien_agent_mail") ,
								);
							}

							$prix_honoraire = get_field('bien_honoraires_montant') ? get_field('bien_honoraires_montant') : get_field('bien_prix_et_honoraires') - get_field('bien_prix'); 

							$tableau_infos_bien['extras'] = array(
									'arrondissement'		  => get_field("bien_adresse_arrondissement") ? get_field("bien_adresse_arrondissement") : 0,  // text
									'nb_etages'				  => get_field("bien_copro_nb_etages") ? get_field("bien_copro_nb_etages") : 0, // integer
									'etage'					  => get_field("bien_copro_etage_bien") ? get_field("bien_copro_etage_bien") : 0, // integer
									'ascenseur'				  => get_field("bien_ascenseur") ? 1 : 0,
									'nb_chambres' 			  => get_field("bien_nb_chambre") ? get_field("bien_nb_chambre") : 0, // integer
									'nb_pieces' 			  => get_field("bien_nb_piece") ? get_field("bien_nb_piece") : 0, // integer
									'nb_sdb' 				  => get_field("bien_nb_piece_de_bain") ? get_field("bien_nb_piece_de_bain") : 0, // integer
									'nb_sde' 				  => get_field("bien_nb_piece_eau") ? get_field("bien_nb_piece_eau") : 0, // integer
									'nb_wc' 				  => get_field("bien_wc_nb") ? get_field("bien_wc_nb") : 0, // integer
									'wc_separes' 			  => get_field("bien_wc_separes") ? 1 : 0, // bool
									'niveau' 				  => get_field("bien_nb_niveaux") ? get_field("bien_nb_niveaux") : 0, // integer
									'sous_sol' 				  => get_field("bien_sous_sol") ? 1 : 0, // bool
									'veranda' 				  => get_field("bien_charme_veranda") ? 1 : 0, // bool
									'salon' 				  => get_field("bien_salon") ? 1 : 0, // bool
									'sauna' 				  => get_field("bien_spa") || get_field("bien_hammam") ? 1 : 0, // bool
									'grenier' 				  => get_field("bien_grenier") ? 1 : 0, // bool
									'balcon' 				  => get_field("bien_terrasse_copie") ? 1 : 0, // bool
									'terrasse' 				  => get_field("bien_terrasse") ? 1 : 0, // bool
									'garage' 				  => get_field("bien_garage__parking") ? get_field("bien_garage__parking") : 0, // integer
									'cave' 					  => get_field("bien_cave") ? 1 : 0, // bool
									'cour' 					  => get_field("bien_charme_cour") ? 1 : 0, // bool
									'gardien' 				  => get_field("bien_copro_gardien") ? 1 : 0, // bool
									'handicapes' 			  => get_field("bien_acces_handique") ? 1 : 0, // bool
									'duplex' 				  => get_field("bien_type_niveaux") && get_field("bien_type_niveaux")=="duplex" ? 1 : 0, // bool
									'triplex' 				  => get_field("bien_type_niveaux") && get_field("bien_type_niveaux")=="triplex" ? 1 : 0, // bool
									'souplex' 				  => get_field("bien_type_niveaux") && get_field("bien_type_niveaux")=="souplex" ? 1 : 0, // bool
									'annee_construction' 	  => get_field("bien_annee_de_construction") ? get_field("bien_annee_de_construction") : 0, // year
									'annee_renovation' 		  => get_field("bien_renovation") ? get_field("bien_renovation") : 0, // year
									'surface_hab' 			  => get_field("bien_surface_habitable") ? get_field("bien_surface_habitable") : 0, // integer
									'surface_carrez' 		  => get_field("bien_superficie_carrez") ? get_field("bien_superficie_carrez") : 0, // integer
									'surface_terrain' 		  => get_field("bien_surface_terrain") ? get_field("bien_surface_terrain") : 0, // integer
									'surface_terrasse' 		  => get_field("bien_surface_terrasse") ? get_field("bien_surface_terrasse") : 0, // integer
									'surface_balcon' 		  => get_field("bien_surface_balcon") ? get_field("bien_surface_balcon") : 0, // integer
									'surface_sejour' 		  => get_field("bien_surface_piece_principale") ? get_field("bien_surface_piece_principale") : 0, // integer
									'surface_annexes' 		  => get_field("bien_surface_dependances") ? get_field("bien_surface_dependances") : 0, // integer
									'statut_nego' 			  => $amb_type["value"]=="negociateur" || $amb_type["value"]=="negociatrice" ? "Salarié" : ($amb_type["value"]=="agent_commercial" ? "Agent commercial" : ($amb_type["value"]=="presidente" ? "Agent immobilier" : "")), 
									'copropriete' 			  =>  get_field("bien_copropriete")=='oui' ? 1 : 0,
									'copropriete_nb_lots' 	  => get_field("bien_copro_nb_lot_copropriete") ? get_field("bien_copro_nb_lot_copropriete") : 0, // integer
									'quote_part_annuelle' 	  => get_field("bien_copro_charges_courantes_an") ? get_field("bien_copro_charges_courantes_an") : 0, // Charge
									'chauffage' 			  => $chauffage,
									'cheminee' 				  => get_field("bien_cheminee") ? 1 : 0, // Bool
									'climatisation' 		  => get_field("bien_clim") ? 1 : 0, // Bool
									'cuisine' 				  => $cuisine,
									'jardin' 				  => get_field("bien_charme_jardin") ? 1 : 0, // Bool
									'piscine' 				  => get_field("bien_piscine") || get_field('bien_charme_piscine') ? 1 : 0, // Bool
									'orientation' 			  => get_field("bien_exposition") ? get_field("bien_exposition") : 0,
									'calme' 				  => get_field("bien_environnement_tres_calme") || get_field("bien_environnement_calme") || get_field("bien_environnement_tres_urbain") || get_field("bien_environnement_urbain") ? 1 : 0, // Bool
									'transport' 			  => get_field("bien_environnement_acces_transport_route_excellents") || get_field("bien_environnement_acces_transport_route_faciles") || get_field("bien_environnement_acces_tgv") ? 1 : 0, // Bool
									'taxe_fonciere' 		  => get_field("bien_taxe_fonciere") ? get_field("bien_taxe_fonciere") : 0, // float
									'charges' 				  => get_field("bien_charges_annuelles") ? get_field("bien_charges_annuelles") : 0, // float
									'honoraires' 			  => get_field("bien_honoraires_montant") ? get_field("bien_honoraires_montant") : 0,
									'prix_hors_honoraires' 	  => get_field("bien_prix") ? get_field("bien_prix") : 0, // float
									//'pourcentage_honoraires'  => get_field("bien_honoraires_montant") && get_field('bien_prix_et_honoraires') ? get_field("bien_honoraires_montant") / get_field('bien_prix_et_honoraires') * 100 : 0,
									// Nouveau calcul selon mail du 29/01/2020 (sur marie.beaudon@wami-concept.com)
									'pourcentage_honoraires'  => $prix_honoraire / get_field('bien_prix') * 100,
									'honoraires_a_charge_de'  => get_field("bien_honoraires_charge_de")=='charge_acquereur' ? 'acquéreur' : (get_field("bien_honoraires_charge_de")=='charge_vendeur' ? 'vendeur' : ""),
									'date_dispo' 			  => get_field("bien_disponibilite") ? get_field("bien_disponibilite") : 0, // date
									'dpe_conso_en' 			  => get_field("bien_consommation__co2") ? get_field("bien_consommation__co2") : 0, // float
									'dpe_ges' 				  => get_field("bien_consommation_kw") ? get_field("bien_consommation_kw") : 0, // float	
									'coup_de_coeur' 		  => get_field("bien_coup_de_coeur") ? 1 : 0, // Bool
									'type_mandat' 			  => $type_mandat['label'],
									'url' 					  => get_permalink($post->ID),
									'url_bareme' 			  => "http://clients.wami-concept.com/dlcaj_mo/baremes/", // text
									'syndic_detail_procedure' => get_field("bien_copro_litiges") ? "Litige en cours" : 0, // text
									'neg_num' 				  => get_field("type_ambassadeur_agent_rsac_num", "user_".$amb_id) ? get_field("type_ambassadeur_agent_rsac_num", "user_".$amb_id) : 0, // text	
									'neg_prenom' 			  => get_the_author_meta('first_name', $amb_id) ? get_the_author_meta('first_name', $amb_id) : 0, // text
									'neg_nom' 				  => get_the_author_meta('last_name', $amb_id) ? get_the_author_meta('last_name', $amb_id) : 0, // text
									'neg_tel_pro' 			  => get_field("ambassadeur_telephone", "user_".$amb_id) ? $this->return_phone_format(get_field("ambassadeur_telephone", "user_".$amb_id)) : 0, // text : Info negociateur
									'neg_mail' 				  => get_field("ambassadeur_mail", "user_".$amb_id) ? get_field("ambassadeur_mail", "user_".$amb_id) : 0, // text
	                    	);
															

	                    	$biens[$post->ID] = (object) $tableau_infos_bien;	                    	
	                   	endif;
                endwhile; 
                wp_reset_postdata();
            endif; 
							
			return $biens;
		}


		public function return_phone_format($str){
			$str = str_replace(' ', '', $str);
			$str = str_replace('&nbsp;', '', $str);	
			$str = str_replace('&#160;', '', $str);
			$str = str_replace('&#xA0;', '', $str);
			$str = preg_replace("/[^0-9,.]/", "", $str);
			return $str;
		}


		function return_code_pays($nom_pays){
			$list_pays =  array(
				"afghanistan" => 'AF',
				"aland islands" => 'AX',
				"albania" => 'AL',
				"algeria" => 'DZ',
				"american samoa" => 'AS',
				"andorra" => 'AD',
				"angola" => 'AO',
				"anguilla" => 'AI',
				"antarctica" => 'AQ',
				"antigua and barbuda" => 'AG',
				"argentina" => 'AR',
				"armenia" => 'AM',
				"aruba" => 'AW',
				"australia" => 'AU',
				"austria" => 'AT',
				"azerbaijan" => 'AZ',
				"bahamas" => 'BS',
				"bahrain" => 'BH',
				"bangladesh" => 'BD',
				"barbados" => 'BB',
				"belarus" => 'BY',
				"belgium" => 'BE',
				"belize" => 'BZ',
				"benin" => 'BJ',
				"bermuda" => 'BM',
				"bhutan" => 'BT',
				"bolivia, plurinational state of" => 'BO',
				"bonaire, sint eustatius and saba" => 'BQ',
				"bosnia and herzegovina" => 'BA',
				"botswana" => 'BW',
				"bouvet island" => 'BV',
				"brazil" => 'BR',
				"british indian ocean territory" => 'IO',
				"brunei darussalam" => 'BN',
				"bulgaria" => 'BG',
				"burkina faso" => 'BF',
				"burundi" => 'BI',
				"cambodia" => 'KH',
				"cameroon" => 'CM',
				"canada" => 'CA',
				"cape verde" => 'CV',
				"cayman islands" => 'KY',
				"central african republic" => 'CF',
				"chad" => 'TD',
				"chile" => 'CL',
				"china" => 'CN',
				"christmas island" => 'CX',
				"cocos (keeling) islands" => 'CC',
				"colombia" => 'CO',
				"comoros" => 'KM',
				"congo" => 'CG',
				"congo, the democratic republic of the" => 'CD',
				"cook islands" => 'CK',
				"costa rica" => 'CR',
				"côte d'ivoire" => 'CI',
				"croatia" => 'HR',
				"cuba" => 'CU',
				"curaçao" => 'CW',
				"cyprus" => 'CY',
				"czech republic" => 'CZ',
				"denmark" => 'DK',
				"djibouti" => 'DJ',
				"dominica" => 'DM',
				"dominican republic" => 'DO',
				"ecuador" => 'EC',
				"egypt" => 'EG',
				"el salvador" => 'SV',
				"equatorial guinea" => 'GQ',
				"eritrea" => 'ER',
				"estonia" => 'EE',
				"ethiopia" => 'ET',
				"falkland islands (malvinas)" => 'FK',
				"faroe islands" => 'FO',
				"fiji" => 'FJ',
				"finland" => 'FI',
				"france" => 'FR',
				"french guiana" => 'GF',
				"french polynesia" => 'PF',
				"french southern territories" => 'TF',
				"gabon" => 'GA',
				"gambia" => 'GM',
				"georgia" => 'GE',
				"germany" => 'DE',
				"ghana" => 'GH',
				"gibraltar" => 'GI',
				"greece" => 'GR',
				"greenland" => 'GL',
				"grenada" => 'GD',
				"guadeloupe" => 'GP',
				"guam" => 'GU',
				"guatemala" => 'GT',
				"guernsey" => 'GG',
				"guinea" => 'GN',
				"guinea-bissau" => 'GW',
				"guyana" => 'GY',
				"haiti" => 'HT',
				"heard island and mcdonald islands" => 'HM',
				"holy see (vatican city state)" => 'VA',
				"honduras" => 'HN',
				"hong kong" => 'HK',
				"hungary" => 'HU',
				"iceland" => 'IS',
				"india" => 'IN',
				"indonesia" => 'ID',
				"iran, islamic republic of" => 'IR',
				"iraq" => 'IQ',
				"ireland" => 'IE',
				"isle of man" => 'IM',
				"israel" => 'IL',
				"italy" => 'IT',
				"jamaica" => 'JM',
				"japan" => 'JP',
				"jersey" => 'JE',
				"jordan" => 'JO',
				"kazakhstan" => 'KZ',
				"kenya" => 'KE',
				"kiribati" => 'KI',
				"korea, democratic people's republic of" => 'KP',
				"korea, republic of" => 'KR',
				"kuwait" => 'KW',
				"kyrgyzstan" => 'KG',
				"lao people's democratic republic" => 'LA',
				"latvia" => 'LV',
				"lebanon" => 'LB',
				"lesotho" => 'LS',
				"liberia" => 'LR',
				"libya" => 'LY',
				"liechtenstein" => 'LI',
				"lithuania" => 'LT',
				"luxembourg" => 'LU',
				"macao" => 'MO',
				"macedonia, the former yugoslav republic of" => 'MK',
				"madagascar" => 'MG',
				"malawi" => 'MW',
				"malaysia" => 'MY',
				"maldives" => 'MV',
				"mali" => 'ML',
				"malta" => 'MT',
				"marshall islands" => 'MH',
				"martinique" => 'MQ',
				"mauritania" => 'MR',
				"mauritius" => 'MU',
				"mayotte" => 'YT',
				"mexico" => 'MX',
				"micronesia, federated states of" => 'FM',
				"moldova, republic of" => 'MD',
				"monaco" => 'MC',
				"mongolia" => 'MN',
				"montenegro" => 'ME',
				"montserrat" => 'MS',
				"morocco" => 'MA',
				"mozambique" => 'MZ',
				"myanmar" => 'MM',
				"namibia" => 'NA',
				"nauru" => 'NR',
				"nepal" => 'NP',
				"netherlands" => 'NL',
				"new caledonia" => 'NC',
				"new zealand" => 'NZ',
				"nicaragua" => 'NI',
				"niger" => 'NE',
				"nigeria" => 'NG',
				"niue" => 'NU',
				"norfolk island" => 'NF',
				"northern mariana islands" => 'MP',
				"norway" => 'NO',
				"oman" => 'OM',
				"pakistan" => 'PK',
				"palau" => 'PW',
				"palestinian territory, occupied" => 'PS',
				"panama" => 'PA',
				"papua new guinea" => 'PG',
				"paraguay" => 'PY',
				"peru" => 'PE',
				"philippines" => 'PH',
				"pitcairn" => 'PN',
				"poland" => 'PL',
				"portugal" => 'PT',
				"puerto rico" => 'PR',
				"qatar" => 'QA',
				"réunion" => 'RE',
				"romania" => 'RO',
				"russian federation" => 'RU',
				"rwanda" => 'RW',
				"saint barthélemy" => 'BL',
				"saint helena, ascension and tristan da cunha" => 'SH',
				"saint kitts and nevis" => 'KN',
				"saint lucia" => 'LC',
				"saint martin (french part)" => 'MF',
				"saint pierre and miquelon" => 'PM',
				"saint vincent and the grenadines" => 'VC',
				"samoa" => 'WS',
				"san marino" => 'SM',
				"sao tome and principe" => 'ST',
				"saudi arabia" => 'SA',
				"senegal" => 'SN',
				"serbia" => 'RS',
				"seychelles" => 'SC',
				"sierra leone" => 'SL',
				"singapore" => 'SG',
				"sint maarten (dutch part)" => 'SX',
				"slovakia" => 'SK',
				"slovenia" => 'SI',
				"solomon islands" => 'SB',
				"somalia" => 'SO',
				"south africa" => 'ZA',
				"south georgia and the south sandwich islands" => 'GS',
				"south sudan" => 'SS',
				"spain" => 'ES',
				"sri lanka" => 'LK',
				"sudan" => 'SD',
				"suriname" => 'SR',
				"svalbard and jan mayen" => 'SJ',
				"swaziland" => 'SZ',
				"sweden" => 'SE',
				"switzerland" => 'CH',
				"syrian arab republic" => 'SY',
				"taiwan, province of china" => 'TW',
				"tajikistan" => 'TJ',
				"tanzania, united republic of" => 'TZ',
				"thailand" => 'TH',
				"timor-leste" => 'TL',
				"togo" => 'TG',
				"tokelau" => 'TK',
				"tonga" => 'TO',
				"trinidad and tobago" => 'TT',
				"tunisia" => 'TN',
				"turkey" => 'TR',
				"turkmenistan" => 'TM',
				"turks and caicos islands" => 'TC',
				"tuvalu" => 'TV',
				"uganda" => 'UG',
				"ukraine" => 'UA',
				"united arab emirates" => 'AE',
				"united kingdom" => 'GB',
				"united states" => 'US',
				"united states minor outlying islands" => 'UM',
				"uruguay" => 'UY',
				"uzbekistan" => 'UZ',
				"vanuatu" => 'VU',
				"venezuela, bolivarian republic of" => 'VE',
				"viet nam" => 'VN',
				"virgin islands, british" => 'VG',
				"virgin islands, u.s." => 'VI',
				"wallis and futuna" => 'WF',
				"western sahara" => 'EH',
				"yemen" => 'YE',
				"zambia" => 'ZM',
				"zimbabwe" => 'ZW',
			);
			if(array_key_exists(strtolower($nom_pays),$list_pays)) return $list_pays[strtolower($nom_pays)];
			else return false;
		}		


	} // FIN DE LA CLASS
endif;

$wami_module_gedeon = new wami_module_gedeon();                  