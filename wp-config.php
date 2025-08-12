<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'kdlnroyghosting_wp367' );

/** Database username */
define( 'DB_USER', 'kdlnroyghosting_wp367' );

/** Database password */
define( 'DB_PASSWORD', '!95SSp02F!' );

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
define( 'AUTH_KEY',         '9dkakuw5kimibkpn7ofmsriho6d6iysb2qzgegfyxrk0l0cnndjiziofe9y7oohv' );
define( 'SECURE_AUTH_KEY',  'dmf4rftwaqv3wzytqbiola6syr8g7kkui9nqqw99mcvxcs46168wjqau1lv4y7rn' );
define( 'LOGGED_IN_KEY',    'jblqygg1cjtrutsrxvpv6n0pbgih1pdweivbwvnibl9z4ukkp9d8hdsaknnrtkbt' );
define( 'NONCE_KEY',        'tsawdzpemjoaw9a0smgahc8jgqcvisjcijcffkqnbg0ghenajqsyrxqtqi3iswu1' );
define( 'AUTH_SALT',        'igml1mvmglw1lffyvn2r8arceboxvowqegnpvdi4zp1dafngtsvqxhfrk7wi6udf' );
define( 'SECURE_AUTH_SALT', 'lpem0x1b1ppoymwvpqodcb6lprjqbcogg7yss6u9y4dfrwgyea8bbotahkv8gzyg' );
define( 'LOGGED_IN_SALT',   'hxwur1hfd22qkstjhfmxue5blx9npmdphjvbutip7rlj9ipbh6lnj64wuewf3ppv' );
define( 'NONCE_SALT',       'aevu0xufblzhvocgnxszzyya6ipa4okdc2cqbrg2i6rdaajp5iu8em74gidskmpv' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp5n_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
