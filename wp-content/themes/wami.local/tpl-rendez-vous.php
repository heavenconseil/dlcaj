<?php
/*
Template Name: TPL Rendez-vous
*/
?>
<?php get_header(); ?>

	 <section id="workshop">        
        
        <!-- TITRE + FILTRES -->
        <div class="w_grid limited-content">
            <div class="grid-col col_size-12">
                <h1 class="title-button"><?php echo get_field("tpl_actu_titre") ? get_field("tpl_actu_titre") : "Les rendez-vous de la cour au jardin"; ?> <a href="<?php echo wami_get_page_link('59'); ?>" class="button btn-secondary btn-to-right"><?php echo get_field("tpl_actu_titre_texte_du_bouton_de_contact") ? get_field("tpl_actu_titre_texte_du_bouton_de_contact") : "nous contacter"; ?></a></h1>
            </div>            
            <div class="grid-col col_size-6 mobile_size-12">
                <p class="intro"><?php echo get_field("tpl_actu_texte_introduction") ? get_field("tpl_actu_texte_introduction") : "Retrouvez toutes nos rencontres, ateliers conférences organisés par la communauté de la cour au jardin"; ?></p>
            </div>            
            <div class="grid-col col_size-6 mobile_size-12">
                <p class="cta"><?php echo get_field("tpl_actu_texte_a_droite_de_introduction") ? get_field("tpl_actu_texte_a_droite_de_introduction") : "Vous souhaitez organiser un évènement dans votre ville ?"; ?></p>
                <a href="#" class="button btn-secondary mobile-only"><?php echo get_field("tpl_actu_titre_texte_du_bouton_de_contact") ? get_field("tpl_actu_titre_texte_du_bouton_de_contact") : "nous contacter"; ?></a>
            </div>  
            <span class="workshop-filters">
                <div class="filter-container">              
                    <?php //get_template_part('search', 'actualites'); ?>
                </div>
            </span>
        </div>       
        
        
        <!-- LISTE WORKSHOPS -->
        <div class="w_grid limited-content">            
            <div class="workshop-list">				
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
                    'posts_per_page'    => -1
                );
                $query_atelier = new WP_Query( $args );
                if($query_atelier->have_posts()) : ?>
                    
                    <?php while($query_atelier->have_posts()) :
                    $query_atelier->the_post(); ?>
                        <?php get_template_part("page_part/loop", "atelier"); ?>
                    <?php endwhile; ?>
                    
                <?php wp_reset_postdata(); ?>
                <?php endif; ?>               
            </div>            
        </div>    

    </section>      
    
    
    <section id="ambassadors">        
        <div class="w_grid limited-content">            
            <div class="grid-col col_size-12">
                <h2>Témoignages</h2>
            </div>
            <?php $args = array(
                'post_type'         => 'temoignage',
                'post_status'       => 'publish',                               
                'posts_per_page'    => 6
            );
            $query_temoignages = new WP_Query( $args );            
            if($query_temoignages->have_posts()) : ?>  
                <div class="grid-col col_size-12">
                    <div class="ambassadors-list owl-carousel">
                        <?php while($query_temoignages->have_posts()) :
                        $query_temoignages->the_post(); ?>                            
                            <div class="ambassador">
                                <?php get_template_part('page_part/loop', 'temoignage'); ?>
                            </div>
                        <?php endwhile; ?> 
                    </div>
                </div>
            <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>    
    </section>
		
	

<?php get_footer(); ?>