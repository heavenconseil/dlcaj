<?php 

function wami_add_mo_bien_publish_section($postId = false, $popin_name = 'diffusion-annonce'){  

    $plop = wami_test_acf_required_fields($postId, 1);
    $type_de_mandat = get_field("bien_mandat", get_query_var('page'));
    $text_after = "";
	        
    if( $postId && empty($plop) ) : 
    
            if (is_array($type_de_mandat) && ($type_de_mandat['value']=="mandat_simple" || $type_de_mandat['value']=="mandat_exclusif" || $type_de_mandat['value']=="mandat_exigence") ){
                $text_after .= "<div class='titre_ancre'>Passerelle de diffusion de votre annonce</div>";
                $text_after .= "<label class='selectit'>
                                <input value='dlcaj' name='publication_input[support][]' id='dlcaj' type='checkbox'";
                if(get_field("field_5901b5b467594", $postId)) $text_after .= " checked='checked'";
                $text_after .= "><span class='title_over_input'>De la Cour au Jardin</span></label>";
                $text_after .= "<label class='selectit'>
                                <input value='le_bon_coin' name='publication_input[support][]' id='le_bon_coin' type='checkbox'";
                if(get_field("field_5980a3e646181", $postId)) $text_after .= " checked='checked'";
                $text_after .= "><span class='title_over_input'>Le Bon Coin</span></label>";
                $text_after .= "<label class='selectit'>
                                <input value='se_loger' name='publication_input[support][]' id='se_loger' type='checkbox'";
                if(get_field("field_5980a40846182", $postId)) $text_after .= " checked='checked'";
                $text_after .= "><span class='title_over_input'>Se loger</span></label>";
                $text_after .= "<label class='selectit'>
                                <input value='international' name='publication_input[support][]' id='international' type='checkbox'";
                if(get_field("field_5980a41e46183", $postId)) $text_after .= " checked='checked'";
                $text_after .= "><span class='title_over_input'>International</span></label>";   
            }
            

            $args_bien = array(
                'post_id'           => $postId,
                'new_post'          => false,
                'fields'            => array(
                    'field_59804cafe9f1b', // titre
                    'field_5c7fcc8148e41', // checkbox mandat signe                    
                    'field_5c7fcca648e42', // titre
                    'field_59804ba33938a', // lien du mandat
                ),
                'field_el'          => 'div',
                'form'              => true,  
                'html_after_fields' => $text_after,  
                'submit_value'      => 'Publier'
            ); 

            $args_query = array(
                'post__in'  => array($postId),
                'post_type' => 'biens',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
            $my_posts = new WP_Query($args_query); ?> 

            <?php if($my_posts->have_posts()) :       
                while($my_posts->have_posts()) : 
                    $my_posts->the_post(); ?>
                        
                        <?php if(isset($_GET['erreur'])) : ?>
                            <div class="mention">
                                <p>* Il manque certains champs obligatoire pour pouvoir publier votre annonce : </p>
                                <?php $erreurs = str_replace( "%0A", "<br>", $_GET['erreur'] );
                                echo $erreurs; ?>
                            </p>
                        <?php endif; ?>
                       
                        <?php acf_form($args_bien); ?> 
                        
                        <a href='#annuler' class='button btn-primary annuler close_popin' data-closepopin="<?php echo $popin_name; ?>">Annuler</a>
                
                <?php endwhile; ?>                                   
            <?php endif; ?>
    

    <?php else : ?>

        <p class="with_list">Tous les champs obligatoires relatif à la publication de votre annonce n'ont pas été renseignés merci de bien vouloir renseigner les champs suivant pour publier votre annonce :</p>
        <ul>
            <?php foreach($plop as $p) { echo '<li>'.$p['label'].'</li>'; } ?>
        </ul>
        <a href='#annuler' class='button btn-primary annuler close_popin' data-closepopin="<?php echo $popin_name; ?>">Fermer</a>

    <?php endif; 

} 