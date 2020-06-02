 <?php $a = get_queried_object(); ?>
 <?php get_header(); ?>
	
	<div class="single-ambassador">	

			<section id="ambassador-profil">        
		        <div class="bg-overlay"></div>		    
		        <div class="w_grid limited-content">		        
		            <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
		                <div class="ambassador-profil-content">
		                    <div id="ambassador-avatar" class="profil-container">
		                        <div class="ambassador-cover">
		                        	<?php $image = get_field('ambassadeur_photo', 'user_'.$a->ID); 
		                        	if($image) : 
							    		echo '<img src="'.$image['sizes']['carre_medium'].'" alt="'.$image['alt'].'" title="'.$image['title'].'">'; 	
							    	; else : echo '<img src="http://placehold.it/190x190">'; 
							    	endif; ?>		                            
		                        </div>
		                        <h1><?php echo $a->data->display_name; ?></h1>
		                        <p><?php echo (isset($a->roles) && is_array($a->roles)) ? $a->roles[0] : ""; ?></p>
		                        <p><?php $region_amb = get_field('ambassadeur_region', 'user_'.$a->ID);
                    			echo (isset($region_amb) && is_array($region_amb)) ? $region_amb['label']: ""; ?>
		                        </p>
		                    </div>
		                </div>
		            </div>
		            <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
		                <div class="ambassador-profil-content">
		                    <div id="ambassador-info-primary" class="profil-container">
		                        <p><b><?php echo $a->data->display_name; ?></b></p>
		                        <p><?php echo get_field('ambassadeur_mail', 'user_'.$a->ID) ? get_field('ambassadeur_mail', 'user_'.$a->ID) : ""; ?></p>
		                        <p><?php echo get_field('ambassadeur_telephone', 'user_'.$a->ID) ? return_tel_french_format(get_field('ambassadeur_telephone', 'user_'.$a->ID)) : ""; ?></p>
		                        <ul class="ambassador-rs">
					                <?php if(get_field('ambassadeur_lien_facebook', 'user_'.$a->ID)) 
					                	echo '<li><a href="'.get_field('ambassadeur_lien_facebook', 'user_'.$a->ID).'" class="rs-item rs-fb"><span class="fa fa-facebook"></span></a></li>'; ?>
					                <?php if(get_field('ambassadeur_lien_twitter', 'user_'.$a->ID))
					                	echo '<li><a href="'.get_field('ambassadeur_lien_twitter', 'user_'.$a->ID).'" class="rs-item rs-twitter"><span class="fa fa-twitter"></span></a></li>'; ?>	
					                <?php if(get_field('ambassadeur_lien_linkedin', 'user_'.$a->ID))
					                	echo '<li><a href="'.get_field('ambassadeur_lien_linkedin', 'user_'.$a->ID).'" class="rs-item rs-linkedin"><span class="fa fa-linkedin"></span></a></li>'; ?>  
					                <?php if(get_field('ambassadeur_lien_pinterest', 'user_'.$a->ID))
					                	echo '<li><a href="'.get_field('ambassadeur_lien_pinterest', 'user_'.$a->ID).'" class="rs-item rs-pinterest"><span class="fa fa-pinterest"></span></a></li>'; ?>
					                <?php if(get_field('ambassadeur_lien_instagram', 'user_'.$a->ID))
					                	echo '<li><a href="'.get_field('ambassadeur_lien_instagram', 'user_'.$a->ID).'" class="rs-item rs-instagram"><span class="fa fa-instagram"></span></a></li>'; ?>  
				                </ul>
		                    </div>
		                </div>
		            </div>
		            <?php if(get_field('ambassadeur_description', 'user_'.$a->ID)) : ?>
		            <div class="grid-col col_size-4 tablet_size-12">
		                <div class="ambassador-profil-content">
		                    <div id="ambassador-info-secondary" class="profil-container">
		                        <?php the_field('ambassadeur_description', 'user_'.$a->ID); ?>
		                    </div>
		                </div>
		            </div>	
		            <?php endif; ?>	            
		        </div>		    
		    </section>   		    
		    

		    <?php 
            $args = array(
            	'post_type'		=> 'biens',
            	'post_status'	=> 'publish',
            	'author__in' 	=> array( $a->ID ),
            	'meta_query'	=> array(           		
            		array(
            			'key' 		=> 'bien_disponible',
            			'value' 	=> 1,
            			'compare' 	=> '='
            		)
            	),
            	'posts_per_page'=> -1
            );
            $query_biens = new WP_Query( $args );
            if($query_biens->have_posts()) : ?>
			    <section id="annonces">
			        <div class="w_grid limited-content">		                
			            <div class="grid-col col_size-12">       
			                <h3>Les annonces de <?php echo get_the_author_meta( 'first_name', $a->ID ); ?> (<?php echo $query_biens->post_count; ?>)</h3>		                
			            </div>
			        </div>
			        <div class="w_grid limited-content">
			            <ul class="annonce-list tpl-tripple">			            	
	                    	<?php while($query_biens->have_posts()) :
	                    		$query_biens->the_post(); ?>
		                         <li class="grid-col col_size-4 tablet_size-6 mobile_size-12 bottom-gutter">
				                        <div class="annonce">
				                            <?php echo get_template_part('page_part/loop', 'annonces_medium'); ?>   
				                        </div>
				                    </li>
			                <?php endwhile; ?>
			            </ul>
			            <!--a href="#" class="button btn-secondary">Voir les annonces</a-->
			        </div>
			    </section>	
			<?php wp_reset_postdata(); ?>
       		<?php endif; ?>	

	</div>

<?php get_footer(); ?>