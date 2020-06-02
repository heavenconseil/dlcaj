<?php
// POUR LES BIENS
add_action('wp_ajax_nopriv_wami_filtres_les_biens', 'wami_filtres_les_biens');
add_action('wp_ajax_wami_filtres_les_biens', 'wami_filtres_les_biens');
function wami_filtres_les_biens(){
	if( isset($_REQUEST['data']) ) {
		
		global $post;

		$total = 0;
		$content = "";
		$mes_filtres = $_REQUEST['data'];	
		
		// 1- on traite la base de la requete
		$filtres = array(
        	'post_type'		=> 'biens',
        	'post_status'	=> 'publish',
        	'meta_query'	=> array(
        		'relation'	=> 'AND', 
        		array(
        			'key' 		=> 'bien_disponible',
        			'value' 	=> 1,
        			'compare' 	=> '='
        		)
        	),	
        	'tax_query'	=> array(),		            	
        	'posts_per_page'=> -1
        );

		if( !empty($mes_filtres['ma_page']) && $mes_filtres['ma_page']=='les-coups-de-coeurs' ){
			$filtres['meta_query'][] = array(
    			'key' 		=> 'bien_coup_de_coeur',
    			'value' 	=> 1,
    			'compare' 	=> '='
    		);   
		}

		if( !empty($mes_filtres['lieu']) ){
			$filtres['tax_query'][] = array(
    			'taxonomy' => 'lieu',
				'field'    => 'slug',
				'terms'    => $mes_filtres['lieu'],
    		);
		} 

		if( !empty($mes_filtres['type_bien']) ){
			/*$filtres['tax_query'][] = array(
    			'taxonomy' => 'type_bien',
				'field'    => 'slug',
				'terms'    => $mes_filtres['type_bien'],
    		);*/
    		$filtres['meta_query'][] = array(
				'key'		=> 'bien_type',
				'value'		=> $mes_filtres['type_bien']
			);
		}

		if( !empty($mes_filtres['prix']) ){
			$filtres['meta_query'][] = array(
				'key'		=> 'bien_prix_et_honoraires',
				'value'		=> $mes_filtres['prix'],
	        	'compare'	=> '<=',
	        	'type' 		=> 'numeric'
			);
		}

		if( !empty($mes_filtres['surface']) ){
			$filtres['meta_query'][] = array(
				'key'		=> 'bien_surface_habitable',
				'value'		=> $mes_filtres['surface'],
	        	'compare'	=> '>=',
	        	'type' 		=> 'numeric'
			);
		}

		$manouvelle_query =  new WP_Query($filtres);
			
			if($manouvelle_query->have_posts()): 
				$total = $manouvelle_query->post_count;
				while($manouvelle_query->have_posts()) :
            		$manouvelle_query->the_post(); 		                    		
                    $content .= '<li class="grid-col col_size-4 tablet_size-6 mobile_size-12 bottom-gutter annonce_bien_trouve">
                        <div class="annonce">';
                        	//$file = file_get_contents(STYLESHEETPATH . '/template-part.php');
                           $content .= wami_return_annonces_medium($post); 
                        $content .= '</div>
                    </li>';
		        endwhile; 
		        wp_reset_postdata();
			
			; else :  
				$total = 0;
				// on affiche les derniers biens publiés 
				$content .= '<div class="annonce_bien_trouve grid-col col_size-12 bottom-gutter">';
					$content .= '<h2>'.__("il n'y a pas de résultat pour votre recherche, mais retrouvez nos dernières offres :", "wami").'</h2>';
				$content .= '</div>';                
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
	            if($query_biens->have_posts()) :
	            	while($query_biens->have_posts()) :
                		$query_biens->the_post(); 		                    		
                        $content .= '<li class="grid-col col_size-4 tablet_size-6 mobile_size-12 bottom-gutter annonce_bien_trouve">
	                        <div class="annonce">';
	                        	$content .= wami_return_annonces_medium($post); 
	                        $content .= '</div>
                        </li>';
	                endwhile; 
	                wp_reset_postdata(); 
	            endif; 
	                	
			endif;
		} 

	$return = array(
	    'total'    		=> $total,
	    'content'       => $content
	);
	wp_send_json($return);	
	die();
} 


// POUR LES ACTUALITES
add_action('wp_ajax_nopriv_wami_filtres_les_actualites', 'wami_filtres_les_actualites');
add_action('wp_ajax_wami_filtres_les_actualites', 'wami_filtres_les_actualites');
function wami_filtres_les_actualites(){
	if( isset($_REQUEST['data']) ) {
		$mes_filtres = $_REQUEST['data'];	
		
		// 1- on traite la base de la requete			 
		$filtres = array(
            'post_type'         => 'post',
            'post_status'       => 'publish',  
            'order'             => 'ASC',
            'orderby'           => 'meta_value_num',
            'meta_key'          => 'date_rdv',                         
            'posts_per_page'    => -1
        );

		if( !empty($mes_filtres['periode']) ){
			$first_day = $mes_filtres['periode']."01";
			$last_day  = $mes_filtres['periode'].date("t", strtotime($first_day));   
			$filtres['meta_query'][] = array(
                'key'       => 'date_rdv',
                'value' 	=> array($first_day, $last_day),
			    'type'		=> 'DATE',
			    'compare' 	=> 'BETWEEN'
            );
		}

		if( !empty($mes_filtres['lieu']) ){
			$filtres['tax_query'][] = array(
    			'taxonomy' => 'lieu',
				'field'    => 'slug',
				'terms'    => $mes_filtres['lieu'],
    		);
		} 

		$manouvelle_query =  new WP_Query($filtres);

            if($manouvelle_query->have_posts()) : ?>
                <?php while($manouvelle_query->have_posts()) :
                $manouvelle_query->the_post(); ?>
                    <?php get_template_part("page_part/loop", "atelier"); ?>
                <?php endwhile; ?>
            <?php wp_reset_postdata(); 
			
			; else :  
			// on affiche les derniers biens publiés ?>
				<div class="re_recherche annonce_bien_trouve">
					<?php _e("Il n'y a pas de résultat pour votre recherche.", "wami") ?>						
				</div><?php	
			endif;
		} 
	
	die();
} 