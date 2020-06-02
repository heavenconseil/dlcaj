<div class="workshop grid-col col_size-6 mobile_size-12">
    <div class="workshop-cover">
         <?php if(has_post_thumbnail()) : 
            the_post_thumbnail('paysage_reg');           
        ; else : echo '<img  src="http://placehold.it/550x330/0000FF">'; 
        endif; ?>        
    </div>                    
    <h3 class="workshop-title actu"><?php the_title(); ?></h3>    
    <div class="wokshop-description actu"><?php the_content(); ?></div>
</div> 