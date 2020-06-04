<?php get_header(); ?>

	<?php while(have_posts()):
		the_post(); ?>

		<section id="main">
    
	        <div class="w_grid limited-content">
	            <div class="grid-col col_size-12">
	                <div class="page-content">                
	                    <h1><?php the_title(); ?></h1>        
	                    <?php the_content(); ?>   
	                </div>
	            </div>
	        </div>
	        
	    </section>

	<?php endwhile; ?>

<?php get_footer(); ?>