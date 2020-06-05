<?php $fields = array(  
    'field_597609cc2fc49', // Type de mandat (A LAISSER ET CACHER EN CSS CAR LES AUTRES CHAMPS EN DEPENDENT)
    // CONTACT DE L'ACHETEUR : 
    'field_597b51ef3b8b2', // titre ancre    
    'field_597ee87405669', // Nom
    'field_597ee9320566f', // bâtiment
    'field_597ee88f0566a', // Prenom
    'field_597ee93d05670', // etage
    'field_597ee8b00566b', // Telephone
    'field_597ee94805671', // CP
    'field_597ee8c10566c', // email
    'field_597ee95405672', // Ville
    'field_597ee8ce0566d', // adresse 1
    'field_597ee95e05673', // Région
    'field_597ee8fc0566e', // adresse 2
    'field_597ee96805674', // Pays   
    // NOTAIRE DE L'ACHETEUR : 
    'field_597b52213b8b3', // titre ancre    
    'field_597ee98505676', // Nom
    'field_597ee9de0567c', // bâtiment
    'field_597ee99605677', // Prenom
    'field_597ee9e50567d', // etage
    'field_597ee9a305678', // Telephone
    'field_597ee9ee0567e', // CP
    'field_597ee9aa05679', // email
    'field_597ee9f70567f', // Ville
    'field_597ee9b30567a', // adresse 1
    'field_597eea0005680', // Région
    'field_597ee9d60567b', // adresse 2
    'field_597eea0905681', // Pays  
    // NOTAIRE DU VENDEUR : 
    'field_597efdef5cd4d', // titre ancre   
    'field_597eea730568e', // Nom
    'field_597eea6c05688', // bâtiment
    'field_597eea710568d', // Prenom
    'field_597eea6b05687', // etage
    'field_597eea700568c', // Telephone
    'field_597eea6a05686', // CP
    'field_597eea6f0568b', // email 
    'field_597eea6905685', // Ville
    'field_597eea6e0568a', // adresse 1
    'field_597eea6805684', // Région
    'field_597eea6d05689', // adresse 2
    'field_597eea6605683', // Pays  
    // CONDIITIONS SUSPENSIVES
    'field_597f02b570fda', // titre ancre
    'field_597eeb671ceb9', // jusqu'au    
    // DATE DE SIGNATURE
    'field_597b527d3b8b5', // titre ancre
    'field_597eebb61ceba', // Promesses
    'field_597eebec1cebb', // Acte authentique
    'field_59088607dfe53', // bien_honoraires_montant    
    // NOTES INTERNES 
    'field_597b52973b8b6', // titre ancre   
    'field_597746bc95c2b', // Notes internes                                      
);
?>

    <div class="sub-section_detailbien">
        <?php $args_bien = array(
            'post_id'           => 'new_post',
            'new_post'          => true,
            'fields'            => $fields,
            'field_el'          => 'div',
            'form'              => true, 
            'new_post'          => array(
                        'post_type'   => 'biens',
                        'post_status' => 'pending',
            ),           
            'submit_value' => 'Enregistrer'
        ); ?>  
        
        <?php if(get_query_var('page')) :
                                                                
            $args_bien['post_id']       = get_query_var('page');
            $args_bien['new_post']      = false;

            $args_query = array(
                'post__in'  => array(get_query_var('page')),
                'post_type' => 'biens',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
            $my_posts = new WP_Query($args_query); ?>                                    
           
            <?php if($my_posts->have_posts()) :
                while($my_posts->have_posts()) : 
                    $my_posts->the_post();
                        acf_form($args_bien); ?>                                            
                <?php endwhile; ?>                                   
            <?php endif; ?>

        <?php else : ?> 

            <?php acf_form($args_bien); ?>
        
        <?php endif; ?> 
        

    </div>

