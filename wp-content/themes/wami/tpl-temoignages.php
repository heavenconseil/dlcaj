<?php
/*
Template Name: TPL Témoignage
*/
?>

<?php get_header(); ?>


    <div class="tpl-annonces-list">
        <section id="ambassadors">

            <div class="w_grid limited-content">                
                <div class="grid-col col_size-12">                  
                    <div id="annonces-filters">                     
                        <fieldset class="single-field grid-col col_size-4 tablet_size-12">
                            <?php get_search_form(); ?>
                        </fieldset>
                    </div>                  
                </div>
            </div>

            <div class="w_grid limited-content ambassadors-list">            
                <div class="annonce-list tpl-tripple"> 

                	<?php $args = array(
                    	'post_type'			=> 'temoignage',
                    	'post_status'		=> 'publish',				            	
                    	'posts_per_page'	=> -1
                    );
                    $query_temoignages = new WP_Query( $args );
                    
                    if($query_temoignages->have_posts()) : ?>                        
                        <?php while($query_temoignages->have_posts()) :
                        $query_temoignages->the_post(); ?>
                            <div class="grid-col col_size-4 tablet_size-4 mobile_size-12 bottom-gutter"> 
                                <div class="ambassador">
                                    <?php get_template_part('page_part/loop', 'temoignage'); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>     
                    <?php wp_reset_postdata(); ?>
                	<?php endif; ?>

                </div>
            </div>

        </section>
    </div>

<?php get_footer(); ?>