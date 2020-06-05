<?php get_header(); ?>

<?php incremente_le_compteur_de_visite_de_ce_bien($post->ID, $post->post_author);  ?>

<?php 
$bien_superficie_terrain 	= get_field('bien_superficie_terrain') ? get_field('bien_superficie_terrain') : false; /* nb */
$bien_nb_piece 				= get_field('bien_nb_piece') ? get_field('bien_nb_piece') : false; /* nb */
$bien_nb_piece_de_bain 		= get_field('bien_nb_piece_de_bain') ? get_field('bien_nb_piece_de_bain') : false;  /* nb */
$bien_acces_handique 		= get_field('bien_acces_handique') ? get_field('bien_acces_handique') : false; /* vrai faux */
$bien_garage__parking 		= get_field('bien_garage__parking') ? get_field('bien_garage__parking') : false; /* nb */ 
$bien_annee_de_construction = get_field('bien_annee_de_construction') ? get_field('bien_annee_de_construction') : false; /* nb */ 
$bien_chauffage 			= get_field('bien_chauffage') ? get_field('bien_chauffage') : false; /* texte*/ 
$type_mandat 				= get_field('bien_mandat') ? get_field('bien_mandat') : false; /* array*/ 
	    	
//$infos_complementaires 		= get_field('infos_complementaires') ? get_field('infos_complementaires') : false;  /* repeater */ 
?>	    	  

	<div class="single-annonce">

		<div id="logo_print"><img src="<?php echo get_template_directory_uri(); ?>/lib/img/logo-middle-office.png" /></div>
	
		<section id="list-info">
	        <div class="w_grid limited-content">
	            <div class="grid-col col_size-12">
	                <a href="<?php echo wami_get_page_link('2'); ?>" class="button btn-secondary ico-left arrow-left">Retour à la liste</a>
	                <a href="" class="button btn-secondary imprimerlannonce"><i class="fa fa-print fa-2x" aria-hidden="true"></i></a>
	            </div>
	        </div>
	    </section>

		<?php while(have_posts()):
			the_post(); ?>

			<section id="annonce-slider-photos">
		        <?php 
		        $first_img = false;
		        if(have_rows("bien_image_slider")): ?>
		        	<ul class="owl-carousel">
						<?php while(have_rows('bien_image_slider')): the_row(); ?>
				             <li><?php $image = get_sub_field('image'); 
				             $img_resize = array_key_exists('paysage_slider', $image['sizes']) ? $image['sizes']['paysage_slider'] :$image["sizes"]["paysage_xbig"];
				             	if(get_row_index()==1) $first_img = get_sub_field('image'); ?>
				          		<a href="<?php echo $image['url']; ?>" class="js-lightbox">
				                	<img src="<?php echo $img_resize; ?>">
				                </a>
				            </li>
			        	<?php endwhile; ?>
		        	</ul>
			    <?php endif; ?>	        
		        <div id="owl-custom-nav"></div>
				<?php if($first_img && is_array($first_img)) echo '<div class="image_print"><img src="'.$first_img['sizes']['paysage_slider'].'"></div>'; ?>	 	

		       <!-- <?php if(get_field("iframe_de_la_visite_360")) : ?>
		        	<a href='#demarrer_visite_virtuelle' class='button btn-stoke open_popin' data-openpopin='demarrer_visite_virtuelle'>Démarrer la visite virtuelle</a>
			        <?php get_template_part('page_part/popin', 'visite-virtuelle'); ?>
		        <?php elseif(get_field("lien_de_la_visite_360")) : ?>
		            <a href="<?php echo get_field("lien_de_la_visite_360"); ?>" class='button btn-stoke'><span class="cover-label label-360">Démarrer la visite virtuelle</span></a>
		        <?php endif; ?> -->
		        	    
		    </section>

		    <section id="common-infos">
		        <div class="w_grid limited-content">

		            <div class="grid-col col_size-6 mobile_size-12">	                
		                <div>
		                	<div class="entete_single_bien">
		                    	<h1><?php the_title(); ?><span id="annonce-price"><?php echo get_field('bien_prix_et_honoraires') ? number_format(get_field('bien_prix_et_honoraires'), 0, '', ' ').' €' : ""; ?></span></h1>
		                    	<div id="annonce-chargede"><?php echo get_field('bien_honoraires_charge_de')=="charge_vendeur" ? "honoraires à la charge du vendeur" : "honoraires à la charge de l'acquereur"; ?></div>
		                    </div>    

		                    <h2><?php echo wami_get_villedubien($post->ID); ?> <span><?php echo wami_get_cpdubien($post->ID); ?></span> - <span><?php echo wami_get_regiondubien($post->ID); ?></span></h2>	                    
		                    <ul class="info-primary">
		                        <?php echo get_field('bien_surface_habitable') ? '<li>'.get_field('bien_surface_habitable').' m²</li>' : ""; ?>
		                        <?php echo $bien_superficie_terrain ? '<li>'.$bien_superficie_terrain.'  m2 de terrain</li>' : ""; ?>
								<?php if($bien_nb_piece) {
									echo '<li>'.$bien_nb_piece.'  pièce'; 
									if($bien_nb_piece>1) echo 's'; 
									echo '</li>'; 
								} ?>
		                        <?php if(get_field('bien_nb_chambre')){ 
		                        	echo '<li>'.get_field('bien_nb_chambre').' chambre'; 
		                        	if(get_field('bien_nb_chambre')>1) echo 's'; 
		                        	echo '</li>'; 
		                        }; ?>
		                        <?php if(get_field('bien_nb_piece_eau')){ 
		                        	echo '<li>'.get_field('bien_nb_piece_eau').' salle'; 
		                        	if(get_field('bien_nb_piece_eau')>1) echo 's'; 
		                        	echo ' d\'eau</li>'; 
		                        }; ?>
		                        <?php if($bien_nb_piece_de_bain) {
		                        	echo '<li>'.$bien_nb_piece_de_bain.' salle'; 
		                        	if($bien_nb_piece_de_bain>1) echo 's'; 
		                        	echo ' de bain</li>'; 
		                        }; ?>	
		                        <?php if($bien_garage__parking) {
		                        	echo '<li>'.$bien_garage__parking.' parking'; 
		                        	if($bien_garage__parking>1) echo 's'; 
		                        	echo '/garage'; 
		                        	if($bien_garage__parking>1) echo 's'; 
		                        	echo '</li>'; 
		                        }; ?>	
		                        <?php echo $bien_acces_handique ? '<li>Acces handicapé</li>' : ""; ?>
								<?php echo $bien_chauffage ? '<li>Chauffage : '.$bien_chauffage.'</li>' : ""; ?>
								<?php echo $bien_annee_de_construction ? '<li>Année de construction : '.$bien_annee_de_construction.'</li>' : ""; ?>


		                    </ul>	                    
		                    <div class="annonce-content">
		                        <?php the_content(); ?>

		                        <?php /*if($infos_complementaires) : ?>
								    <ul class="annonce-complementary">        
								        <?php while(have_rows('infos_complementaires')): the_row();  ?>            
								        	<li><?php the_sub_field("informations"); ?></li>
										<?php endwhile; ?>
									</ul>
								<?php endif;*/ ?>
		                    </div>	                    
		                </div>	                
		            </div>
		            
		            <div class="grid-col col_size-6 mobile_size-12">	                
		                <div>
		                    <div class="info-secondary">   
							    <div id="diagnostics_for_print">
							        <div class="w_grid limited-content">
							            <div class="grid-col col_size-12">
							                <div class="info-vente">
							                	 <h2>Infos de vente : </h2>
							                    <?php $type_bien = get_field('bien_type');		                    	 
												// Loi Carrez
												echo ($type_bien == 'appartement' && get_field('bien_superficie_carrez')) ? '<p>Loi Carrez <span>'.number_format(get_field('bien_superficie_carrez'), 0, '', ' ').' m<sup>2</sup></span></p>' : "";
												// surface habitable
												echo get_field('bien_surface_habitable') ? '<p>Surface habitable <span>'.number_format(get_field('bien_surface_habitable'), 0, '', ' ').' m<sup>2</sup></span></p>' : ""; 
												// surface terrain
												echo ($type_bien == 'maison' && get_field('bien_surface_terrain')) ? '<p>Surface terrain <span>'.number_format(get_field('bien_surface_terrain'), 0, '', ' ').' m<sup>2</sup></span></p>' : ""; 

												// Nombre de lots de copropriété
												echo ($type_bien == 'appartement' && get_field('bien_copro_nb_lot_copropriete')) ? '<p>Nombre de lots de copropriété <span>'.get_field('bien_copro_nb_lot_copropriete').'</span></p>' : ""; 
												// Charges courantes
												echo get_field('bien_copro_charges_courantes_an') ? '<p>Charges courantes annuelles <span>'.number_format(get_field('bien_copro_charges_courantes_an'), 0, '', ' ').' €</span></p>' : ""; 								
												// Taxe foncière 
												echo get_field('bien_taxe_fonciere') ? '<p>Taxe foncière <span>'.number_format(get_field('bien_taxe_fonciere'), 0, '', ' ').' €</span></p>' : "";								
												// prix honoraires inc TTC
												echo get_field('bien_prix_et_honoraires') ? '<p>Prix honoraires inc. <span>'.number_format(get_field('bien_prix_et_honoraires'), 0, '', ' ').' €</span></p>' : ""; 
												// prix hors-honoraires
												echo get_field('bien_prix') ? '<p>Prix hors honoraires <span>'.number_format(get_field('bien_prix'), 0, '', ' ').' €</span></p>' : ""; 
												// % des honoraires
												if( get_field('bien_prix_et_honoraires') && (get_field('bien_honoraires_montant') || get_field('bien_prix')) ) : 
													$prix_sans_honoraire = get_field("bien_prix");
													$prix_avec_honoraire = get_field('bien_prix_et_honoraires');
													$montant_honoraire   = get_field('bien_honoraires_montant');

												    $prix_honoraire = get_field('bien_honoraires_montant') ? get_field('bien_honoraires_montant') : get_field('bien_prix_et_honoraires') - get_field('bien_prix'); 

												    $pourcentage = $prix_honoraire / get_field('bien_prix') * 100;

													echo $pourcentage>=0 ? '<p>% des honoraires <span>'.round($pourcentage, 2).' %</span></p>' : "";
												endif; ?>
							                </div>
							            </div>
							            <div class="grid-col col_size-12">
							                <div class="ges">
							                    <h2>DPE <span>Consommation en kWh/an</span> : <?php echo get_field('bien_consommation_non_soumis_dpe') ? 'Bien non soumis aux DPE' : get_field('bien_consommation__co2'); ?></h2>
							                    <div class="shema"><img src="<?php echo get_template_directory_uri(); ?>/lib/img/GES.png" /></div>
							                </div>
							            </div>
							            <div class="grid-col col_size-12">
							                <div class="des">
							                    <h2>GES <span>Consommation en kgCO2/m²/an</span> : <?php echo get_field('bien_consommation_kw') ? get_field('bien_consommation_kw') : 'NR'; ?></h2>
							                    <div class="shema"><img src="<?php echo get_template_directory_uri(); ?>/lib/img/DES.png" /></div>
							                </div>
							            </div>
							        </div>
							    </div> 
		                    	<?php if(get_field("iframe_de_la_visite_360") || get_field("iframe_de_la_visite_360_code_html")) : ?>
						        	<a href='#demarrer_visite_virtuelle' class='button btn-primary open_popin visite_virtuelle' data-openpopin='demarrer_visite_virtuelle'>Démarrer la visite virtuelle</a>
							        <?php get_template_part('page_part/popin', 'visite-virtuelle'); ?>
						        <?php elseif(get_field("lien_de_la_visite_360")) : ?>
						            <a href="<?php echo get_field("lien_de_la_visite_360"); ?>" class='button btn-primary visite_virtuelle'><span class="cover-label label-360">Démarrer la visite virtuelle</span></a>
						        <?php endif; ?>                   
		                        <div class="embassador">
		                        	 <?php 
		                        	 $author_id = get_the_author_meta('ID'); 
		                        	 $avatar    = get_field("ambassadeur_photo", 'user_'.$author_id); 
		                        	 ?> 
		                            <header>
		                                <div class="embassador-cover">
		                                	<?php if($avatar && is_array($avatar) )
		                                		echo '<img src="'.$avatar['sizes']['carre_small'].'" alt="'.$avatar['alt'].'" title="'.$avatar['title'].'" />';
		                                	else echo '<img alt="avatar" src="http://placehold.it/60x60&text=avatar">'; ?>
		                                </div>
		                                <h3><?php echo get_the_author(); ?></h3>
		                                <h4>Consultant(e) en charge</h4>
		                            </header>
		                            <article>
		                            	<a href='#demande_rappel' class='open_popin' data-openpopin='demande_rappel'><?php echo get_field("ambassadeur_mail", "user_".$author_id) ? get_field("ambassadeur_mail", "user_".$author_id) : ""; ?></a>
		                                <p><?php echo get_field("ambassadeur_telephone", "user_".$author_id) ? return_tel_french_format(get_field("ambassadeur_telephone", "user_".$author_id)) : ""; ?></p>
		                                <a href='#demande_rappel' class='open_popin button btn-accent' data-openpopin='demande_rappel'><?php _e('Contactez-moi', 'wami'); ?></a>
		                            </article>
		                        </div>	                        
		                        <div id="annonce-environnement">
		                            <ul class="environnement-note">
		                                <?php //$note = get_field('bien_environnement_note') ? get_field('bien_environnement_note') : 0; 
		                                $note = wami_cacul_environnement_du_bien($post->ID);  //debug($note);
						            	for( $i=1; $i<6; $i++) {
						            		if($note>=1) echo '<li class="enviro-on"></li>';
						            		else if($note>0) echo '<li class="enviro-float"></li>';
						            		else echo '<li class="enviro-off"></li>';
						            		$note--;
						            	} ?>
		                            </ul>
		                            <h3>Environnement</h3>
		                            <p class="details"><?php echo get_field("bien_environnement_commentaire") ? get_field("bien_environnement_commentaire") : ""; ?></p>
		                        </div>                        
		                        <div id="annonce-charme">
		                            <ul class="charme-note">
		                                <?php //$note = get_field('bien_charme_note') ? get_field('bien_charme_note') : 0;
		                                $note = wami_cacul_charme_du_bien($post->ID); //debug($note);
						            	for( $i=1; $i<6; $i++) {
						            		if($note>=1) echo '<li class="charme-on"></li>';
						            		else if($note>0) echo '<li class="charme-float"></li>';
						            		else echo '<li class="charme-off"></li>';
						            		$note--;
						            	} ?>
		                            </ul>
		                            <h3>Charme</h3>
		                            <p class="details"><?php echo get_field("bien_charme_commentaire") ? get_field("bien_charme_commentaire") : ""; ?></p>
		                        </div>
		                        <?php if(get_field("bien_environnement_acces_tgv")) echo '<h3>Accessible en TGV</h3>'; ?>           
		                    </div>
		                </div>	                
		            </div>	

		        </div>
		    </section>  	    
		    
		    <section id="diagnostics">
		        <div class="w_grid limited-content">
		            <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
		                <div id="ges">
		                    <h4>DPE <span>Consommation en kWh/an</span></h4>
		                    <?php if( get_field('bien_consommation_non_soumis_dpe') ) :
		                    	echo '<p>Bien non soumis aux DPE.</p>'; 
		                    ; else : ?>
			                    <div class="diagnostic-shem">
			                        <div class="shema"></div>
			                        <?php $value = get_field('bien_consommation__co2') ? intval(get_field("bien_consommation__co2")) : "0"; ?>
			                        <span class="value" data-palier="<?php echo get_palier_conso_kwh($value); ?>" data-value="<?php echo $value; ?>">0</span>
				                    </div>
			                <?php endif; ?>
		                </div>
		            </div>
		            <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
		                <div id="des">
		                    <h4>GES <span>Consommation en kgCO2/m²/an</span></h4>
		                    <div class="diagnostic-shem">
		                        <div class="shema"></div>
			                    	<?php $value = get_field('bien_consommation_kw') ? intval(get_field("bien_consommation_kw")) : "0"; ?>	
			                        <span class="value" data-palier="<?php echo get_palier_conso_co2($value); ?>" data-value="<?php echo $value; ?>">0</span>
		                    </div>
		                </div>
		            </div>
		            <div class="grid-col col_size-4 tablet_size-12">
		                <div class="info-vente">
		                    <ul class="vente-list">
		                    	<?php $type_bien = get_field('bien_type');		                    	 
	                    		// Loi Carrez
		                    	echo ($type_bien == 'appartement' && get_field('bien_superficie_carrez')) ? '<li>Loi Carrez <span>'.number_format(get_field('bien_superficie_carrez'), 0, '', ' ').' m<sup>2</sup></span></li>' : "";
								// surface habitable
								echo get_field('bien_surface_habitable') ? '<li>Surface habitable <span>'.number_format(get_field('bien_surface_habitable'), 0, '', ' ').' m<sup>2</sup></span></li>' : ""; 
								// surface terrain
								echo ($type_bien == 'maison' && get_field('bien_surface_terrain')) ? '<li>Surface terrain <span>'.number_format(get_field('bien_surface_terrain'), 0, '', ' ').' m<sup>2</sup></span></li>' : ""; 

								// Nombre de lots de copropriété
								echo ($type_bien == 'appartement' && get_field('bien_copro_nb_lot_copropriete')) ? '<li>Nombre de lots de copropriété <span>'.get_field('bien_copro_nb_lot_copropriete').'</span></li>' : ""; 
			                    // Charges courantes
								echo get_field('bien_copro_charges_courantes_an') ? '<li>Charges courantes annuelles <span>'.number_format(get_field('bien_copro_charges_courantes_an'), 0, '', ' ').' €</span></li>' : ""; // bien_copro_charges_courantes_an
								// Procédure en cours
								echo ($type_bien == 'appartement' && get_field('bien_copro_litiges')) ? '<li>Procédure en cours</li>' : ""; 
								echo ($type_bien == 'appartement' && get_field('bien_copro_litiges')) ? '<li>Procédure en cours</li>' : ""; 
								// Taxe foncière 
								echo get_field('bien_taxe_fonciere') ? '<li>Taxe foncière <span>'.number_format(get_field('bien_taxe_fonciere'), 0, '', ' ').' €</span></li>' : "";
								
								// prix honoraires inc TTC
								echo get_field('bien_prix_et_honoraires') ? '<li>Prix honoraires inc. <span>'.number_format(get_field('bien_prix_et_honoraires'), 0, '', ' ').' €</span></li>' : ""; 
								// prix hors-honoraires
								echo get_field('bien_prix') ? '<li>Prix hors honoraires <span>'.number_format(get_field('bien_prix'), 0, '', ' ').' €</span></li>' : ""; 
								// % des honoraires
						        if( get_field('bien_prix_et_honoraires') && (get_field('bien_honoraires_montant') || get_field('bien_prix')) ) : 
							        $prix_sans_honoraire = get_field("bien_prix");
									$prix_avec_honoraire = get_field('bien_prix_et_honoraires');
									$montant_honoraire   = get_field('bien_honoraires_montant');

									$prix_honoraire = get_field('bien_honoraires_montant') ? get_field('bien_honoraires_montant') : get_field('bien_prix_et_honoraires') - get_field('bien_prix');

							        $pourcentage = $prix_honoraire / get_field('bien_prix') * 100;

									echo $pourcentage>=0 ? '<li>% des honoraires <span>'.round($pourcentage, 2).' %</span></li>' : "";
								endif;
								// honoraire à la charge de
								//echo get_field('bien_honoraires_charge_de')=="charge_vendeur" ? '<li>honoraires à la charge du vendeur</li>' : "<li>honoraires à la charge de l'acquereur</li>"; 
								?>
		                    </ul>
		                </div>
		            </div>
		        </div>
		    </section>


		    <section id="localisation">
		        <div class="w_grid no-gutter">
		            <?php 		            
		            	$localite = wami_get_last_localite_bien($post->ID); //debug($localite); 
		            	$loc_ids = array();
		            	$adresse = false;
		            	$type = 'adresse_bien'; 
		            	$mandat_type = is_array($type_mandat) ? $type_mandat['value'] : '';
		            	
		            	$adresse = get_field('bien_adresse');
		            	/*if($mandat_type == "mandat_simple")
		            		$adresse = ($localite->lieu!="" && get_field('adresse_map', 'lieu_'.$localite->lieu->term_id)) ? get_field('adresse_map', 'lieu_'.$localite->lieu->term_id) : false;
		            	else */if( $localite->lieu!="" || get_field('bien_adresse') )  
			            	$adresse = get_field('bien_adresse') ? get_field('bien_adresse') : (($localite->lieu!="" && get_field('adresse_map', 'lieu_'.$localite->lieu->term_id)) ? get_field('adresse_map', 'lieu_'.$localite->lieu->term_id) : false);			            
		            ?>
		            <?php if($adresse && $mandat_type == "mandat_exclusif") : ?>
		            	<div class="grid-col col_size-6 mobile_size-12">		            	
		                	<div id="localisation-map" data-markerid="marker_bien" data-adresse="<?php echo $adresse['address']; ?>" data-lat="<?php echo $adresse['lat']; ?>" data-lng="<?php echo $adresse['lng']; ?>" data-zoom="<?php echo $type; ?>"  data-typem="<?php echo $mandat_type; ?>"></div>
			            </div>
			            <div class="grid-col col_size-6 mobile_size-12">
			                <div id="localisation-infos">
			                	<?php if($localite->lieu!="") : ?>
				                    <h3><?php echo $localite->lieu->name; ?>		                     
					                    <span><?php echo $localite->region->name; ?><?php if($localite->type == 'district') echo '&nbsp;<span class="ville">'.$localite->ville->name.'</span>'; ?></span> 
				                    </h3>	                    
				                    <p class="localisation-description"><?php echo  $localite->lieu->description; ?></p>
				                    <ul class="localisation-infos-secondary">
				                        <li>
				                            <p><b><?php echo get_field('nombre_dhabitant', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id) ? get_field('nombre_dhabitant', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id) : ""; ?></b></p>
				                        </li>
				                        <li>
				                            <p><?php echo get_field('nombre_decole', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id) ? get_field('nombre_decole', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id) : ""; ?></p>
				                        </li>
				                        <li>
				                            <p><?php echo get_field('apercu_des_transports', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id) ? get_field('apercu_des_transports', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id) : ""; ?></p>
				                        </li>
				                    </ul>
				                    <?php if(have_rows('lieu_de_reference', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id)) : $i=0;?>
					                    <ul id="recommendations" class="localisation-infos-secondary">
					                    	<?php while(have_rows('lieu_de_reference', $localite->lieu->taxonomy.'_'.$localite->lieu->term_id)):
					                    	the_row(); ?>
					                    	<li>
					                    		<?php $reco_adresse = get_sub_field('adresse_map'); ?>
		                    					<?php if(is_array($reco_adresse) && $reco_adresse['address']) echo '<a href="#" class="lien_adresse_map adresse_hotspot" data-markerid="marker_'.$i.'" data-adresse="'.$reco_adresse['address'].'" data-lat="'.$reco_adresse['lat'].'" data-lng="'.$reco_adresse['lng'].'" >'.get_sub_field('nom').' <br/><span>'.get_sub_field('adresse').'</span></a>'; 
		                    					else echo '<p class="adresse_hotspot">'.get_sub_field('nom').' <br/><span>'.get_sub_field('adresse').'</span></p>';
		                    					?>
					                    	</li>
					                    	<?php  $i++; if($i>=3) break; 
					                    	endwhile; ?>  
					                    </ul>
					                <?php endif; ?>
					            <?php endif; ?>
			                </div>
			            </div> 
			       	<?php endif; ?>
		            <div class="grid-col col_size-12">
		                <?php get_template_part('page_part/page-part', 'share'); ?>
		            </div>
		        </div>
		    </section>

		    <section id="similar-posts">
		        <div class="w_grid limited-content">
		            <?php 
		            $args = array(
		            	'post_type'		=> 'biens',
		            	'post_status'	=> 'publish',
		            	'post__not_in'  => array( $post->ID ),
		            	'tax_query' => array(
							array(
								'taxonomy' => 'lieu',
								'field'    => 'term_id',
								'terms'    => $loc_ids,
								'operator' => 'IN',
							),
						),
		            	'posts_per_page'=> 6
		            );
		            $query_biens = new WP_Query( $args );
		            if($query_biens->have_posts()) : ?>		            	
			            <div class="grid-col col_size-12">
			                <h3 class="title-button">Annonces similaires <a href="<?php echo get_term_link($localite->region); ?>" class="button btn-secondary btn-to-right">Toutes les annonces similaires</a></h3>
			            </div>
		            	<ul class="annonce-list tpl-thumbinette">
		                	<?php while($query_biens->have_posts()) :
	                    		$query_biens->the_post(); ?>
		                       	<li>
                    				<div class="grid-col col_size-2 tablet_size-4 mobile_size-6">
		                            	<?php echo get_template_part('page_part/loop', 'annonces_liees'); ?> 
		                            </div>                           
		                        </li>  
			                <?php endwhile; ?>
		           		</ul>
		           	<?php endif; ?>
		           	<?php wp_reset_postdata(); ?>
		        </div>
		    </section>		

		<?php endwhile; ?>

	</div>

	<?php 
	if ( comments_open() || get_comments_number() ) : ?>
		<div class="popin-layer close" id="demande_rappel">
			<div class="popin">
				<?php comments_template(); ?>				
			</div>
		</div>
	<?php endif; ?>
	
<?php get_footer(); ?>
