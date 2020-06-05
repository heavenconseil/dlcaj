 <div class="article">
    <div class="article-cover">
        <a href="<?php the_permalink(); ?>">
            <?php if(has_post_thumbnail()) : 
                the_post_thumbnail('portrait_small');           
            ; else : echo ' <img  src="http://placehold.it/150x190/0000FF">'; 
            endif; ?>         
        </a>
        <span class="label"><a href="<?php the_permalink(); ?>">lire plus</a></span>
    </div>
    <div class="article-text">
        <h4><?php the_title(); ?> <span><?php echo get_field('date_rdv') ? get_field('date_rdv') : ""; ?></span></h4>
        <p><?php echo wami_return_extrait($post->ID, 150); ?></p>
    </div>
</div>