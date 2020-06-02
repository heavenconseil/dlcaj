<?php

function get_count_term_by_post_type($term,$taxonomy,$type){
    $args = array( 
        'fields' =>'ids', 
        'post_type' => $type, 
        'post_status'	=> 'publish',
        'meta_query'	=> array(
    		array(
    			'key' 		=> 'bien_disponible',
    			'value' 	=> 1,
    			'compare' 	=> '='
    		)
    	),
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $term
            )
        ),
        'posts_per_page' => -1
     );
    $ps = get_posts( $args );
    if (count($ps) > 0){
    	return count($ps);
    }else{
    	return 0;
    }
}

/* Fonction permettrant de récuperer la taxonomie d'un post */
function get_post_parent_taxonomies($post_id, $type_taxonomy ){
	$terms = wp_get_post_terms($post_id, $type_taxonomy );
	foreach($terms as $k=>$t){
		if( $t->parent > 0 )  unset($terms[$k]);
	}
	return $terms;
}


/* Fonctions permettant de récuperer d'une part toutes les taxonomies parents et d'autre part toutes ses sous-taxonomies */
function get_all_parents_taxonomies($type){
	$terms_ordering = array();
	$sans_ordre = 0;
	
	// Juste les région
	$terms = get_terms( $type, array(
	    'hide_empty' => false,
	    'orderby'    => 'id', 
    	'order'      => 'ASC',    	
	    'parent'        => false,
    	/*'meta_query'    => array(                   
	        array(
	            'key'       => 'type_de_lieu',
	            'value'     => 'hors-carte',
	            'compare'   => '!='
	        ),
	    )*/
	));		
	foreach($terms as $k=>$t){
		if(get_field('ordre_taxo',  $type.'_'.$t->term_id)) {
			$nb = 1000 + get_field('ordre_taxo',  $type.'_'.$t->term_id);
			$terms_ordering[$nb] = $t;
		} else {
			$terms_ordering[$sans_ordre] = $t;
			$sans_ordre++;
		}		
	}
	
	// Juste l'internat 
	/*$internat = get_terms('lieu', array(
	    "hide_empty"    => false,
	    'orderby'       => 'count',
	    'order'         => 'DESC',
	    'parent'        => false,
	    'post_type'     => 'biens',
	    'meta_query'    => array(                   
	        array(
	            'key'       => 'type_de_lieu',
	            'value'     => 'hors-carte',
	            'compare'   => '=='
	        ),
	    ),
	    //'number' => 1
	)); 
	debug($internat);
	foreach($internat as $k=>$t){			
		$terms_ordering[$sans_ordre] = $t;
		$sans_ordre++;
	}	*/

	ksort( $terms_ordering, SORT_NUMERIC );	
	return $terms_ordering;
}

function get_all_childrens_taxonomies($type, $id_parent){
	$terms_ordering = array();
	$sans_ordre = 0;
	$terms = get_terms( $type, array(
	    'hide_empty' 		=> false,
	    'child_of'   		=> $id_parent,
	    //'hierarchical'      => true,
	));
	// On les ordonne
	foreach($terms as $k=>$t){	
		// On garde que les premiers enfants (niveau 1)
		if( $t->parent != $id_parent )  unset($terms[$k]);
		// On les ordonne				
		else if(get_field('ordre_taxo',  $type.'_'.$t->term_id)) {
			$nb = 1000 + get_field('ordre_taxo',  $type.'_'.$t->term_id);
			$terms_ordering[$nb] = $t;
		} else {
			$terms_ordering[$sans_ordre] = $t;
			$sans_ordre++;
		}
	}
	ksort( $terms_ordering, SORT_NUMERIC );	
	return $terms_ordering;
}

