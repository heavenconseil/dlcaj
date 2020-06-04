
    <?php if( !is_single() && !is_page_template('tpl-devenirambassadeur.php') ) : ?>
	    <section id="facebook-posts">
	        <div class="w_grid limited-content">			
	            <?php $args = array(
	            	'post_type'			=> 'socialpost',
	            	'post_status'		=> 'publish',				            	
	            	'posts_per_page'	=> 6
	            );
	            $query_social = new WP_Query( $args );
	            if($query_social->have_posts()) : ?>
		            <div class="grid-col col_size-12">	            	
		                <h3 class="title-button">Facebook <?php if(get_field('lien_facebook', 'option')) echo '<a href="'.get_field('lien_facebook', 'option').'" class="button btn-secondary btn-to-right">Tous les posts</a>'; ?></h3>
		            </div>
		            <ul class="facebook-post-list">
			            <?php while($query_social->have_posts()) :
			            $query_social->the_post(); ?>
				            <li><?php get_template_part('page_part/loop', 'facebook'); ?></li>
					    <?php endwhile; ?>
					</ul>
			    <?php wp_reset_postdata(); ?>
	       		<?php endif; ?>
		    </div>   
	    </section>
	<?php endif; ?>
	


    <footer id="footer">
    
	    <div class="bg-overlay"></div>
	    <div class="w_grid limited-content">
	        <div class="grid-col col_size-3 tablet_size-6 mobile_size-12">
	            <div class="footer-col">
	                <h3><?php bloginfo('name'); ?></h3>
	                <?php if(get_field('adresse', 'option')) echo '<adress>'.get_field('adresse', 'option').'</adress>'; ?>
	                <ul class="rs-footer">
		                <?php if(get_field('lien_facebook', 'option')) echo '<li><a href="'.get_field('lien_facebook', 'option').'" class="rs-item rs-fb"><span class="fa fa-facebook"></span></a></li>'; ?>
		                <?php if(get_field('lien_twitter', 'option')) echo '<li><a href="'.get_field('lien_twitter', 'option').'" class="rs-item rs-twitter"><span class="fa fa-twitter"></span></a></li>'; ?>	
		                <?php if(get_field('lien_linkedin', 'option')) echo '<li><a href="'.get_field('lien_linkedin', 'option').'" class="rs-item rs-linkedin"><span class="fa fa-linkedin"></span></a></li>'; ?>  
		                <?php if(get_field('lien_pinterest', 'option')) echo '<li><a href="'.get_field('lien_pinterest', 'option').'" class="rs-item rs-pinterest"><span class="fa fa-pinterest"></span></a></li>'; ?>
		                <?php if(get_field('lien_instagram', 'option')) echo '<li><a href="'.get_field('lien_instagram', 'option').'" class="rs-item rs-instagram"><span class="fa fa-instagram"></span></a></li>'; ?>  
	                </ul>
	                <a href="<?php echo wami_get_page_link('faq'); ?>" class="footer-link">FAQ</a>
	            </div>
	        </div>
	        <div class="grid-col col_size-3 tablet_size-6 mobile_size-12">
	            <div class="footer-col col-links">
	            	<?php $menuParameters = array(
	                    'theme_location'  => 'menu-footer1',
						'container'       => false,
						'echo'            => false,
						'items_wrap'      => '%3$s',
						'depth'           => 0,
	                ); 
	                echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' ); ?> 
	            </div>
	        </div>
	        <div class="grid-col col_size-3 tablet_size-6 mobile_size-12">
	            <div class="footer-col col-links">
	            <?php $menuParameters = array(
	                    'theme_location'  => 'menu-footer2',
						'container'       => false,
						'echo'            => false,
						'items_wrap'      => '%3$s',
						'depth'           => 0,
	                ); 
	                echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' ); ?> 
	            </div>
	        </div>
	        <div class="grid-col col_size-3 tablet_size-6 mobile_size-12">
	            <div class="footer-col">
	                <h4>Demande d'informations</h4>
	                <a href="<?php echo wami_get_page_link('59'); ?>" class="button btn-secondary">Contact</a>
	                <?php $presentation = get_field('presentation_pdf', 'option'); 
	                if($presentation) : ?>
	                	<a href="<?php echo $presentation['url']; ?>" class="button btn-accent ico-right arrow-download">Notre présentation</a>
	                <?php endif; ?>
	            </div>
	        </div>
	    </div>
       
    </footer>

    <div class="loader">
    	<i class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></i>
    	<!-- <img src="<?php echo get_template_directory_uri(); ?>/lib/img/ajax-loader.svg" /> -->
    </div>

    <?php wp_footer(); ?>
</body>
</html>
