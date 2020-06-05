<div class="workshop grid-col col_size-6 mobile_size-12">
	<a href="<?php the_permalink(); ?>">
	    <div class="workshop-cover">
	         <?php if(has_post_thumbnail()) : 
	            the_post_thumbnail('paysage_reg');           
	        ; 
	        endif; ?>
	    </div>                    
	    <h3><?php the_title(); ?></h3>
	    <div class="wokshop-description"><?php the_excerpt(); ?></div>
	</a>
</div> 