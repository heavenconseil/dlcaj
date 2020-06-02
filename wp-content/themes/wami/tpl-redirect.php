<?php
/*
Template Name: TPL Redirect to first child
*/

if(have_posts()){
	while(have_posts()){
		the_post();

		$child_pages = get_pages("child_of=".$post->ID."&sort_column=menu_order");
		$firstchild = $child_pages[0];
		wp_redirect(get_permalink($firstchild->ID));
		die();
	}
}
