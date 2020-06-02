<div class="annonce-cover">
    <a href="<?php the_permalink(); ?>">
        <?php if(has_post_thumbnail()) : 
            the_post_thumbnail('paysage_xsmall');           
        ; else : echo '<img src="http://placehold.it/100x70">'; 
        endif; ?>
    </a>
    <span class="annonce-hover-bar"></span>
</div>
<article>
    <h4 class="annonce-title"><a href="<?php the_permalink(); ?>"><?php echo wami_return_small($post->post_title, 48); ?></a></h4> 
    <ul class="annonce-localite">
        <li class="annonce-region"><?php echo wami_get_regiondubien($post->ID); ?></li>
    </ul>
    <ul class="annonce-primary">
        <?php echo get_field('bien_prix_et_honoraires') ? '<li class="annonce-price">'.number_format(get_field('bien_prix_et_honoraires'), 0, '', ' ').' €</li>' : ""; ?> 
        <li class="annonce-city"><?php echo wami_get_villedubien($post->ID); ?></li>       
    </ul>
    <ul class="annonce-secondary">
        <?php echo get_field('bien_surface_habitable') ? '<li class="annonce-metter">'.get_field('bien_surface_habitable').' m²</li>' : ""; ?>
        <?php if(get_field('bien_nb_chambre')) {
            echo '<li class="annonce-bedroom">'.get_field('bien_nb_chambre').' chambre'; 
            if(get_field('bien_nb_chambre')>1) echo 's'; 
            echo '</li>';
        } ?>
        <?php if(get_field('bien_nb_piece_eau')) {
            echo '<li class="annonce-bathroom">'.get_field('bien_nb_piece_eau'); 
            if(get_field('bien_nb_piece_eau')>1) echo ' salles d\'eau</li>'; 
            else echo ' salle d\'eau</li>';
        } ?>
    </ul>
</article>