<?php $terms = get_field_object('field_5976161dead11'); 
//$terms = get_terms("type_bien"); //debug($terms); ?>

<?php $qsearch = get_search_query(); 
$my_term = $qsearch!="" ? get_term_by('slug', $qsearch, 'lieu') : 0;  
$submit_val = $my_term ? $my_term->name.' ('.get_count_term_by_post_type($my_term,'lieu','biens').')' : 'Rechercher'; //$my_term->count ?>

<form id="filtre_les_biens">
    <fieldset class="single-line align-right grid-col col_size-8 tablet_size-12 col_hack-padding">

		<input id="mon_lieu" type="hidden" value="<?php if($my_term) echo $my_term->slug; ?>" data-nicename="<?php if($my_term) echo $my_term->name; ?>">
    	<input id="ma_page" type="hidden" value="<?php echo $post->post_name; ?>">
       
        <select id="filtre_typedebien" name="filtre_typedebien">
            <option value="">Type de bien</option>            
            <?php if(is_array($terms)):
	            foreach($terms['choices'] as $k=>$t): ?>
	            	<option value="<?php echo $k; ?>"><?php echo $t; ?></option>
	            <?php endforeach;  
            endif; ?>
        </select>

        <input id="filtre_prix" name="filtre_prix" type="number" placeholder="Prix (€) max" value="">
        
        <input id="filtre_surface" name="filtre_surface" type="number" placeholder="Surface (m²) min" value="">
		
		<input type="submit" value="<?php echo $submit_val; ?>" class="submit-secondary">
   
    </fieldset>
</form>