function get_taxonomy_list_by_type_de_lieu($taxonomy, $filtre_type_de_lieu='') { 
	
	$list = '';
	
	// Si on a un post en get alors on va checker si la catégorie doit etre checked 
	$checked_terms = array();
	if(get_query_var('page')) {
		$post_terms = wp_get_post_terms( get_query_var('page'), 'lieu' );
		foreach($post_terms as $t) {			
			$checked_terms[] = $t->term_id;
		} 
	} 
	$region_checked = $ville_checked = $district_checked = array();	
	
	if($filtre_type_de_lieu != 'ville' && $filtre_type_de_lieu != 'lieu') $list = '<ul id="tax_region" data-wp-lists="list:'.$taxonomy.'" class="categorychecklist form-no-clear">';
		
		$parents = get_all_parents_taxonomies($taxonomy);
		foreach ($parents as $p) :
			$region = 'lieu_'.$p->term_id;
			if($filtre_type_de_lieu != 'ville' && $filtre_type_de_lieu != 'lieu') :	
				$type = get_field('type_de_lieu', 'lieu_'.$p->term_id);
				$list .= '<li id="lieu_'.$p->term_id.'" class="wpseo-term-unchecked">
					<label class="selectit">
						<input value="'.$p->term_id.'" name="tax_input[lieu][region]" data-type="'.$type.'" id="lieu_'.$p->term_id.'" type="radio"';
						if( in_array($p->term_id, $checked_terms) ) {
							$list .= ' checked="checked"';
							if($type!='hors-carte') $region_checked[] = $p->name;
						}
						$list .= '> '.$p->name.'
					</label>';
			endif;

				if($filtre_type_de_lieu != 'region') :
				
					$enfants = get_all_childrens_taxonomies($taxonomy, $p->term_id);
					if($enfants && is_array($enfants) ):	
						if($filtre_type_de_lieu != 'lieu') $list .= '<ul class="tax_ville children">';
						foreach( $enfants as $e): 
							$ville = 'lieu_'.$e->term_id;
							if($filtre_type_de_lieu != 'lieu') :
								$list .= '<li id="lieu_'.$e->term_id.'" class="wpseo-term-unchecked">
									<label class="selectit">
										<input value="'.$e->term_id.'" name="tax_input[lieu][ville]" id="lieu_'.$e->term_id.'"  data-region="'.$region.'" type="radio"';
										if(in_array($e->term_id, $checked_terms)) {
											$list .= ' checked="checked"';
											$ville_checked[] = $e->name;
										}
										$list .= '> '.$e->name.'
									</label>'; 
							endif;

								if($filtre_type_de_lieu != 'ville') :

									$sous_enfants = get_all_childrens_taxonomies($taxonomy, $e->term_id);
									if($sous_enfants && is_array($sous_enfants) ): 
										$list .= '<ul class="tax_district sub_children">';
										foreach( $sous_enfants as $se): 
											$list .= '<li id="lieu_'.$se->term_id.'" class="wpseo-term-unchecked">
												<label class="selectit">
													<input value="'.$se->term_id.'" name="tax_input[lieu][arrondissement]" id="lieu_'.$se->term_id.'" data-region="'.$region.'" data-ville="'.$ville.'" type="radio"';
													if(in_array($se->term_id, $checked_terms)) {
														$list .= ' checked="checked"';
														$district_checked[] = $e->name;
													}
													$list .= '> '.$se->name.'
												</label>
											</li>';
										endforeach; 
										$list .= '</ul>';
									endif; 

								endif; 

							if($filtre_type_de_lieu != 'lieu') $list .= '</li>';
						endforeach;					
						if($filtre_type_de_lieu != 'lieu') $list .= '</ul>';

					endif;

			    endif; 

			if($filtre_type_de_lieu != 'ville' && $filtre_type_de_lieu != 'lieu') $list .= '</li>';
		endforeach;	
	if($filtre_type_de_lieu != 'ville' && $filtre_type_de_lieu != 'lieu') $list .= '</ul>';

	return (object) array(
		'region_checked' 	=> $region_checked,
		'ville_checked' 	=> $ville_checked,
		'district_checked' 	=> $district_checked, 
		'list' 				=> $list,
	);
	
};

