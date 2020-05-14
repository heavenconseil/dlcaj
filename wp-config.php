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
define( 'DB_NAME', 'dlcaj_git' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'jmjsA7Xrb[6DtTKHo?G58$*j<zy#gAhD]mou~F=xt0B1GhXkc2zDDP|9|J=M1tA ' );
define( 'SECURE_AUTH_KEY',  'cL6&q(eSTtR>e3*Tg)/]aK`bd/V%K= |$?xB}-Hl%)TK~cl.y; >)W$zjNP)JIdq' );
define( 'LOGGED_IN_KEY',    'QDo&W 5e!o%]dj3aOOAhvD[BA35f.z_2BAV}p+FrN;}*iwC&~m`^>m20l_C,1.5x' );
define( 'NONCE_KEY',        'E`E|b7?/ofJt>yQJfe~oi2FFnAbcwdG7.uy54xt>+.4aIb|,@Qe}V+y{>+rQh>J^' );
define( 'AUTH_SALT',        '}s.8~|6qr;cpIa`3tMq`6ty:Z U5FFq7v|nG|Hb_A9X3Im* j*lN5.:nG6~o|KrD' );
define( 'SECURE_AUTH_SALT', '6bU36&BU;aKRJK6|hu<A8lFv]036zUX?, gc1tHk.KJ,w{bMMKZV!tjRABc/GVG%' );
define( 'LOGGED_IN_SALT',   '/2Z?!ZnIX^DM5t.<k3nz@VlY)tt=s:PlD^BFo[Dyc;S]<`YZnP)B22|){c65?xeb' );
define( 'NONCE_SALT',       'aZT(/3K3?5&@!: H[urFiND$X<hK%:!hN)0Vj_Ns^q2K3J;rr[Nw3)E8#iHs;:}p' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

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
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
