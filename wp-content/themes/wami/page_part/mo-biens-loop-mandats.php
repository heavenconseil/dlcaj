<div class="annonce_bien mandats annonce_mandat_<?php echo $post->ID; ?>">

    <div class="infos_bien">   
        <?php if(get_field('bien_vente_vendu') || get_field('bien_vente_date_signature_acte'))  
            echo '<p class="dossier">'; 
        else echo '<a href="'.wami_get_page_link('ajouter-annonce').'/'.$post->ID.'" class="dossier">'; ?>
            Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?>            
        <?php if(get_field('bien_vente_vendu') || get_field('bien_vente_date_signature_acte')) echo '</p>'; 
        else echo '</a>'; ?>

        <?php echo wami_return_bien_proprietaire($post); ?>  

        <?php 
        $mandat = get_field("bien_mandat");
        $mandat = is_array($mandat) ? $mandat['value'] : false;
        if(get_post_status() == 'publish') {
            if(get_field('bien_vente_vendu') || get_field('bien_vente_date_signature_acte'))  {                
                $etat_mandat = 'Vendu';
            }               
            else {
                $etat_mandat = get_field('mandat_etat'); 
                if(is_array($etat_mandat)) $etat_mandat = $etat_mandat['label'];
                else $etat_mandat = 'en vente';
            }
        }
        elseif($mandat == 'mandat_delegation') $etat_mandat = 'Mandat de délégation en cours';
        else $etat_mandat = 'En cours de création (brouillon)';
        ?>
        <p>Etat du mandat : <span><?php echo $etat_mandat; ?></span></p> 
        
        <a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="button btn-accent">Editer l'annonce</a>
        <!-- <a href="#" class="lien_vendupartiers open_popin" data-openpopin="vendupartiers-<?php echo $post->ID; ?>" data-bid="<?php echo $post->ID; ?>">Mandat vendu par un tiers</a>
            <div id="vendupartiers-<?php echo $post->ID; ?>" class="popin-layer close">
                <div class="popin vendupartiers">
                    <p class="titre">Attention vous êtes sur le point de supprimer ce mandat</p>
                    <p>Confirmez-vous sa vente par un tiers ?</p>
                    <br>
                    <a href='#' class='button btn-primary close_popin annuler' data-closepopin='vendupartiers-<?php echo $post->ID; ?>'>Annuler</a>
                    <a href='#' class='button btn-primary confirmer' data-closepopin='vendupartiers-<?php echo $post->ID; ?>' data-mandatid="<?php echo $post->ID; ?>">Confirmer</a>
                </div>
            </div>   -->
    </div>

    <div class="details_bien">
        <?php echo wami_return_bien_annonce_mo($post); ?>
        <p class="button btn-secondary no-link"><?php echo get_field('bien_disponible') ? 'En ligne' : "En attente"; ?></p>
    </div>

    <?php echo wami_return_bien_presentation_mo($post); ?>
    
</div>