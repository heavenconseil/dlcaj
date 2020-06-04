<?php $user = wp_get_current_user();

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>

    <?php get_header('middle-office'); ?>

        <?php 
        if(get_query_var('page')) :
            get_template_part('page_part/mo', 'team-detail-ambassadeur'); 
        ; else :            
            get_template_part('page_part/mo', 'team-management');
        endif; ?>       

    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>