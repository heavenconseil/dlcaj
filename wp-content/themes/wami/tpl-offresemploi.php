<?php
/*
Template Name: TPL Offres d'emploi
*/
?>
<?php get_header(); ?>

<section id="emploi">

    <div class="bg-overlay"></div>
    
    <div class="w_grid limited-content">
    
        <div class="grid-col col_size-12">
            <div class="page-title">
                <h1>Offres d'emploi</h1>
                <!-- <form class="grid-col col_size-3 tablet_size-6 mobile_size-12 col_hack-padding">
                    <fieldset class="align-right single-field">
                        <input type="text" placeholder="Region">
                        <input type="submit" class="right-arrow btn-secondary" value="ok">
                    </fieldset>
                </form> -->
            </div>
        </div>
        
        
        <div class="grid-col col_size-6 tablet_size-12">
            <h2>Nous recherchons</h2>
        </div>
        <div class="grid-col col_size-6 tablet_size-12 no-padding">
            <ul id="job-list">
                <?php 
                    $args = array(
                        'post_type'     => 'offres-emploi',
                        'post_status'   => 'publish',                               
                        'posts_per_page'=> -1
                    );
                    $query_biens = new WP_Query( $args );
                    if($query_biens->have_posts()) : ?>
                        <?php while($query_biens->have_posts()) :
                            $query_biens->the_post(); ?>                                                            
                            <li class="grid-col col_size-12 tablet_size-6 mobile_size-12">
                                <div class="job-item">
                                    <h3 class="job-title">
                                        <?php the_title(); ?> 
                                        <span class="job-remaining">(<?php the_field('offre_emploi_nombre_de_poste_a_pourvoir'); ?> 
                                        poste<?php if(get_field('offre_emploi_nombre_de_poste_a_pourvoir')>1) echo 's'; ?> à pourvoir)</span>
                                    </h3>

                                    <a href="mailto:<?php the_field('offre_emploi_mail_du_contact'); ?>" class="button btn-primary btn-to-right">contact</a>
                                    <p><?php the_field('offre_emploi_region'); ?></p>
                                </div>
                            </li>
                        <?php endwhile; ?>                    
                    <?php wp_reset_postdata(); ?>
                    <?php endif; ?>

            </ul>
        </div>
        
        
        
    </div>
    
</section>   
	

<?php get_footer(); ?>