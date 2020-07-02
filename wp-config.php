<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'delacoursaujardin');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '.2&H$lrp4`a^HsN8r@AVlYzCf`OO;K}nJ*bZho-;B%CIv>7`h@BGwZhBU?H/k+W:');
define('SECURE_AUTH_KEY',  'WN^SHFrt@>_F<c2&vyX3s+X#52+hfqRxh)K<*Su(ARZ2eSWqu}lt k|uBo4f3.-}');
define('LOGGED_IN_KEY',    '8m|h}kE1bG7Fn$S[`YQf>~VQruD9iwHhU!Jy?Rz^r!cS;bB>wOMy%Y~9^rHA2eKy');
define('NONCE_KEY',        'ti3|rfK-Wl4qCn*zGnV(h<7,_gUc8p|KWy[X#?$_v zts:srxoh vU |].g[UihQ');
define('AUTH_SALT',        '_?N9XV!vw@hSY!qJi|Wy0Y|#.Y)%C,~.|.X%M[6{=~H]-cGkZ6[pk#9acrP!rbbZ');
define('SECURE_AUTH_SALT', 'h#T#Gk*wm$f~1)n9z%cXC;EP6P6D_vWeOG4Ai8!MuxA1:_vUjX:ii~;J4OxgUZcH');
define('LOGGED_IN_SALT',   '^s1{r>}pK@1#qU~t/q#}d}/.`jr^B/ikAdNmc>&VYjA.a;xxM.97.DYo/3UsS_l=');
define('NONCE_SALT',       '15?3G!6`brIIv@Y<}VKrwjjX)d(kJbCk]4USYygMN*aNBE:RF#=i96&u7]}AtIT`');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');


/* Add wami for google api key */
define('GOOGLE_API_KEY', 'AIzaSyAg7Gz9DDNXsqwzDlvJKOC-hc7cOJTNivI'); // local
//define('GOOGLE_API_KEY', 'AIzaSyB1WS3VkEJmZJlQS6Uz-msTEShNWfT3Lq4'); // Prod
