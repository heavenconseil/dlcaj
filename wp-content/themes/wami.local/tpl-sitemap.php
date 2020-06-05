<?php
/*
Template Name: TPL Plan du site
*/
?>
<?php get_header(); ?>
    
    <?php while(have_posts()): the_post(); ?>

			<section id="main">    
                <div class="w_grid limited-content">
                    <div class="grid-col col_size-12">
                       
                        <div class="page-content">                        
                            
                            <h1><?php the_title(); ?></h1>    
                            
                            <div id="sitemap">                                  
                                <?php wp_nav_menu(array(
                                    'theme_location' => 'menu-sitemap1',
                                    'container'      => 'div',
                                    'container_class'=> 'menu-plan-du-site-container grid-col col_size-4 mobile_size-12',
                                    'menu_id'        => 'sitemap1' 
                                )); ?> 
                                <?php wp_nav_menu(array(
                                    'theme_location' => 'menu-sitemap2',
                                    'container'      => 'div',
                                    'container_class'=> 'menu-plan-du-site-container grid-col col_size-4 mobile_size-12',
                                    'menu_id'        => 'sitemap2' 
                                )); ?> 
                                <?php wp_nav_menu(array(
                                    'theme_location' => 'menu-sitemap3',
                                    'container'      => 'div',
                                    'container_class'=> 'menu-plan-du-site-container grid-col col_size-4 mobile_size-12',
                                    'menu_id'        => 'sitemap3' 
                                )); ?>    
                            </div> 

                        </div>

                    </div>
                </div>                
            </section>				
				
	<?php endwhile; ?>

<?php get_footer(); ?>