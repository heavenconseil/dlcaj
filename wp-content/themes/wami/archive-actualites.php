<?php
/*
 archive actualités
*/
?>
<?php get_header(); 

    $page_actu_id = ($post = get_page_by_path('actualites', OBJECT, 'page')) ? $post->ID : 57;
?>

	 <section id="workshop">        
        
        <!-- TITRE + FILTRES -->
        <div class="w_grid limited-content">
            <div class="grid-col col_size-12">
                <h1 class="title-button"><?php echo get_field("tpl_actu_titre", $page_actu_id) ? get_field("tpl_actu_titre", $page_actu_id) : "Les rendez-vous de la cour au jardin"; ?> <a href="<?php echo get_field("tpl_actu_lien_bouton_de_contact", $page_actu_id) ? get_field("tpl_actu_lien_bouton_de_contact", $page_actu_id) : wami_get_page_link('59'); ?>" class="button btn-secondary btn-to-right" <?php if(get_field("tpl_actu_bouton_contact_target_blank", $page_actu_id)) echo "target='_blank'"; ?>><?php echo get_field("tpl_actu_titre_texte_du_bouton_de_contact", $page_actu_id) ? get_field("tpl_actu_titre_texte_du_bouton_de_contact", $page_actu_id) : "nous contacter"; ?></a></h1>
            </div>            
            <div class="grid-col col_size-6 mobile_size-12">
                <p class="intro"><?php echo get_field("tpl_actu_texte_introduction", $page_actu_id) ? get_field("tpl_actu_texte_introduction", $page_actu_id) : "Retrouvez toutes nos rencontres, ateliers conférences organisés par la communauté de la cour au jardin"; ?></p>
            </div>            
            <div class="grid-col col_size-6 mobile_size-12">
                <p class="cta"><?php echo get_field("tpl_actu_texte_a_droite_de_introduction", $page_actu_id) ? get_field("tpl_actu_texte_a_droite_de_introduction", $page_actu_id) : "Vous souhaitez organiser un évènement dans votre ville ?"; ?></p>
                <a href="#" class="button btn-secondary mobile-only"><?php echo get_field("tpl_actu_titre_texte_du_bouton_de_contact", $page_actu_id) ? get_field("tpl_actu_titre_texte_du_bouton_de_contact", $page_actu_id) : "nous contacter"; ?></a>
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
                    'post_type'         => 'actualites',
                    'post_status'       => 'publish',  
                    'order'             => 'DESC',                     
                    'posts_per_page'    => -1
                );
                $query_atelier = new WP_Query( $args );
                if($query_atelier->have_posts()) : ?>
                    
                    <?php while($query_atelier->have_posts()) :
                    $query_atelier->the_post(); ?>
                        <?php get_template_part("page_part/loop", "actualite"); ?>
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
		
	

<?php get_footer(); 