<?php
//remplacé par la fonction wami_mo_request_annonces_display() dans le fichier functions/mo-mandat-query-annonces.php

 /*<div class="annonce_bien">

    <div class="infos_bien">
        <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="dossier">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></a>
        <?php echo wami_return_bien_proprietaire($post); ?>
        
        <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="button btn-accent edit_annonce">Editer l'annonce</a>
        <?php if($post->post_status != 'publish') : ?>
            <a href="#diffusion-annonce" class="button btn-accent open_popin" data-openpopin="diffusion-annonce-<?php echo $post->ID; ?>" data-bid="<?php echo $post->ID; ?>">Diffuser l'annonce</a>   
        <?php ; else : ?>
            <a href="" class="button btn-accent open_popin declare_vente" data-openpopin="confirme-declarer-vente-<?php echo $post->ID; ?>" data-bid="<?php echo $post->ID; ?>">Déclarer la vente</a>  
        <?php endif; ?>   

        <!-- <a href="#" class="lien_vendupartiers open_popin" data-openpopin="vendupartiers-<?php echo $post->ID; ?>" data-bid="<?php echo $post->ID; ?>">Mandat vendu par un tiers</a>
            <div id="vendupartiers-<?php echo $post->ID; ?>" class="popin-layer close">
                <div class="popin vendupartiers">
                    <p class="titre">Attention vous êtes sur le point de supprimer ce mandat</p>
                    <p>Confirmez-vous sa vente par un tiers ?</p>
                    <br>
                    <a href='#' class='button btn-primary close_popin annuler' data-closepopin='vendupartiers-<?php echo $post->ID; ?>'>Annuler</a>
                    <a href='#' class='button btn-primary confirmer' data-closepopin='vendupartiers-<?php echo $post->ID; ?>' data-mandatid="<?php echo $post->ID; ?>">Confirmer</a>
                </div>
            </div>          --> 
    </div>

    <div class="details_bien">        
        <?php echo wami_return_bien_annonce_mo($post); ?>            
        <div class="stat">
            <p class="button btn-secondary open_popin" data-openpopin="statistique-<?php echo $post->ID; ?>" data-bid="<?php echo $post->ID; ?>">Statistiques</p>
            <div id="statistique-<?php echo $post->ID; ?>" class="popin-layer close">
                <div class="popin statistique">
                    <?php 
                        $consultation = intval(get_post_meta( $post->ID, 'stat_nombre_de_visite', true ));
                        if($consultation && $consultation > 0) echo 'Cette annonce à été consultée '.$consultation.' fois';
                        else echo "Cette annonce n'a pas encore été consultée";
                    ?>    
                </div>
            </div>          
        </div>   
    </div>

    <?php echo wami_return_bien_presentation_mo($post); ?>

</div>


<div id="diffusion-annonce-<?php echo $post->ID; ?>" class="popin-layer close">
    <div class="popin diffusion-annonce">
        <?php wami_add_mo_bien_publish_section( $post->ID, 'diffusion-annonce-'.$post->ID ); ?>   
    </div>
</div>


<div id="confirme-declarer-vente-<?php echo $post->ID; ?>" class="popin-layer close">
    <div class="popin">
    <p class="titre">Confirmez-vous la promesse de vente de ce bien ?</p>
    <p class="green">Une fois confirmé le bien vendu sera placé automatiquement dans la catégorie DOSSIER DE VENTE</p>
    <?php $args_bien = array(
            'post__in'  => array($post->ID),
            'post_type' => 'biens',
            'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
            'fields'            => array(  
                //'field_597609cc2fc49', // Type de mandat (A LAISSER ET CACHER EN CSS CAR LES AUTRES CHAMPS EN DEPENDENT)                
                'field_59088607dfe53', // Montant TTC des honoraires
            ),
            'field_el'          => 'div',
            'form'              => true,             
            'html_after_fields' =>  '<input type="hidden" name="declarer_la_vente" value="1">',      
            'submit_value' => 'Confirmer'
        ); 
        acf_form($args_bien); ?>
        <a href='#annuler' class='button btn-primary annuler close_popin' data-closepopin="confirme-declarer-vente-<?php echo $post->ID; ?>">Annuler</a>
        <!-- <a href='#confirmer' class='button btn-secondary confirmer confirme_declare_vente close_popin' data-closepopin="confirme-declarer-vente-<?php echo $post->ID; ?>">Confirmer</a> -->
    </div>
</div>*/