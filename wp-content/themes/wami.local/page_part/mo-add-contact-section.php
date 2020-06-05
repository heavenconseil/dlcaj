<?php $fields = array( 
   'field_59760096b949d', // Civilité
   'field_59760079b949b', //nom
   'field_59760084b949c', // prenom
   'field_591ee75292e8c', // telephone
   'field_591ee74792e8b', // Email
   'field_591ee5e132f86', // adresse
   'field_597600b6b949e', // adresse 2
   'field_59760b85a28a7', // Batiment
   'field_59760bcea28a8', // Etage
   'field_59760bd7a28a9', // Code porte 1
   'field_59760be8a28aa', // Code porte 2
   'field_59760bfaa28ab', // Arrondissement
   'field_597600c9b949f', // CP
   'field_597600d5b94a0', // Ville
   'field_5981995c9e2d7', // Type de contact
   'field_59760137b94a2', // parrainage
);
?>

    <?php $args = array(
        'post_id'           => 'new_post',
        'new_post'          => true,
        'fields'            => $fields,
        'field_el'          => 'div',
        'form'              => true, 
        'new_post'          => array(
                'post_type'   => 'bien_contact',
                'post_status' => 'publish',
        ),  
        'html_after_fields' => $text_after,           
        'submit_value' => 'Enregistrer'
    ); ?>  

    <?php if(get_query_var('page')) :
                                                            
        $args['post_id']       = get_query_var('page');
        $args['new_post']      = false;

        $args_query = array(
            'post__in'  => array(get_query_var('page')),
            'post_type' => 'bien_contact',
            'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
        $my_posts = new WP_Query($args_query); ?>                                    
       
        <?php if($my_posts->have_posts()) :
            while($my_posts->have_posts()) : 
                $my_posts->the_post(); ?>
                    <p class="titre_form">Modifier le contact</p>
                    <?php acf_form($args); ?>                                
            <?php endwhile; ?>                                   
        <?php endif; ?>

    <?php else : ?> 

        <p class="titre_form">Nouveau contact</p>
        <?php acf_form($args); ?>
    <?php endif; ?>

        <a href='#annuler' class='button btn-primary annuler close_popin' data-closepopin="ajouter_contact">Annuler</a>
