<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<title><?php wp_title('|'); ?></title>
    <?php wp_head(); ?>
        
	<!-- Google Analytics -->
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-108634779-1', 'auto');
	ga('send', 'pageview');
	</script>
	<!-- End Google Analytics -->
</head>

<body <?php body_class(); ?> <?php wami_getBrowser(); ?>>