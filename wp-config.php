<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'e_learning');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'k8Dmc$y;#{`5n^P(z<>,@yE3/kIAWo:pnJUNeueU.2qt?vJw=@d+Xw1vxKOcQ*0a');
define('SECURE_AUTH_KEY',  '?n7zpgsX^Q.J4[XIn#rlDACSbERO|<0gnH$z,+9jQd0< [dyf]8nH&C/Erx^8+lu');
define('LOGGED_IN_KEY',    ')xQFPRjB68K49*R(!n!HvYsJ((L/:~s?0{TA4P$aZnD{5VK!in?!Oc1Pz*y9[B/L');
define('NONCE_KEY',        'c4o?P-WJ2s`@?/{ICi8FfN~& I,OTT xjdqO/h1NgxVSyN.4+.W4Ni1/8YE6^^r|');
define('AUTH_SALT',        '@vd- X{Qgrd[t?P&YU3!~<9KlB;/5Jwi]/GrQ^I1D-lWK4XsdV*}`9Sf`N-HmE/O');
define('SECURE_AUTH_SALT', '7;qB|${7Iv@CRG>7][r)jLN^~M8vZfMo-)%rqNX;`rw7yWgOV4}#4R))~8Nc_nnO');
define('LOGGED_IN_SALT',   ': o8_Cn?0UF]_wt-V`{?nH<r@;*.)/Fo,3w@JnRuiN4CYgQap@(/Un]5,GKHEHs8');
define('NONCE_SALT',       't$($nw^=8LF0#Sjj{s52Vy:>=2c,R,8QG[>R9Ul`XWZjzZIL,KaKV7YMEi-;eAGW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'gm_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
