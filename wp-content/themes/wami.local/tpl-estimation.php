<?php
/*
Template Name: TPL Estimation
*/
?>
<?php get_template_part('header-part', 'head'); ?>

	<?php while(have_posts()): the_post(); ?>
		<?php
		$logo 		= get_field("estimation_logo");
	    $image_bg 	= get_field("estimation_image_bg");
	    $style 		= $image_bg && is_array($image_bg) ? "background: transparent url(".$image_bg['url'].") center center no-repeat;" : "background:#ccc"; ?>

		<div class="tpl-estimation" style="<?php echo $style; ?>">
			<section id="contact-form">
				<div class="w_grid limited-content">
					<div class="grid-col col_size-12 centered">

							<?php if($logo && is_array($logo)) : ?>
								<div class="logo">
	            		<img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" title="<?php echo $logo['title']; ?>" />
	            	</div>
		            	<?php endif; ?>

		            	<div class="intro">
		            		<h1><?php the_field('estimation_titre'); ?></h1>
		            		<p><?php the_field('estimation_intro'); ?></p>
										<div class="intro__button-wrapper">
		            			<a class="intro__button"><?php the_field('estimation_bouton'); ?></a>
										</div>
		            	</div>

		            	<div class="formulaire_estimation" data-url="<?php echo get_the_permalink(); ?>">
		            		<?php
		            		$shortcode = get_field('estimation_shortcode');
		            		if($shortcode) echo do_shortcode($shortcode);
		            		?>
		            	</div>
								</div>
	            </div>
			</section>
		</div>

	<?php endwhile; ?>

    <?php wp_footer(); ?>
</body>
</html>
