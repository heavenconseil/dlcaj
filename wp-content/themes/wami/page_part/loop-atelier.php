<div class="workshop grid-col col_size-6 mobile_size-12 annonce_bien_trouve">
    <div class="workshop-cover">
         <?php if(has_post_thumbnail()) : 
            the_post_thumbnail('paysage_reg');           
        ; else : echo '<img  src="http://placehold.it/550x330/0000FF">'; 
        endif; ?>
        <?php if(get_field('organisateur_mail')) echo '<span class="label"><a href="mailto:'.get_field('organisateur_mail').'">nous contacter</a></span>'; ?>
    </div>                    
    <h3 class="workshop-title"><?php the_title(); ?></h3>
    <ul class="workshop-info-primary">
        <?php echo get_field('date_rdv') ? '<li>'.get_field('date_rdv').'</li>' : ""; ?>
        <li><?php echo wami_get_villedubien($post->ID); ?></li>
    </ul>
    <ul class="workshop-info-secondary">
        <?php echo get_field('organisateur') ? '<li>'.get_field('organisateur').'</li>' : ""; ?>
        <?php echo get_field('organisateur_mail') ? '<li>'.get_field('organisateur_mail').'</li>' : ""; ?>
        <?php echo get_field('organisateur_telephone') ? '<li>'.return_tel_french_format(get_field('organisateur_telephone')).'</li>' : ""; ?>
    </ul>                    
    <div class="wokshop-description"><?php the_content(); ?></div>
</div> 