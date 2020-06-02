<?php
include_add_action_for_acf_form();
$user = wp_get_current_user();

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>

    <?php get_header('middle-office'); ?>

        <div class="tpl-contacts-list middle_office">
            <section id="contacts"> 
            
                <div class="w_grid limited-content">                
                    <div class="grid-col col_size-12">                  
                        <div id="contacts-filters">                     
                            <?php get_template_part('search', 'contacts'); ?>                            
                        </div>                  
                    </div>
                </div>

                <div class="w_grid limited-content">    
                    <ul class="contact-list tpl-tripple">  
                        <?php 
                        $args = array(
                            'post_type'     => 'bien_contact',
                            'post_status'   => 'publish',
                            'author__in'    => array( $user->ID ),                
                            'posts_per_page'=> -1
                        );
                        $query_contacts = new WP_Query( $args );
                        if($query_contacts->have_posts()) : ?>          
                            <?php while($query_contacts->have_posts()) :
                                $query_contacts->the_post(); ?>
                                    <?php echo get_template_part('page_part/loop', 'contact_list'); ?> 
                            <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                        <?php endif; ?> 
                    </ul>
                </div>  

            </section> 
        </div>

        <?php $etat = get_query_var('page') ? '' : 'close'; ?>
        <div id="ajouter_contact" class="popin-layer <?php echo $etat; ?>">
            <div class="popin">
                <?php get_template_part('page_part/mo', 'add-contact-section'); ?>
            </div>
        </div>
        
       

    <?php acf_enqueue_uploader(); ?>    
    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>