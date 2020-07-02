<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta property="fb:pages" content="218501478179476">
  <meta property="og:type" content="website" />
	<meta name="viewport" content="width=device-width">
	<title>
		<?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title( '', true, 'right' ); ?>
	</title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="Shortcut Icon" href="<?php echo get_template_directory_uri();?>/favicon.ico">
	<link rel="icon" href="<?php echo get_template_directory_uri();?>/favicon.ico" type="image/x-icon">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>

	<!-- Google -->
	<script type='text/javascript'>
    var googletag = googletag || {};
    googletag.cmd = googletag.cmd || [];
    (function() {
      var gads = document.createElement('script');
      gads.async = true;
      gads.type = 'text/javascript';
      var useSSL = 'https:' == document.location.protocol;
      gads.src = (useSSL ? 'https:' : 'http:') +
        '//www.googletagservices.com/tag/js/gpt.js';
      var node = document.getElementsByTagName('script')[0];
      node.parentNode.insertBefore(gads, node);
    })();
  </script>

  <script type='text/javascript'>
    googletag.cmd.push(function() {
      googletag.defineSlot('/19345425/bannieres_mobile', [320, 50], 'div-gpt-ad-1434016998860-0').addService(googletag.pubads());
      googletag.defineSlot('/19345425/interstitiel_mobile', [320, 480], 'div-gpt-ad-1434016998860-1').addService(googletag.pubads());
      googletag.pubads().enableSingleRequest();
      googletag.enableServices();
    });
  </script>
  
  <script src='//ww62.smartadserver.com/config.js?nwid=62' type="text/javascript"></script>
  <script type="text/javascript">
      sas.setup({ domain: '//ww62.smartadserver.com'});
  </script>

  <script type='text/javascript' src='//static.criteo.net/js/ld/publishertag.js'></script>
  <script type='text/javascript'>
     function crto_ShorterThan(widthMax){return screen.width < widthMax;}
     var LimitWidth = 728;
  </script>

	<?php echo $habillage_header; ?>
	
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  
  <!--script type="text/javascript">
    window._taboola = window._taboola || [];
    _taboola.push({article:'auto'});
    !function (e, f, u, i) {
      if (!document.getElementById(i)){
        e.async = 1;
        e.src = u;
        e.id = i;
        f.parentNode.insertBefore(e, f);
      }
    }(document.createElement('script'),
    document.getElementsByTagName('script')[0],
    '//cdn.taboola.com/libtrc/pariszigzag/loader.js',
    'tb_loader_script');
  </script--> 
  
  <?php /*
  <script type="text/javascript">
  (function(){var a=document.createElement("script"),b=document.getElementsByTagName("script")[0];a.src="//b6bi07yc62.s.ad6media.fr/?d="+(new Date).getTime()+"&r=";try{a.src+=encodeURIComponent(top.document.referrer)}catch(c){a.src+=encodeURIComponent(document.referrer)}a.type="text/javascript";a.async=!0;b.parentNode.insertBefore(a,b)})();
  </script>
  */ ?>
  <?php if(is_home()): ?><meta property="og:image" content="website" /><?php endif; ?>
  <meta name="google-site-verification" content="Ng500z7zPfYbE4Xetdfej93xv2h0ZxcWMDjDxBNIYWw" />
</head>
	
<?php global $habillage_query; ?>

<body class="<?php echo ($has_mini_pub)? 'has-mini-pub' : null?>" >
<!--oncontextmenu="return false" -->

	<?php if ($has_mini_pub): ?>
		
		<div id="pub-mini">
			<div class="pub-mini-content">
			<?php echo $pub_mini; ?>
			</div>
		</div>
		
	<?php endif ?>
	
	<?php if ($has_big_pub): ?>
				
		<div id="pub-big">
			<div class="pub-big-contener">
				
				<div class="to-close-big-pub"></div>

				<div class="pub-big-content">
				<?php echo $pub_big; ?>
				</div>
			</div>
		</div>

	<?php endif ?>

	<?php if ($habillage_query && $habillage_query->have_posts()) :
			
			$has_habillage = 'has-habillage';

			while ($habillage_query->have_posts()) :
				$habillage_query->the_post();

				$habillage_top = get_field('habillage_code_top');
				if(!empty($habillage_top)){

					?><div id="habillage-header">
						<?php echo $habillage_top ?>
					</div><?php

				}
				$habillage_right = get_field('habillage_code_right');
				if(!empty($habillage_right)){

					?><div id="habillage-right">

						<?php echo $habillage_right ?>
					</div><?php

				}
				$habillage_left = get_field('habillage_code_left');
				if(!empty($habillage_left)){

					?><div id="habillage-left">
						<?php echo $habillage_left ?>
					</div><?php

				}

			endwhile;
      wp_reset_postdata();

		endif; ?>