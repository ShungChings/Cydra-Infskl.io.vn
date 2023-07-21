<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'qa CzDFF[P=K4AlL|N&ho^tHhiYU&^I !Gh3.AiXX|!4ZaEGRwMIO5WSx+.R&#F}' );
define( 'SECURE_AUTH_KEY',  'cotZ_%R|vu/|#W,~T=h1@E+E,q8c{C0lXqv(x Znde^ZwBGR;Rh<ILI,6^EA2! -' );
define( 'LOGGED_IN_KEY',    'YsC?h=7vyx%1H5}8SVwQ-GYNv2%2+xBp(.R4]d<$j h-UL%;k<|.^lY X$G~ -^?' );
define( 'NONCE_KEY',        'O;H(Kl6Mb!mzMP`GND+>6Cu=+ )~px8DWS2hCZ!$.yd}H<7MyZIrJ3rb`$RZM{1p' );
define( 'AUTH_SALT',        '?DKD4os.FHzZrKkaE)#UhW;R-0xQkJZwwv>1S*b*dfwqDx|vINXGM}T@AW-FABF+' );
define( 'SECURE_AUTH_SALT', '*q#@G&ZV(<;Pg{2yeZGAPNd[mj_* V[v0M$L)[6UV/2*ITJQHUa*:z0C$R`GhV/1' );
define( 'LOGGED_IN_SALT',   'a,Hib}nsQ`f <OwGyN9r(ta`#WH7 ;Tq)yM,F.4PY<F0=/}1am<yTATeB{xfLh=<' );
define( 'NONCE_SALT',       '26mQEFv.b/+uRzn6[uCkA8NY`uC_~I<86j|XFrC4zwiNQ7dZ9%PSSbriz,dNVQY<' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
