<?php

function wami_pagination($custom_query=false){
	$current_page = max(1, get_query_var('paged'));
	if(!$custom_query){
		global $wp_query;
		$custom_query = $wp_query;
		$current_page = max(1, get_query_var('page'));
	}
    $total_pages = $custom_query->max_num_pages;
    if($total_pages > 1){
 		echo '<div class="grid-col col_size-12"><div class="pagination">';
        echo paginate_links(array(
			'base'      => get_pagenum_link(1) . '%_%',
			'format'    => '/page/%#%',
			'current'   => $current_page,
			'total'     => $total_pages,
			'show_all'  => false,
			'end_size'  => 1,
			'mid_size'  => 2,
			'prev_text' => '«',
			'next_text' => '»',
        ));
        echo '</div></div>';
    }
}


/* on génère le lien load more en php */
function wami_load_more_link($a=array()){
	$params = array_merge(array(
		'query'       => null,
		'template'    => null,
		'title'       => "Charger + d'articles",
		'zone'        => "#content"
	), $a);

	if(is_null($params['query']) || is_null($params['template']) ){
		return false;
	}

	$attrs_array = $params;
	$attrs_array['query'] = base64_encode(serialize($params['query']->query));

	$attrs = '';
	foreach($attrs_array as $k=>$v){
		$attrs .= ' data-'.$k.'="'.esc_attr($v).'"';
	}

	if( $params['query']->max_num_pages>1 ){
		echo '<a href="#" class="wami_mega_load_more" '.$attrs.'>'.$params['title'].'</a>';
	}
}


/* on obtient le résultat du load more */
add_action('wp_ajax_nopriv_wami_ajax_mega_load_more', 'wami_ajax_mega_load_more');
add_action('wp_ajax_wami_ajax_mega_load_more',        'wami_ajax_mega_load_more');
function wami_ajax_mega_load_more(){
	if(isset($_REQUEST['query']) && isset($_REQUEST['template'])){
		$query_64 = base64_decode($_REQUEST['query']);
		$query = unserialize($query_64);
		$template = $_REQUEST['template'];

		//debug($query, '$query');
		if(!isset($query['paged']) || $query['paged']==0) $query['paged'] = 1;
		$query['paged'] = $query['paged']+1;

		$ajax_query = new WP_Query($query);

		ob_start();
		if($ajax_query->have_posts()){
			while($ajax_query->have_posts()){
				$ajax_query->the_post();
				get_template_part($template);
			}
		}
		// $html : contenu html (utlisant le template spécifié)
		$html = ob_get_contents();
    	ob_end_clean();

		// $button : attribut data-query du lien/bouton (injecté en JS au retour ajax)
    	$button = esc_attr(base64_encode(serialize($ajax_query->query)));

    	// $reste : s'il reste ou non des pages à charger
    	$reste = ($query['paged']<$ajax_query->max_num_pages)?true:false;

		echo json_encode(array(
			'html'   => $html, 
			'button' => $button, 
			'reste'  => $reste
		));
	}
	die();
}