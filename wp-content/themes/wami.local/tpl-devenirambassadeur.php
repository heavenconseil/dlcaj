<?php
/*
Template Name: TPL Devenir Ambassadeur
*/
?>
<?php get_header(); ?>
	
	<div class="tpl-philosophie">

		<?php while(have_posts()):
			the_post(); ?>

                <div id="page-nav">        
                    <div class="w_grid limited-content">
                        <div class="grid-col col_size-12">
                            <h1><?php the_title(); ?></h1>
                            <ul id="nav">
                                <li><a href="#intro" class="ancredouce">Pourquoi</a></li>
                                <li><a href="#competences" class="ancredouce">Qui</a></li>
                                <li><a href="<?php echo wami_get_page_link('469'); ?>" class="button btn-secondary white">S'inscrire</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        
				<section id="intro" class="content-fix-height reverseResponsive">    
			        <div class="bg-overlay"></div>			        
			        <div class="w_grid limited-content">
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-details-block">
			                    <?php echo get_field('intro_contenu') ? get_field('intro_contenu') : ""; ?>
			                </div>
			            </div>
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-punhline-block">
			                    <div class="bottom-block">
			                    	<h2 class="philo-punchline"><?php echo get_field('intro_titre') ? get_field('intro_titre') : ""; ?></h2>
			                    </div>
			                </div>
			            </div>
			        </div>			        
			    </section>
        
            
                <section id="competences" class="content-fix-height">			        
			        <div class="bg-overlay"></div>			        
			        <div class="w_grid limited-content">
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-details-block">
			                    <?php echo get_field('bloc2_contenu') ? get_field('bloc2_contenu') : ""; ?>
			                </div>
			            </div>
			            <div class="grid-col col_size-6 tablet_size-12">
			                <div class="philo-punhline-block">
			                    <div class="bottom-block">
			                        <h2 class="philo-punchline">
			                        	<?php echo get_field('bloc2_titre') ? get_field('bloc2_titre') : ""; ?>
			                        </h2>
			                        <div class="philo-half-block">
			                            <?php echo get_field('bloc2_mentions') ? get_field('bloc2_mentions') : ""; ?>
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </section>

			    <section id="share-part">			    
		            <div class="grid-col col_size-12">
		                <?php get_template_part('page_part/page-part', 'share'); ?>
		            </div>
			    </section>
			  

		<?php endwhile; ?>

	</div>

<?php get_footer();