<article>
    <a href="<?php the_permalink(); ?>">
        <div class="thumb-post-cover">
            <?php if(has_post_thumbnail()) : 
                the_post_thumbnail('paysage_xsmall');           
            ; else : echo '<img src="http://placehold.it/100x70">'; 
            endif; ?>
        </div>
        <h4>
            <?php echo wami_get_villedubien($post->ID); ?>
            <span class="annonce-price"><?php echo get_field('bien_prix_et_honoraires') ? number_format(get_field('bien_prix_et_honoraires'), 0, '', ' ').' €' : ""; ?></span>
        </h4>
        <h4>
            <span class="annonce-metter"><?php echo get_field('bien_surface_habitable') ? get_field('bien_surface_habitable').' m²' : ""; ?></span>
            <span class="annonce-bedroom">
                <?php if(get_field('bien_nb_chambre')){
                    echo get_field('bien_nb_chambre').' chambre'; 
                    if(get_field('bien_nb_chambre')>1) echo 's'; 
                } ?>
                
            </span>
        </h4>
    </a>
</article>