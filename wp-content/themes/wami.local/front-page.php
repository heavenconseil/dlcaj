<?php get_header(); ?>
	
    <section id="enter">
        <!--<div class="bg-overlay"></div>-->
        <?php if(have_rows("slider_entete_du_site", "option")) : ?>
            <div id="home-slider">
                <ul class="slider-container">
                    <?php while(have_rows("slider_entete_du_site", "option")) : 
                        the_row(); 
                        $image = get_sub_field("image"); ?>
                        <li class="item-slider">
                            <img src="<?php echo $image['url']; ?>">
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="enter-content">
            <div id="enter-logo"><h1><?php bloginfo('name'); ?></h1></div>

            <div class="w_grid limited-content">
                <div class="grid-col col_size-4 tablet_size-10 mobile_size-12 centered">
                    <?php //get_search_form(); 
                    get_template_part('searchform', 'lieu'); ?>
                </div>
            </div>
        </div>

        <!--div id="cta-next">
            <div class="w_grid limited-content no-gutter">
                <div class="grid-col col_size-12">
                    <a href="#">Carte des biens</a>
                </div>
            </div>
        </div-->
    </section>


    <section id="map-country">
        <?php get_template_part('page_part/page-part', 'carte'); ?>
    </section>


    <section id="annonces">
        <div class="w_grid limited-content">
            
            <?php 
            $args = array(
            	'post_type'		=> 'biens',
            	'post_status'	=> 'publish',
            	'meta_query'	=> array(
            		'relation'	=> 'AND',
            		array(
            			'key' 		=> 'bien_coup_de_coeur',
            			'value' 	=> 1,
            			'compare' 	=> '='
            		),            		
            		array(
            			'key' 		=> 'bien_disponible',
            			'value' 	=> 1,
            			'compare' 	=> '='
            		)
            	),
            	'posts_per_page'=> 4
            );
            $query_biens = new WP_Query( $args );
            if($query_biens->have_posts()) : ?>
            	<div class="grid-col col_size-8 tablet_size-6 mobile_size-12">
	                <div id="liked">		                	
	                    <h3>Coups de coeur <a href="<?php echo wami_get_page_link('126'); ?>" class="title-link">voir tout</a></h3>
	                    <ul class="annonce-list tpl-wild">
	                    	<?php while($query_biens->have_posts()) :
	                    		$query_biens->the_post(); ?>
		                        <li class="annonce">
		                            <?php echo get_template_part('page_part/loop', 'annonces_medium'); ?>                            
		                        </li>  
			                <?php endwhile; ?>
	                    </ul>                    
	                    <a href="<?php echo wami_get_page_link('126'); ?>" class="button btn-secondary btn-to-right mobile-only">Tous les coups de coeur</a>
	                </div>
	            </div>
		    <?php wp_reset_postdata(); ?>
       		<?php endif; ?>

       		<?php 
            $args = array(
            	'post_type'		=> 'biens',
            	'post_status'	=> 'publish',
            	'meta_query'	=> array(
            		'relation' 	=> 'AND',
            		array(
            			'key' 		=> 'bien_coup_de_coeur',
            			'value' 	=> 0,
            			'compare' 	=> '='
            		),
            		array(
            			'key' 		=> 'bien_disponible',
            			'value' 	=> 1,
            			'compare' 	=> '='
            		)
            	),
            	'posts_per_page'=> 5
            );
            $query_biens = new WP_Query( $args );
            if($query_biens->have_posts()) : ?>
            	<div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
	                <div id="recently-added">
	                    <h3>Annonces récentes <a href="<?php echo wami_get_page_link('2'); ?>" class="title-link">voir tout</a></h3>
	                    <ul class="annonce-list tpl-thumb">
	                    	<?php while($query_biens->have_posts()) :
	                    		$query_biens->the_post(); ?>
		                        <li class="annonce">
		                            <?php get_template_part('page_part/loop', 'annonces_small'); ?>
		                        </li>
			                <?php endwhile; ?>
	                    </ul>                    
	                    <a href="<?php echo wami_get_page_link('2'); ?>" class="button btn-secondary btn-to-right mobile-only">Toutes les annonces récentes</a>
	                </div>
	            </div>
		    <?php wp_reset_postdata(); ?>
       		<?php endif; ?>
        </div>
    </section>


    <section id="brand-info">
        <div class="w_grid no-gutter">
            
            <?php /*
            <div class="grid-col col_size-4 tablet_size-12">
                <div id="actu">
                    <div class="bg-overlay"></div>
                    <div class="centered-content">
                        <!-- <h3>Ateliers</h3> -->
                        <h4>Les rendez-vous<br/>de la cour au jardin</h4>
                        <p>Retrouvez toutes nos rencontres, ateliers conférences organisés par la communauté de la cour au jardin</p>
                        <?php $args = array(
                            'post_type'         => 'post',
                            'post_status'       => 'publish',       
                            'meta_query'    => array( 
                                array(
                                    'key'       => 'date_rdv',
                                    'value'     => date('Ymd'),
                                    'type'      => 'DATE',
                                    'compare'   => '>='
                                )
                            ),                            
                            'order'             => 'ASC',
                            'orderby'           => 'meta_value_num',
                            'meta_key'          => 'date_rdv',                         
                            'posts_per_page'    => 5
                        );
                        $query_atelier = new WP_Query( $args );
                        if($query_atelier->have_posts()) : ?>
                            <ul class="workshop-list">
                            <?php while($query_atelier->have_posts()) :
                            $query_atelier->the_post(); ?>
                                <li><?php the_field("date_rdv"); ?> : <?php echo wami_get_villedubien($post->ID); ?> <span><?php the_title(); ?></span></li>
                            <?php endwhile; ?>
                            </ul>
                        <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                        <a href="<?php echo wami_get_page_link('rendez-vous'); ?>" class="button btn-accent btn-to-right">Tous les ateliers</a>
                    </div>
                </div>
            </div>
            */ ?>
            
            <div class="grid-col col_size-12 tablet_size-12">
                <div id="testimony">
                    <div class="w_grid limited-content">
                        <?php $args = array(
			            	'post_type'			=> 'temoignage',
			            	'post_status'		=> 'publish',	
                            'meta_query'    => array(
                                array(
                                    'key'       => 'temoignage_mis_en_avant',
                                    'value'     => 1,
                                    'compare'   => '='
                                ),  
                            ),			            	
			            	'posts_per_page'	=> 3
			            );
			            $query_temoignages = new WP_Query( $args );
			            if($query_temoignages->have_posts()) : ?>
				            <?php while($query_temoignages->have_posts()) :
				            $query_temoignages->the_post(); ?>
					            <?php get_template_part('page_part/loop', 'temoignage'); ?>
						    <?php endwhile; ?>
					    <?php wp_reset_postdata(); ?>
			       		<?php endif; ?>
                        <a href="<?php echo wami_get_page_link('266'); ?>" class="button btn-accent btn-to-right">Tous les témoignages</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php get_footer(); ?>