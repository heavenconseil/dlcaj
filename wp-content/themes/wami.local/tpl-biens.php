<?php
/*
Template Name: TPL Biens en vente
*/
?>
<?php get_header(); ?>

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


	        <div class="w_grid limited-content">	
				<ul class="annonce-list tpl-tripple">  
                    <?php 
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

		</section>
	</div>	

<?php get_footer(); ?>