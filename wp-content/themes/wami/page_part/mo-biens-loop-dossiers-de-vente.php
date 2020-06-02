<div class="annonce_bien">

    <div class="infos_bien">
        <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="dossier">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></a>
        <?php echo wami_return_bien_proprietaire($post); ?> 

        <p>Notaire : <span><?php echo get_field('bien_vente_notairevendeur_nom') ? get_field('bien_vente_notairevendeur_nom') : ""; ?></span></p>
        <br>
        <p>Acquéreur : <span><?php echo get_field('bien_vente_acheteur_nom') ? get_field('bien_vente_acheteur_nom') : "-"; ?></span></p>
        <p>Notaire : <span><?php echo get_field('bien_vente_notaireacheteur_nom') ? get_field('bien_vente_notaireacheteur_nom') : "-"; ?></span></p>
        <br>
        <p>Conditions suspensives : <span><?php echo get_field('bien_vente_conditions_suspensives') ? get_field('bien_vente_conditions_suspensives') : "-"; ?></span></p>    
        <p>Date de signature : <span><?php echo get_field('bien_vente_date_signature_promesse') ? get_field('bien_vente_date_signature_promesse') : "-"; ?></span></p>        

        <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="button btn-accent edit_annonce">Editer l'annonce</a>
        <a href="" class="button btn-accent open_popin declare_vente" data-openpopin="confirme-declarer-vendu" data-bid="<?php echo $post->ID; ?>">Déclarer ce bien comme vendu</a>  
    </div>

    <div class="details_bien">       
        <?php echo wami_return_bien_annonce_mo($post); ?>    
    </div>

    <?php echo wami_return_bien_presentation_mo($post); ?>

</div>