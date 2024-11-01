<?php
/**
 * Plugin Name: WP Awesome Announcements
 * Plugin URI:  https://jeweltheme.com
 * Description: Best WordPress Announcements Plugin integrated with Custom Post Type. WP Awesome Announcements based on latest jQuery UI
 * Version:     2.0.5
 * Author:      Jewel Theme
 * Author URI:  https://jeweltheme.com
 * Text Domain: wp-awesome-announcements
 * Domain Path: languages/
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package wp-awesome-announcements
 */

/*
 * don't call the file directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( esc_html__( 'You can\'t access this page', 'wp-awesome-announcements' ) );
}

$jltwann_plugin_data = get_file_data(
	__FILE__,
	array(
		'Version'     => 'Version',
		'Plugin Name' => 'Plugin Name',
		'Author'      => 'Author',
		'Description' => 'Description',
		'Plugin URI'  => 'Plugin URI',
	),
	false
);

// Define Constants.
if ( ! defined( 'JLTWANN' ) ) {
	define( 'JLTWANN', $jltwann_plugin_data['Plugin Name'] );
}

if ( ! defined( 'JLTWANN_VER' ) ) {
	define( 'JLTWANN_VER', $jltwann_plugin_data['Version'] );
}

if ( ! defined( 'JLTWANN_AUTHOR' ) ) {
	define( 'JLTWANN_AUTHOR', $jltwann_plugin_data['Author'] );
}

if ( ! defined( 'JLTWANN_DESC' ) ) {
	define( 'JLTWANN_DESC', $jltwann_plugin_data['Author'] );
}

if ( ! defined( 'JLTWANN_URI' ) ) {
	define( 'JLTWANN_URI', $jltwann_plugin_data['Plugin URI'] );
}

if ( ! defined( 'JLTWANN_DIR' ) ) {
	define( 'JLTWANN_DIR', __DIR__ );
}

if ( ! defined( 'JLTWANN_FILE' ) ) {
	define( 'JLTWANN_FILE', __FILE__ );
}

if ( ! defined( 'JLTWANN_SLUG' ) ) {
	define( 'JLTWANN_SLUG', dirname( plugin_basename( __FILE__ ) ) );
}

if ( ! defined( 'JLTWANN_BASE' ) ) {
	define( 'JLTWANN_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'JLTWANN_PATH' ) ) {
	define( 'JLTWANN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'JLTWANN_URL' ) ) {
	define( 'JLTWANN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
}

if ( ! defined( 'JLTWANN_INC' ) ) {
	define( 'JLTWANN_INC', JLTWANN_PATH . '/Inc/' );
}

if ( ! defined( 'JLTWANN_LIBS' ) ) {
	define( 'JLTWANN_LIBS', JLTWANN_PATH . 'Libs' );
}

if ( ! defined( 'JLTWANN_ASSETS' ) ) {
	define( 'JLTWANN_ASSETS', JLTWANN_URL . 'assets/' );
}

if ( ! defined( 'JLTWANN_IMAGES' ) ) {
	define( 'JLTWANN_IMAGES', JLTWANN_ASSETS . 'images' );
}

if ( ! class_exists( '\\JLTWANN\\JLT_Awesome_Announcement' ) ) {
	// Autoload Files.
	include_once JLTWANN_DIR . '/vendor/autoload.php';
	// Instantiate JLT_Awesome_Announcement Class.
	include_once JLTWANN_DIR . '/class-wp-awesome-announcements.php';
}