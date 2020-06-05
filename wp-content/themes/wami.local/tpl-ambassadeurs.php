<?php
/*
Template Name: TPL Ambassadeurs
*/
?>
<?php get_header(); ?>

	<div class="tpl-annonces-list">
		<section id="ambassadors">

			<div class="w_grid limited-content">                
	            <div class="grid-col col_size-12">	 
	            	<h1><?php the_title(); ?></h1>               
					<div id="annonces-filters">		                
		                <fieldset class="single-field grid-col col_size-4 tablet_size-12">
		                	<?php //get_search_form(); ?>
		                </fieldset>
		            </div>		            
	            </div>
	        </div>

	        <div class="w_grid limited-content ambassadors-list">
	        	<div class="annonce-list tpl-tripple"> 
		        	<?php $args = array(
						//'role'         => 'ambassadeur',
						'role__in'     => array('ambassadeur', 'ambassadeur_responsable_de_region', 'administrator'),
						// 'role__not_in' => array(),
						// 'meta_key'     => 'ambassadeur_de_la_cour_au_jardin',
						// 'meta_value'   => 1,
						// 'meta_compare' => '=',
						'meta_query'   => array(
							'relation'  => 'AND',    
                            array(
                                'key'       => 'ambassadeur_de_la_cour_au_jardin',
                                'value'     => 1,
                                'compare'   => '='
                            ),                            
                            array(
                                'key'       => 'ambassadeur_visible_en_front',
                                'value'     => 1,
                                'compare'   => '='
                            ) 
						),
						'orderby'      => 'meta_value',
						'meta_key'     => 'last_name',
						// 'date_query'   => array(),        
						// 'include'      => array(),
						// 'exclude'      => array(),
						// 'orderby'      => 'login',
						// 'order'        => 'ASC',
						// 'offset'       => '',
						// 'search'       => '',
						// 'number'       => '',
						// 'count_total'  => false,
						// 'fields'       => 'all',
						// 'who'          => ''
					 ); 
					$ambassadeurs = get_users( $args );
					if(is_array($ambassadeurs)) : ?>
		            	<?php foreach($ambassadeurs as $a) : ?>
		            		<div class="grid-col col_size-3 tablet_size-4 mobile_size-12 bottom-gutter"> 
				            	<div class="ambassador">
				                    <div class="ambassador-cover">
				                        <a href="<?php echo get_author_posts_url($a->ID); ?>" href="single-ambassador.php">
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
				                        <h5>
				                        	<?php echo get_field('ambassadeur_front_description', 'user_'.$a->ID) ? get_field('ambassadeur_front_description', 'user_'.$a->ID).'<br>' : ""; ?>
				                        	<?php $region_amb = get_field('ambassadeur_region', 'user_'.$a->ID);
                    						echo (isset($region_amb) && is_array($region_amb)) ? $region_amb['label']: ""; ?>
				                        	<?php //echo get_field('ambassadeur_region', 'user_'.$a->ID) ? get_field('ambassadeur_region', 'user_'.$a->ID) : ""; ?>				                        	
				                        </h5>				                        
				                        <?php echo get_field('ambassadeur_mail', 'user_'.$a->ID) ? '<a href="mailto:'.get_field('ambassadeur_mail', 'user_'.$a->ID).'">'.get_field('ambassadeur_mail', 'user_'.$a->ID).'</a>' : ""; ?>
				                        <p><?php echo get_field('ambassadeur_telephone', 'user_'.$a->ID) ? return_tel_french_format(get_field('ambassadeur_telephone', 'user_'.$a->ID)) : ""; ?></p>
				                    </article>
				                </div>
					        </div>
					    <?php endforeach; ?>	
		            <?php endif; ?>
		        </div>
            </div>

		</section>
	</div>

<?php get_footer(); ?>