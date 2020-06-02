<?php
/*
Template Name: Search Page
*/
?>
<?php get_header(); ?>

<?php 
$qsearch = get_search_query();
$my_term = wami_get_term_lieu($qsearch);
?>

<div class="tpl-annonces-list">
	<section id="annonces">				

		<div class="w_grid limited-content"> 
            <div class="grid-col col_size-12">	
	            <div id="annonces-filters">		                
	                <fieldset class="single-field grid-col col_size-4 tablet_size-12">
	                	<?php //get_search_form(); 
                    	get_template_part('searchform', 'lieu'); ?>
	                </fieldset>
	                <?php get_template_part('search', 'filtres'); ?>
	            </div>	            
            </div>
        </div>


		<?php if($my_term) :	
			$image = get_field('image', $my_term->taxonomy.'_'.$my_term->term_id); ?>			
	        <div class="w_grid limited-content">
				<ul class="annonce-list tpl-tripple">
                    <?php if($image) : ?>
	                    <div class="grid-col col_size-8 tablet_size-12 bottom-gutter">
	                        <div class="city-highlight">
	                            <div class="bg-overlay">
	                            	<img src="<?php echo $image['sizes']['paysage_big']; ?>">           
	                            </div>
	                            <div class="hack-centered-content">
	                                <h1><?php echo $my_term->name; ?></h1>
	                                <?php //echo '<p>'.$my_term->description.'</p>'; ?>
	                                <a href="<?php echo get_term_link($my_term); ?>" class="button btn-stoke">Lire plus</a>
	                            </div>
	                        </div>
	                    </div>
                    <?php endif;
		            $args = array(
				    	'post_type'		=> 'biens',
				    	'post_status'	=> 'publish',
				    	'meta_query'	=> array(
				    		array(
				    			'key' 		=> 'bien_disponible',
				    			'value' 	=> 1,
				    			'compare' 	=> '='
				    		)
				    	),
				    	'tax_query'	=> array(
				    		array(
				    			'taxonomy' => 'lieu',
								'field'    => 'slug',
								'terms'    => $my_term->slug,
				    		),
				    	),
				    	'posts_per_page'=> -1
					); 
		            $query_biens = new WP_Query( $args );
		            if($query_biens->have_posts()) : ?>
                    	<?php while($query_biens->have_posts()) :
                    		$query_biens->the_post(); ?>		                    		
	                        <li class="grid-col col_size-4 tablet_size-6 mobile_size-12 bottom-gutter annonce_bien_trouve">
		                        <div class="annonce">
		                            <?php get_template_part('page_part/loop', 'annonces_medium'); ?>
		                        </div>
	                        </li>
		                <?php endwhile; ?>	              
				    <?php wp_reset_postdata(); ?>
		       		<?php endif; ?>
                </ul>											
			</div>	

		<?php // SI ON A AUCUN RESULTAT NUL PART 
		    ; else : ?>
				<div class="w_grid limited-content">                
		        	<div class="grid-col col_size-12">
						<article>
							<p><?php _e("Il n'y a pas de résultat pour votre recherche", "wami"); ?></p>
							<a href="<?php bloginfo('url'); ?>"><?php _e("Retourner sur la page d'accueil", "wami"); ?></a>
						</article>
					</div>
				</div>

		<?php endif; ?>


	</section>
</div>
	
<?php get_footer();