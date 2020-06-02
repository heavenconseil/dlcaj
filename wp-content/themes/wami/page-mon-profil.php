<?php 
include_add_action_for_acf_form();
$user = wp_get_current_user();

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>


    <?php get_header('middle-office'); ?>


        <div class="tpl-annonces-list middle_office">
            <section id="user_profil">
                <div class="user_profil_conteneur">
                
                    <!-- <div class="user_profil_title">
                        <div class="w_grid limited-content">                
                            <div class="grid-col col_size-12">                  
                                <?php the_title(); ?>              
                            </div>
                        </div>
                    </div> -->


                    <div class="user_profil_content">    
                        <div class="w_grid limited-content no-gutter">      
                            <?php get_template_part('page_part/mo', 'change-profil'); ?>
                        </div>                      
                    </div>

                </div>
            </section>
        </div>

    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>