<?php 
$my_term = get_queried_object(); 
$image   = get_field('image', $my_term->taxonomy.'_'.$my_term->term_id); 
while( !$image && $my_term->parent!="" ){
	$my_term = get_term_by( 'id', $my_term->parent, 'lieu' );
	$image   = get_field('image', $my_term->taxonomy.'_'.$my_term->term_id); 
}
?>

<?php get_header(); ?>
	
	<div class="tpl-ville">
		
		<section id="city-infos">			
			<div class="bg-overlay">
				<?php if($image) : 
            		echo '<img src="'.$image['sizes']['paysage_full'].'">';         
		        ; else : echo '<img  src="http://placehold.it/1440x1024">'; 
		        endif; ?>	            
	        </div>
	        <div class="w_grid limited-content">        
	            <div class="grid-col col_size-12">
	                <div id="back">
	                    <a href="<?php echo wami_get_page_link('2'); ?>" class="button btn-secondary ico-left arrow-left">Retour aux annonces</a>
	                </div>
	            </div>	            
	            <div class="adjust-size">
	                <div class="grid-col col_size-6 mobile_size-12">
	                    <!-- <h2 class="city-name"><?php echo $my_term->name; ?></h2> -->
	                </div>
	                <div class="grid-col col_size-6 mobile_size-12">
	                    <div class="detail-city">
	                        <h3><?php echo $my_term->name; ?></h3>
	                        <p><?php echo $my_term->description; ?></p>
	                    </div>
	                </div>
	            </div>	            
	        </div>	    
    	</section>

    	<?php 
    	$adresse = get_field('adresse_map', $my_term->taxonomy.'_'.$my_term->term_id); 
		$type    = get_field('type_de_lieu', $my_term->taxonomy.'_'.$my_term->term_id);
    	if($type != 'hors-carte') : ?>		
			<section id="city-map">    
		        <div class="w_grid no-gutter">        
		            <div class="grid-col col_size-8 tablet_size-6 mobile_size-12">
		                <?php if($adresse) : ?>
	                		<div id="map" data-markerid="marker_0" data-adresse="<?php echo $adresse['address']; ?>" data-lat="<?php echo $adresse['lat']; ?>" data-lng="<?php echo $adresse['lng']; ?>" data-zoom="<?php echo $type; ?>"></div>
		                <?php endif; ?>
		            </div>        
		            <div class="scrollover grid-col col_size-4 tablet_size-6 mobile_size-12">
		                <div id="recommendations">
		                    <h3>Les adresses recommandées</h3>
		                    <?php if(have_rows('lieu_de_reference', $my_term->taxonomy.'_'.$my_term->term_id)) : $i = 1; ?>
			                    <ul id="recommendation-list">
			                    	<?php while(have_rows('lieu_de_reference', $my_term->taxonomy.'_'.$my_term->term_id)):
			                    	the_row(); ?>
										<li>
			                    		<?php $adresse = get_sub_field('adresse_map'); ?>
			                    		<?php if(is_array($adresse) && $adresse['address']) echo '<a href="#" class="lien_adresse_map adresse_hotspot" data-markerid="marker_'.$i.'" data-adresse="'.$adresse['address'].'" data-lat="'.$adresse['lat'].'" data-lng="'.$adresse['lng'].'" >'.get_sub_field('nom').' <br/><span>'.get_sub_field('adresse').'</span></a>'; 
			                    		else echo '<p class="adresse_hotspot">'.get_sub_field('nom').' <br/><span>'.get_sub_field('adresse').'</span></p>'?>
			                    		</li>
			                    	<?php $i++; endwhile; ?>  
			                    </ul>
			                <?php endif; ?>
		                </div>
		            </div>            
		        </div>    
		    </section>
		<?php endif; ?>

    
	    <section id="coupsdecoeur">
	        <div class="w_grid limited-content">
	        	
	        	<?php 
	            $args = array(
	            	'post_type'		=> 'biens',
	            	'post_status'	=> 'publish',
	            	'meta_query'	=> array(
	            		/*'relation'	=> 'AND',
	            		array(
	            			'key' 		=> 'bien_coup_de_coeur',
	            			'value' 	=> 1,
	            			'compare' 	=> '='
	            		), */           		
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
	            	'meta_key' => 'bien_coup_de_coeur', 
			        'orderby' => 'meta_value', 
			        'order' => 'DESC',
	            	'posts_per_page'=> 3
	            );
	            $query_biens = new WP_Query( $args );
	            if($query_biens->have_posts()) : ?>
		            
		            <div class="grid-col col_size-12">
		                <h3>Coups de coeur</h3>
		            </div>            
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
		            <a href="<?php bloginfo('url'); ?>?search-type=lieu&s=<?php echo $my_term->slug; ?>" class="button btn-secondary">Toutes les annonces</a>		        
			    
			    <?php wp_reset_postdata(); ?>
	       		<?php endif; ?> 

	        </div>
	    </section>

		
	</div>


<?php get_footer();