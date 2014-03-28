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
define( 'WPCACHEHOME', '/home/syonserv/public_html/newinsiv/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'syonserv_wrdp8');

/** MySQL database username */
define('DB_USER', 'syonserv_wrdp8');

/** MySQL database password */
define('DB_PASSWORD', 'BiuieA5gJ57MCcM3');

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
define('AUTH_KEY',         's97zf@wOsU4s=i\`:)NyL=cLZSN3<r@$ju/H3DK@;NPsOq62<-wVP@N05cjdbQY=N\`bu8Q>OZu~dOM1!');
define('SECURE_AUTH_KEY',  '');
define('LOGGED_IN_KEY',    'VWWMY*tg_g0JNL$e/ahjXfiKQxr*?27BuMf1XCr7>R\`/KV/oXoKGKG06xCVuc4FS*30Nbdha');
define('NONCE_KEY',        '8dcZ)!5@/oZj=*5P$5bTrDNXG~smLVY^EauEY!<)C8cNhmSv(i$:7<x\`2>pf$CP*2Kd5?5u6Lf');
define('AUTH_SALT',        '#EL=EZE#FI0Z3dweTf>1X\`X:lbu?M~vArqPMetlF-vHhR|5zYkul5X$5-$xN#o)>\`(IGjY#ng2aZNz');
define('SECURE_AUTH_SALT', 'Y-_FB>VKHA~EZH2J#26IKYIIS:GyifdmLEt4Ae0z@3f3tmj=RkqDy:A>~<)6QIVIvP4It|nw');
define('LOGGED_IN_SALT',   'c1;_WLNGFq6@ZUYY@90XG_fahuy^~FM1hMCw0O-2;Z9bnY)e5:WR#Z6>pRF:KY?SH$t?_Q(7hiYDVq');
define('NONCE_SALT',       '$r#(LEF*w>3UTXl;|HFS!8VXJw7DYLsfy0ji7cssN1WKO>nkoAd|MbeN0Ypy$7FZL!SDaxL40qvxyN');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
