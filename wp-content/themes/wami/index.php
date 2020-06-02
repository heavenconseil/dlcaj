<?php 

global $wp_query;

get_header();
get_sidebar(); ?>
	
	<div id="ajax">
	<?php while(have_posts()):
		the_post(); ?>

		<?php get_template_part('page_part/loop'); ?>

	<?php endwhile; ?>
	</div>

	<?php //wami_pagination();
	wami_load_more_link(array(
		'query' => $wp_query, 
		'template' => 'loop',
		'zone' => '#ajax',
	));
	

get_footer();
