<?php

/* Déclaration des menus du thème */
add_action('init', 'wami_theme_menu');
function wami_theme_menu(){
	register_nav_menu('menu-header', 'Menu entête');
	register_nav_menu('menu-footer1', 'Menu pied de page 1');	
	register_nav_menu('menu-footer2', 'Menu pied de page 2');

	register_nav_menu('menu-sitemap1', 'Plan du site (col 1)');
	register_nav_menu('menu-sitemap2', 'Plan du site (col 2)');	
	register_nav_menu('menu-sitemap3', 'Plan du site (col 3)');


	register_nav_menu('menu-header-middle-office1', 'Menu entête du middle office (ligne 1)');
	register_nav_menu('menu-header-middle-office2', 'Menu entête du middle office (ligne 2)');
	register_nav_menu('menu-header-middle-office2-resp-region', 'Menu entête du middle office pour Ambassadeur de région (ligne 2)');
}