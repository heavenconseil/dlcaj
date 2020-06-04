<?php if( is_home() ) : ?>

    <article>
        <div class="testimony-cover">
            <?php if(has_post_thumbnail()) the_post_thumbnail('carre_small');           
            else echo '<img src="http://placehold.it/145x145">'; ?>
        </div>
        <header>
            <h4><?php echo get_field('auteur') ? get_field('auteur') : ""; ?> <span><?php echo get_field('ville') ? get_field('ville') : ""; ?></span></h4>
            <h5>" <?php the_title(); ?> "</h5>
        </header>
        <?php the_content(); ?>
    </article>


<?php else : ?>

    <div class="ambassador-cover">
            <?php if(has_post_thumbnail()) the_post_thumbnail('carre_small');           
            else echo '<img src="http://placehold.it/145x145">'; ?> 
        <span class="ambassador-hover-bar"></span>
    </div>
    <article>
         <header>
            <h4><?php echo get_field('auteur') ? get_field('auteur') : ""; ?> <span><?php echo wami_get_villedubien($post->ID); ?></span></h4>
            <h5>" <?php the_title(); ?> "</h5>
        </header>
        <?php the_content(); ?>
    </article>

<?php endif; ?>
