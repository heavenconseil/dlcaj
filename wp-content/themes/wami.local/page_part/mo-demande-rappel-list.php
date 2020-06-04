<?php 
$bien_id = $comment->comment_post_ID;
$auteur = $comment->comment_author; 
$email = $comment->comment_author_email;
$horaire = get_comment_meta($comment->comment_ID, 'horaire', true) ? get_comment_meta($comment->comment_ID, 'horaire', true) : '';
$telephone = get_comment_meta($comment->comment_ID, 'telephone', true) ? get_comment_meta($comment->comment_ID, 'telephone', true) : ''; 
?>                        
                                

<li class="w_grid limited-content content_with_border">    
    
    <div class="grid-col col_size-4 mobile_size-12">
        <a href="<?php echo get_the_permalink($bien_id); ?>" class="nom"><?php echo get_the_title($bien_id); ?></a>
        <ul class="annonce-primary">
            <li class="annonce-price"><?php echo get_field('bien_prix_et_honoraires', $bien_id) ? number_format(get_field('bien_prix_et_honoraires', $bien_id), 0, '', ' ').' €' : ""; ?></li>
            <li class="annonce-city"><?php echo wami_get_villedubien($bien_id); ?></li>
        </ul>
        <div class="details_bien sous_bloc">
            <div class="annonce-cover">
                <a href="<?php echo get_permalink($bien_id); ?>">
                    <?php if(has_post_thumbnail($bien_id)) : 
                        echo get_the_post_thumbnail($bien_id, 'paysage_medium');           
                    ; else : echo '<img src="http://placehold.it/350x230">'; 
                    endif; ?>
                    <?php if(get_field("lien_de_la_visite_360", $bien_id)) : ?>
                        <a href="<?php echo get_field("lien_de_la_visite_360", $bien_id); ?>"><span class="cover-label label-360">visiter</span></a>
                    <?php endif; ?>
                </a>
                <span class="annonce-hover-bar"></span>
            </div>  
            <ul class="annonce-secondary">
                <li class="annonce-metter"><?php echo get_field('bien_surface_habitable', $bien_id) ? get_field('bien_surface_habitable', $bien_id).' m²' : ""; ?></li>
                <li class="annonce-bedroom"><?php echo get_field('bien_nb_chambre', $bien_id) ? get_field('bien_nb_chambre', $bien_id).' chambres' : ""; ?></li>
                <li class="annonce-bathroom"><?php echo get_field('bien_nb_piece_eau', $bien_id) ? get_field('bien_nb_piece_eau', $bien_id).'  salles d\'eau' : ""; ?></li>
            </ul>
            <ul class="annonce-complementary">
                <li class="annonce-environnement">
                    <span class="label">Environnement</span>
                    <ul class="star-notation">
                        <?php $note = wami_cacul_environnement_du_bien($bien_id);
                        for( $i=1; $i<6; $i++) {
                            if($note>=1) echo '<li class="star star-on"></li>';
                            else if($note>0) echo '<li class="star star-float"></li>';
                            else echo '<li class="star star-off"></li>';
                            $note--;
                        } ?>
                    </ul>
                </li>
                <li class="annonce-charme">
                    <span class="label">Charme</span>
                    <ul class="key-notation">
                        <?php $note = wami_cacul_charme_du_bien($bien_id);
                        for( $i=1; $i<6; $i++) {
                            if($note>=1) echo '<li class="key key-on"></li>';
                            else if($note>0) echo '<li class="key key-float"></li>';
                            else echo '<li class="key key-off"></li>';
                            $note--;
                        } ?>
                    </ul>
                </li>
            </ul>
            <?php if(have_rows('infos_complementaires', $bien_id)) : $i = 0; ?>
            <ul class="annonce-complementary complementary">        
                <?php while(have_rows('infos_complementaires', $bien_id)): 
                    the_row(); 
                    if($i < 2) : ?>            
                       <li><?php the_sub_field("informations"); ?></li>
                  <?php endif; 
                $i++;
                endwhile; ?>
            </ul>
            <?php endif; ?> 
            <?php if(is_page('annonces')) : ?>
                <a href="<?php bloginfo('url'); ?>/tableau-de-bord/" class="button btn-secondary">Statistiques</a>
            <?php endif; ?>
            <?php if(is_page('mandats')) : ?>
                <p class="button btn-secondary"><?php echo get_field('bien_disponible') ? 'En ligne' : "En attente"; ?></p>
            <?php endif; ?> 
        </div>
    </div>   

    <div class="grid-col col_size-4 mobile_size-6 col_2">
        <div class="date_demande">
            <p class="titre">Demande effectuée</p>
            <p>Le <?php echo get_comment_date( 'l j F \à g:ia', $comment->comment_ID ); ?></p>
        </div>
        <div class="personne sous_bloc">
            <p class="titre">Personne à contacter</p>
            <p><?php echo $auteur; ?></p>
            <p><?php echo return_tel_french_format($telephone); ?> | <?php echo $email; ?></p>
        </div>
        <div class="creneau sous_bloc">
            <p class="titre">Créneaux pour être contacté</p>
            <p><?php echo $horaire; ?></p>
        </div>
    </div>

    <div class="grid-col col_size-4 mobile_size-6 col_3">        
        <div class="message">
            <p class="titre">Message</p>            
            <p class="first_bloc"><?php echo wami_return_small($comment->comment_content, 100); ?></p>
            <p class="personne sous_bloc"><?php echo $comment->comment_content; ?></p>
        </div>
    </div>   

    <a href="#" class="chevron middle-office-accordion update_rappel_statut" data-rappelid="<?php echo $comment->comment_ID; ?>"></a> 

</li>