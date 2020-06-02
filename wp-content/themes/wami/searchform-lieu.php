<?php 
$tpl = array('tpl-actualites.php');
$class = is_page_template($tpl) ? " submit-secondary" : ''; 
$regions = get_all_parents_taxonomies('lieu');
?>

<form role="search" method="get" action="<?php echo home_url('/'); ?>" class="single-field">
	<input type="hidden" name="search-type" value="lieu" />
	<!-- <input type="text" placeholder="<?php //esc_attr_e("Rechercher un lieu", 'wami'); ?>" value="<?php //echo get_search_query(); ?>" name="s" autocomplete="off" /> -->
	<select name="s">
		<option value="">Rechercher une région</option>
		<?php foreach($regions as $r) : ?>
			<option value="<?php echo $r->slug; ?>" <?php if(get_search_query() && get_search_query()==$r->slug) echo 'selected'; ?>><?php echo $r->name; ?></option>
		<?php endforeach; ?>
	</select>
	<input type="submit" value="ok" class="right-arrow<?php echo $class; ?>">
</form>

 