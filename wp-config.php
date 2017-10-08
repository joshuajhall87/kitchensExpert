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
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/kitche20/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'kitche20_wp121');

/** MySQL database username */
define('DB_USER', 'kitche20_wp121');

/** MySQL database password */
define('DB_PASSWORD', '8pgae10S([');

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
define('AUTH_KEY',         'km1ybyyrdymgnqxc949hbmpcmxayumwqt9xi2kuqsfkghjxig1fz2pqp6pj1nhxm');
define('SECURE_AUTH_KEY',  'v0jkxtiu9kiswnqvvoiyqnzesjo13b0stzb4edcph7dgk9fjyjjwqiojza7h74lo');
define('LOGGED_IN_KEY',    '0xxuqh70yv3jzvns3qmik60ds0sboqdfb0ugebo2yajfmvcotowsmfmbqfvev2tw');
define('NONCE_KEY',        'f45wnk2amoxlszj1ixhq8vmyztk76rbnmovdvja2qeo0hzp3hd9axeov3qzhbzxo');
define('AUTH_SALT',        'tkgthqtqdohe5xn21jw3vnyfin4gm9vg3h4nxf03euwwscifp6p1pytxyyd3vy6u');
define('SECURE_AUTH_SALT', 'wshoysgkvw3lxs98vdl6zvmxlvrkfoqxzh2f8qibu1cew3dstywqjlq11wow54wh');
define('LOGGED_IN_SALT',   'ghq2rvmtdfeusq6dklsvzbvfeeisfdab54bjc3l62xhwwdvhnezdyufzizrgzdmv');
define('NONCE_SALT',       'yuqh5dtoyzvvwjx6d2myf5pxizuu4fnuk1xlrvshmwb3mxl8epiyun4h1h3cikjw');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpoa_';

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
define( 'WP_MEMORY_LIMIT', '128M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );
