<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
//define( 'WPCACHEHOME', '/home/syonserv/public_html/newinsiv/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
//define('DB_NAME', 'syonserv_wrdp8');
define('DB_NAME', 'insivc5_insiv');

/** MySQL database username */
//define('DB_USER', 'syonserv_wrdp8');
define('DB_USER', 'insivc5_myinsiv');

/** MySQL database password */
//define('DB_PASSWORD', 'BiuieA5gJ57MCcM3');
define('DB_PASSWORD', 'wU4@hC(zBvWT');


/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'DNnMp1|^]+|}{XP{JsU<Z@fJ%q|l 9n(i:D-Rjdzqp>NW?h22rgzB$Hn |,Pw~Sg');
define('SECURE_AUTH_KEY',  'T)yStQw`[1zUr>.}ptBX*vxCUyPVf#3L||cPJ+Kc:q-_xhEC!Y4Fb/m3+`,`u>#R');
define('LOGGED_IN_KEY',    'hdU?|vQd1q}g`dom1P4i*3~[#Ft-:O]>kpB.WT/zZMA1#gm=Wi!x6@j|1!&:e(ji');
define('NONCE_KEY',        'z;=+4Vn:`Vt4f_6B/ILG+<p`o+>Tb1+%Q#X!!$rC`)?!k8S[hU=b5r-8$?Yu3m-/');
define('AUTH_SALT',        '%7gHDnxCm|--Nj:tmH-ggV<]%jI-!2W),,m!Y{`uE]QoiX%kPzqv%G6+)(Me86Ec');
define('SECURE_AUTH_SALT', ']^7[<)h;+s)RoTX{Srd8|Tvp%HG/Nv7/)q:u%m=%!|brsXZigvIkM5J0Ygw-AyFt');
define('LOGGED_IN_SALT',   'bl|n:KXG~l^M;D*zv?ZQ_2Rg,Ki8xs5~;++F1?#iP>)A>A40MFHPfZu7>;NFa6YI');
define('NONCE_SALT',       'oN?S,20odbSE>T(kTP4y>;~z)Vtz1>g!XTDOUM/DFYCiaZ|PoHhk>O%K_PbOhU=5');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
//$table_prefix  = 'wp_';
$table_prefix  = 'in_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
