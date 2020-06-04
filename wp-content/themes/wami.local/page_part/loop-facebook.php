<div class="grid-col col_size-2 mobile_size-4">
    <article>        
        <div class="facebook-post-cover">
            <a href="<?php echo get_field('link') ? get_field('link') : ''; ?>">
            <?php if(has_post_thumbnail()) : 
                the_post_thumbnail('carre_small');           
            ; else : echo '<img src="http://placehold.it/150x150">'; 
            endif; ?>
            </a> 
        </div>
        <date><?php echo get_the_date('d/m/y'); ?></date>
        <h4><?php $contenu = get_the_title() ? $post->post_title : $post->post_content;
        echo wami_return_small($contenu, 25); ?></h4>

    </article>
</div>
