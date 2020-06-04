<?php
/*
Template Name: TPL Philosophie
*/
?>
<?php get_header(); ?>
	
	<div class="tpl-philosophie">

		<?php while(have_posts()):
			the_post(); ?>

                <div id="page-nav">        
                    <div class="w_grid limited-content">
                        <div class="grid-col col_size-12">
                            <h1><?php the_title(); ?></h1>
                            <ul id="nav">
                                <li><a href="#associees" class="ancredouce"><?php echo get_field("philo_associees_ancre") ? get_field("philo_associees_ancre") : ""; ?></a></li>
                                <li><a href="#competences" class="ancredouce"><?php echo get_field("philo_competence_ancre") ? get_field("philo_competence_ancre") : ""; ?></a></li>
                                <li><a href="#solidaire" class="ancredouce"><?php echo get_field("philo_asolidaire_ancre") ? get_field("philo_asolidaire_ancre") : ""; ?></a></li>
                                <!--li><a href="#" data-target="">Charte Delacour</a></li-->
                                <li><a href="#media" class="ancredouce"><?php echo get_field("philo_presse_ancre") ? get_field("philo_presse_ancre") : ""; ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        
				<section id="intro" class="content-fix-height reverseResponsive">    
			        <div class="bg-overlay"></div>			        
			        <div class="w_grid limited-content">
			            <div class="grid-col col_size-5 tablet_size-12">			            	
			                <div class="philo-details-block">
			                    <?php echo get_field('philo_intro_contenu') ? get_field('philo_intro_contenu') : ""; ?>
			                </div>
			            </div>
			            <div class="grid-col col_size-7 tablet_size-12">			            	
			                <?php if(get_field('philo_intro_video')) : ?>
			                    <div class="intro-video">
			                        <div class="video-container">
			                            <?php echo get_field('philo_intro_video'); ?>
			                        </div>
			                    </div>
				            <?php endif; ?>
			                <div class="philo-punhline-block">
			                    <div class="bottom-block">
			                    	<h2 class="philo-punchline"><?php echo get_field('philo_intro_titre') ? get_field('philo_intro_titre') : ""; ?></h2>
			                    </div>
			                </div>
			            </div>
			        </div>			        
			    </section>
        
            
                <section id="associees">
                    <div class="w_grid limited-content">
                        <div class="grid-col col_size-12">
                            <h3><?php echo get_field('philo_associées_titre') ? get_field('philo_associées_titre') : ""; ?></h3>
                        </div>

                        <?php $args = array(
						//'role'          => 'ambassadeur',
						'meta_query'	=> array(
		            		array(
		            			'key' 		=> 'associe_principal',
		            			'value' 	=> 1,
		            			'compare' 	=> '='
		            		),    
		            	),
		            	'number'      	=> '3',		
					 ); 
					$ambassadeurs = get_users( $args );
					if(is_array($ambassadeurs)) : ?>
						<div id="ambassadors">
                            <div class="ambassadors-list">
				            	<?php foreach($ambassadeurs as $a) : ?>
				            		<div class="grid-col col_size-4 mobile_size-12 bottom-gutter">
		                                <div class="ambassador">
		                                    <div class="ambassador-cover">
		                                        <a href="<?php echo get_author_posts_url($a->ID); ?>">
						                        	<?php $image = get_field('ambassadeur_photo', 'user_'.$a->ID); 
						                        	if($image) : 
											    		echo '<img src="'.$image['sizes']['carre_small'].'" alt="'.$image['alt'].'" title="'.$image['title'].'">'; 	
											    	; else : echo '<img src="http://placehold.it/145x145">'; 
											    	endif; ?>				                            
						                        </a>
		                                        <span class="ambassador-hover-bar"></span>
		                                    </div>
		                                    <article>
		                                        <h4 class="ambassador-title"><a href="<?php echo get_author_posts_url($a->ID); ?>"><?php echo $a->data->display_name; ?></a></h4>
		                                        <?php echo get_field('ambassadeur_front_description', 'user_'.$a->ID) ? '<h5>'.get_field('ambassadeur_front_description', 'user_'.$a->ID).'</h5>' : ""; ?>
		                                       <?php echo get_field('ambassadeur_description', 'user_'.$a->ID) ? wami_return_small(get_field('ambassadeur_description', 'user_'.$a->ID), 250) : ""; ?>
		                                    </article>
		                                </div>
		                            </div>
							    <?php endforeach; ?>
							</div>
						</div>	
		            <?php endif; ?>
		          </div>
                </section>

			    
			    <section id="competences" class="content-fix-height">			        
			        <div class="w_grid limited-content">
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-details-block">
			                    <?php echo get_field('philo_competence_contenu') ? get_field('philo_competence_contenu') : ""; ?>
			                </div>
			            </div>
			            <div class="grid-col col_size-5 margin-1 tablet_size-11">
			                <div class="philo-punhline-block">
			                    <div class="bottom-block">
			                    	<h2 class="philo-punchline">
			                       		<?php echo get_field('philo_competence_titre') ? get_field('philo_competence_titre') : ""; ?>
			                       	</h2>	
			                        <div class="philo-half-block">
			                        	<?php echo get_field('philo_competence_mentions') ? get_field('philo_competence_mentions') : ""; ?>
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>			        
			    </section>	  
			    
			    
			    <section id="solidaire" class="content-fix-height reverseResponsive">			        
			        <div class="bg-overlay"></div>			        
			        <div class="w_grid limited-content">
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-details-block">
			                    <?php echo get_field('philo_asolidaire_contenu') ? get_field('philo_asolidaire_contenu') : ""; ?>
			                </div>
			            </div>
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-punhline-block">
			                    <div class="bottom-block">
			                        <h2 class="philo-punchline">
			                        	<?php echo get_field('philo_asolidaire_titre') ? get_field('philo_asolidaire_titre') : ""; ?>
			                        </h2>
			                        <div class="philo-half-block">
			                            <?php echo get_field('philo_asolidaire_mentions') ? get_field('philo_asolidaire_mentions') : ""; ?>
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </section>

			    
			    <section id="media">			    
			        <div class="w_grid limited-content">			        
			           
			            <div class="grid-col col_size-5 tablet_size-12">
			                <?php /* ?><div id="articles">
			                    <h3><?php echo get_field('philo_presse_titre_rubrique_articles_col_de_gauche') ? get_field('philo_presse_titre_rubrique_articles_col_de_gauche') : ""; ?></h3>	
			                    <?php $args = array(
				                    'post_type'         => 'post',
				                    'post_status'       => 'publish',       
				                    'meta_query'    => array( 
				                        array(
				                            'key'       => 'date_rdv',
				                            'value'     => date('Ymd'),
				                            'type'      => 'DATE',
				                            'compare'   => '>='
				                        )
				                    ),                            
				                    'order'             => 'ASC',
				                    'orderby'           => 'meta_value_num',
				                    'meta_key'          => 'date_rdv',                         
				                    'posts_per_page'    => 4
				                );
				                $query_atelier = new WP_Query( $args );
				                if($query_atelier->have_posts()) : ?>
				                    <ul class="article-list">
				                    <?php while($query_atelier->have_posts()) :
				                    $query_atelier->the_post(); ?>
				                    	<li> <?php get_template_part("page_part/loop", "atelier_small"); ?> </li>
				                    <?php endwhile; ?>
				                    </ul>
				                <?php wp_reset_postdata(); ?>
				                <?php endif; ?> 
			                </div> <?php */ ?>
			                <div id="videos">
			                    <h3 class="title-button"><?php echo get_field('philo_presse_titre_rubrique_video_col_de_droite') ? get_field('philo_presse_titre_rubrique_video_col_de_droite') : ""; ?> <a href="<?php echo get_field('philo_presse_rubrique_video_lien_bouton_tout_voir') ? get_field('philo_presse_rubrique_video_lien_bouton_tout_voir') : ""; ?>" class="button btn-secondary btn-to-right"><?php echo get_field('philo_presse_rubrique_video_titre_bouton_tout_voir') ? get_field('philo_presse_rubrique_video_titre_bouton_tout_voir') : ""; ?></a></h3>	
			                    <div class="last-video">
			                        <div class="video-container">
			                            <!-- <img alt="VIDEO" src="http://placehold.it/555x330/00FF00&text=VIDEO"> -->
			                            <?php echo get_field('philo_presse_lien_de_la_video'); ?>
			                        </div>
			                        <p><?php echo get_field('philo_presse_lien_de_la_video_titre') ? get_field('philo_presse_lien_de_la_video_titre') : ""; ?></p>
			                    </div>
			                </div>
			            </div>

			            <div class="grid-col col_size-6 margin-1 tablet_size-12">
			                <div id="publications">			                    
			                    <h3><?php echo get_field('philo_presse_titre_rubrique_publication_col_de_droite') ? get_field('philo_presse_titre_rubrique_publication_col_de_droite') : ""; ?></h3>			                    
			                    <?php if(have_rows("philo_presse_rubrique_publication_content")) : ?>
				                    <ul id="publications">
				                    	<?php while(have_rows("philo_presse_rubrique_publication_content")) : 
				                    		the_row(); ?>
					                        <li>
					                        	<?php if( get_sub_field('lien') ) echo '<a href="'.get_sub_field('lien').'">'; ?>
					                        	<b><?php the_sub_field("titre"); ?></b> "<?php the_sub_field("contenu"); ?>"
					                        	<?php if( get_sub_field('lien') ) echo '</a>'; ?>
					                        </li>
					                    <?php endwhile; ?>
				                    </ul>
				                <?php endif; ?>
				                <?php if(get_field('philo_presse_rubrique_publication_pdf')) : 
				                	$pdf = get_field('philo_presse_rubrique_publication_pdf'); ?>
			                    	<a href="<?php echo $pdf['url']; ?>" class="button btn-secondary ico-left arrow-down"><?php echo get_field('philo_presse_rubrique_publication_titre_bouton') ? get_field('philo_presse_rubrique_publication_titre_bouton') : ""; ?></a>
			                    <?php endif; ?>
			                </div>			                
			                <div id="presse">			                    
			                    <h3><?php echo get_field('philo_presse_titre_rubrique_dossier_presse_col_de_droite') ? get_field('philo_presse_titre_rubrique_dossier_presse_col_de_droite') : ""; ?></h3>			                    
			                    <?php if(get_field('philo_presse_rubrique_dossier_presse_titre_bouton_lien_mailto')) :  ?>
			                    	 <a href=mailto:"<?php echo get_field('philo_presse_rubrique_dossier_presse_titre_bouton_lien_mailto'); ?>" class="button btn-secondary"><?php echo get_field('philo_presse_rubrique_dossier_presse_titre_bouton') ? get_field('philo_presse_rubrique_dossier_presse_titre_bouton') : ""; ?></a>
			                    <p><?php echo get_field('philo_presse_rubrique_dossier_presse_texte') ? get_field('philo_presse_rubrique_dossier_presse_texte') : ""; ?></p>
			                    <?php endif; ?>			                   			                    
			                </div>
			            </div>	

			        </div>			        
			    </section>

		<?php endwhile; ?>

	</div>

<?php get_footer();