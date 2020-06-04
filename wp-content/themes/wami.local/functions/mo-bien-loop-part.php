<?php

function wami_return_bien_proprietaire($post){
	?><p>Vendeur :
        <?php if(have_rows('proprietaires', $post->ID)) : ?>
            <?php while(have_rows('proprietaires', $post->ID)): the_row(); ?>            
                <span><?php echo get_sub_field('proprietaire_nom') ? get_sub_field('proprietaire_nom') : ""; ?> </span>
            <?php endwhile; ?>
        <?php endif; ?>   
    </p><?php 
}

function wami_return_bien_annonce_mo($post, $annonce_cover=1){
	?>
	<?php if($annonce_cover) : ?>
		<div class="annonce-cover">
		    <a href="<?php echo get_permalink($post->ID); ?>">
		        <?php if(has_post_thumbnail($post->ID)) : 
		            echo get_the_post_thumbnail($post->ID, 'paysage_medium');           
		        ; else : echo '<img src="http://placehold.it/350x230">'; 
		        endif; ?>
		        <?php if(get_field("lien_de_la_visite_360", $post->ID) || get_field("iframe_de_la_visite_360", $post->ID)) : ?>
		            <!-- <a href="<?php echo get_field("lien_de_la_visite_360"); ?>"> -->
		                <span class="cover-label label-360">visiter</span>
		            <!-- </a> -->
		        <?php endif; ?>
		    </a>
		    <span class="annonce-hover-bar"></span>
		</div> 
	<?php endif; ?>
	<h4 class="annonce-title"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo wami_return_small($post->post_title, 48); ?></a></h4>
	<ul class="annonce-primary">
	    <?php echo get_field('bien_prix_et_honoraires', $post->ID) ? '<li class="annonce-price">'.number_format(get_field('bien_prix_et_honoraires', $post->ID), 0, '', ' ').' €</li>' : ""; ?>
	    <li class="annonce-city"><?php echo wami_get_villedubien($post->ID); ?></li>
	</ul>	
	<?php $type_residence = get_field('bien_type_de_residence', $post->ID);
		echo ($type_residence && is_array($type_residence)) ? '<p>'.$type_residence['label'].'</p>' : ""; ?>
	<ul class="annonce-secondary">
	    <?php echo get_field('bien_surface_habitable', $post->ID) ? '<li class="annonce-metter">'.get_field('bien_surface_habitable', $post->ID).' m²</li>' : ""; ?>
	    <?php echo get_field('bien_nb_chambre', $post->ID) ? '<li class="annonce-bedroom">'.get_field('bien_nb_chambre', $post->ID).' chambre(s)</li>' : ""; ?>
	    <?php echo get_field('bien_nb_piece_eau', $post->ID) ? '<li class="annonce-bathroom">'.get_field('bien_nb_piece_eau', $post->ID).'  salle(s) d\'eau</li>' : ""; ?>
	</ul>
	<ul class="annonce-complementary">
	    <li class="annonce-environnement">
	        <span class="label">Environnement</span>
	        <ul class="star-notation">
	            <?php $note = wami_cacul_environnement_du_bien($post->ID);
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
	        <ul class="key-notation"
	            <?php $note = wami_cacul_charme_du_bien($post->ID);
	            for( $i=1; $i<6; $i++) {
	                if($note>=1) echo '<li class="key key-on"></li>';
	                else if($note>0) echo '<li class="key key-float"></li>';
	                else echo '<li class="key key-off"></li>';
	                $note--;
	            } ?>
	        </ul>
	    </li>
	</ul>
	<?php if(have_rows('infos_complementaires', $post->ID)) : $i = 0; ?>
	<ul class="annonce-complementary complementary">        
	    <?php while(have_rows('infos_complementaires', $post->ID)): 
	        the_row(); 
	        if($i < 2) : ?>            
	           <li><?php the_sub_field("informations"); ?></li>
	      <?php endif; 
	    $i++;
	    endwhile; ?>
	</ul>
	<?php endif; 
}

function wami_return_bien_presentation_mo($post, $display_content=1){
	?><div class="presentation_bien">
        <div class="adresse">
            <p><?php echo get_field('field_5975f83884133', $post->ID) ? get_field('field_5975f83884133', $post->ID) : ""; ?></p>
            <p>
                <?php echo get_field('field_597601ad2fc38', $post->ID) ? get_field('field_597601ad2fc38', $post->ID) : ""; ?>
                <?php echo get_field('field_597601ba2fc39', $post->ID) ? get_field('field_597601ba2fc39', $post->ID) : ""; ?>
            </p>
        </div>        
        <?php if($display_content && $post->post_content!= "") {
        	echo '<div class="extrait">'.wami_return_small($post->post_content, 750).'</div><span class="lire_la_suite"></span>';
        	?><div class="full_text"><?php the_content(); ?></div><?php
        }; ?>
    </div><?php
}