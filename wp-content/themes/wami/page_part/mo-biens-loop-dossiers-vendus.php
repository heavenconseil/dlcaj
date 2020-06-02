<div class="annonce_bien">

    <div class="infos_bien">
        <p class="dossier">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></p>
        <?php echo wami_return_bien_proprietaire($post); ?>  
        
        <p>Acquéreur : <span><?php echo get_field('bien_vente_acheteur_nom') ? get_field('bien_vente_acheteur_nom') : "-"; ?></span></p>
        <p>Date de signature : <span><?php echo get_field('bien_vente_date_signature_promesse') ? get_field('bien_vente_date_signature_promesse') : "-"; ?></span></p>
    </div>

    <div class="details_bien">
        <?php echo wami_return_bien_annonce_mo($post, 0); ?> 
    </div>

    <?php echo wami_return_bien_presentation_mo($post, 0); ?>
    
</div>