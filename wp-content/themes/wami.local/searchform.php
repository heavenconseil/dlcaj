<?php 
$tpl = array('tpl-actualites.php');
$class = is_page_template($tpl) ? " submit-secondary" : ''; 
?>

<form role="search" method="get" action="<?php echo home_url('/'); ?>" class="single-field">
	<input type="hidden" name="search-type" value="all" />
	<input type="text" placeholder="<?php esc_attr_e("Rechercher", 'wami'); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
	<input type="submit" value="ok" class="right-arrow<?php echo $class; ?>">
</form>
