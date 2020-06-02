<?php
/*
Template Name: TPL Contact
*/
?>
<?php get_header(); ?>

	<div class="tpl-annonces-list">
		<section id="contact-form">
		<?php while(have_posts()): the_post(); ?>

			 <div class="w_grid limited-content">
            	
            	<h1><?php the_title(); ?></h1>
                 
                 <!-- <a href="#" class="button btn-secondary">Appeler De la cour au jardin</a> -->
            	
            	<?php the_content(); ?>

            	<div id="direct-contact">            
	                <div class="grid-col col_size-12">
	                    <h2>Contacts directs</h2>
	                </div>
	                <div class="grid-col col_size-3 mobile_size-12">
	                    <div class="contact-avatar">
	                    	<?php $image = get_field('image_de_contact_direct', 'option');
	                    	if($image) : 
				                echo '<img src="'.$image['sizes']['carre_medium'].'">';              
				            ; else : echo '<img  src="http://placehold.it/300x300/0000FF">'; 
				            endif; ?>
	                    </div>
	                </div>
	                <div class="grid-col col_size-3 margin-1 mobile_size-12">
	                    <div class="direct-contact-container">
	                        <h4>Adresse</h4>
	                        <p><?php echo get_field('contact_adresse', 'option') ? get_field('contact_adresse', 'option') : ""; ?></p>
	                    </div>
	                </div>
	                <div class="grid-col col_size-4 margin-1 mobile_size-12">
	                    <div class="direct-contact-container">
	                        <h4>Relation presse</h4>
	                        <p><?php echo get_field('relation_presse', 'option') ? get_field('relation_presse', 'option') : ""; ?></p>
	                    </div>
	                    <div class="direct-contact-container">
	                        <h4>Consultant handicap</h4>
	                        <p><?php echo get_field('consultant_handicap', 'option') ? get_field('consultant_handicap', 'option') : ""; ?></p>
	                    </div>
	                </div>	                
	            </div>

            </div>				
				
		<?php endwhile; ?>
		</section>
	</div>

<?php get_footer(); ?>