function wami_get_term_lieu($qsearch){
	$my_term = false; 

	$array_lieu_slug = array();
	$all_lieu = get_terms( 'lieu', array('hide_empty' => true));
	foreach($all_lieu as $l){
		$array_lieu_slug[] = $l->slug;
	}
	$wami_search_in_array_slug = wami_in_array_like_term_slug($qsearch, $array_lieu_slug);
	//debug($wami_search_in_array_slug);

	// Pour les recherches par ville ou region
	if ( $wami_search_in_array_slug ) :
		$my_term = get_term_by('slug', $wami_search_in_array_slug, 'lieu');
		if($my_term->parent && $my_term->parent!='') $my_term = get_term_by('id', $my_term->parent, 'lieu');

	//if ( term_exists($qsearch, 'lieu') ) :
		//$my_term = get_term_by('name', $qsearch, 'lieu');

	// Pour les recherches par CP
	; elseif ( is_numeric($qsearch) ) :
	 	$terms_args = array(
		    'taxonomy'   => 'lieu',
		    'hide_empty' => false,
		    'meta_query' => array(
		        array(
		            'key'       => 'code_postale',
		            'value'     => $qsearch,
		            'compare'   => 'LIKE'
		        )
		    )
		);
		$terms = get_terms($terms_args);  
		// si on a pas de résultat on va chercher juste les 2 premier chiffres
		if( empty($terms) ) {
	 		$terms_args['meta_query'] = array(
		        array(
		            'key'       => 'code_postale',
		            'value'     => substr($qsearch, 0, 2),
		            'compare'   => 'LIKE'
		        )
		    );	
			$terms = get_terms($terms_args);
		}
		// Si on a plus d'un seul resultat on va chercher le parent
 	 	if( count($terms) > 1 ) {	
 	 		$arr = false;
 	 		foreach($terms as $t){
 	 			if( get_field('type_de_lieu', $t->taxonomy.'_'.$t->term_id) == 'disctrict' ) $arr = $t->parent;
 	 		}
 	 		// On va regarder si on a un arrondissement ou un lieu-dit dans le resultat de recherche si oui on va chercher la ville sinon alors on va chercher la region
 	 		$terms = $arr ? get_term_by('id', $arr, 'lieu') : get_term_by('id', $terms[0]->parent, 'lieu');
 	 	} 
	 	// si on a bien un resultat
	 	if(is_array($terms)) $my_term = $terms[0]; 
	 	elseif(is_object($terms)) $my_term = $terms;

	endif; 

	return $my_term;
}



function wami_terms_clauses( $clauses, $taxonomy, $args ) {
	if ( isset($args['post_type']) && !empty($args['post_type']) /*&& $args['fields'] !== 'count'*/ ) {
		global $wpdb;

		$post_types = array();

		if ( is_array( $args['post_type'] ) ) {
			foreach ( $args['post_type'] as $cpt ) {
				$post_types[] = "'" . $cpt . "'";
			}
		} else {
			$post_types[] = "'" . $args['post_type'] . "'";
		}	
		 
		if(!empty($post_types))	{
			$clauses['fields'] = 'DISTINCT '.str_replace('tt.*', 'tt.term_taxonomy_id, tt.term_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields']).', COUNT(t.term_id) AS count';
			
			//$clauses['join'] .= ' INNER JOIN '.$wpdb->term_relationships.' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN '.$wpdb->posts.' AS p ON p.ID = r.object_id';			
			$clauses['join'] .= ' INNER JOIN '.$wpdb->term_relationships.' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id';
			$clauses['join'] .= ' INNER JOIN '.$wpdb->posts.' AS p ON p.ID = r.object_id';
			$clauses['join'] .= ' INNER JOIN '.$wpdb->postmeta.' AS pm ON ( p.ID = pm.post_id )';
			//$clauses['join'] .= ' INNER JOIN '.$wpdb->postmeta.' AS mt1 ON ( p.ID = mt1.post_id )';
			
			$clauses['where'] .= ' AND p.post_type IN ('.implode(',', $post_types).')';
			//$clauses['where'] .= ' (AND pm.meta_key = bien_coup_de_coeur AND pm.meta_value = 1) ';
			$clauses['where'] .= 'AND ( pm.meta_key = "bien_disponible" AND pm.meta_value = "1" )';

			$clauses['orderby'] = 'GROUP BY t.term_id '.$clauses['orderby'];
		}
		

		/*if ( ! empty( $post_types ) ) {
			$clauses['fields'] = 'DISTINCT ' . str_replace( 'tt.*', 'tt.term_taxonomy_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields'] ) . ', COUNT(p.post_type) AS count';
			$clauses['join'] .= ' LEFT JOIN ' . $wpdb->term_relationships . ' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id LEFT JOIN ' . $wpdb->posts . ' AS p ON p.ID = r.object_id';
			$clauses['where'] .= ' AND (p.post_type IN (' . implode( ',', $post_types ) . ') OR p.post_type IS NULL)';
			$clauses['orderby'] = 'GROUP BY t.term_id ' . $clauses['orderby'];
		}*/
	} 
	return $clauses;
}
add_filter( 'terms_clauses', 'wami_terms_clauses', 10, 3 );





function wami_in_array_like_term_slug($item, $array) {
    $check = false;
    $item = normalize_string($item);
    foreach($array as $a){
        if(strpos($a, $item) !== false) $check = $a;
    }
    return $check;
}

function get_term_link_for_map($term, $tax_type){
	//$l = get_term_link($term->term_id, $tax_type);
	$l = bloginfo('url').'/?search-type='.$tax_type.'&s='.$term->slug;
	return $l;
}
