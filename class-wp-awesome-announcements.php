<?php
namespace JLTWANN;

use JLTWANN\Libs\Assets;
use JLTWANN\Libs\Helper;
use JLTWANN\Libs\Featured;
use JLTWANN\Inc\Classes\Recommended_Plugins;
use JLTWANN\Inc\Classes\Notifications\Notifications;
use JLTWANN\Inc\Classes\Pro_Upgrade;
use JLTWANN\Inc\Classes\Row_Links;
use JLTWANN\Inc\Classes\Upgrade_Plugin;
use JLTWANN\Inc\Classes\Feedback;

/**
 * Main Class
 *
 * @wp-awesome-announcements
 * Jewel Theme <support@jeweltheme.com>
 * @version     2.0.4
 */

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JLT_Awesome_Announcement Class
 */
if ( ! class_exists( '\JLTWANN\JLT_Awesome_Announcement' ) ) {

	/**
	 * Class: JLT_Awesome_Announcement
	 */
	final class JLT_Awesome_Announcement {

		const VERSION            = JLTWANN_VER;
		private static $instance = null;

		/**
		 * what we collect construct method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			$this->includes();
			add_action( 'plugins_loaded', array( $this, 'jltwann_plugins_loaded' ), 999 );
			// Body Class.
			add_filter( 'admin_body_class', array( $this, 'jltwann_body_class' ) );
			// This should run earlier .
			// add_action( 'plugins_loaded', [ $this, 'jltwann_maybe_run_upgrades' ], -100 ); .

			add_action('init', array( $this, 'jltwann_register_announcements' ) );
			add_action( 'add_meta_boxes', array( $this, 'jltwann_add_metabox' ) );
			add_action( 'save_post', array( $this, 'jltwann_metabox_save' ) );
		}

		/**
		 * plugins_loaded method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwann_plugins_loaded() {
			$this->jltwann_activate();
		}

		/**
		 * Version Key
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function plugin_version_key() {
			return Helper::jltwann_slug_cleanup() . '_version';
		}


		/**
		 * Create Custom Post Type
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */		
		function jltwann_register_announcements() {

			$labels = array(
				'name' => _x( 'Announcements', 'post type general name' ),
				'singular_name' => _x( 'Announcement', 'post type singular name' ),
				'add_new' => _x( 'Add New', 'Announcement' ),
				'add_new_item' => __( 'Add New Announcement' ),
				'edit_item' => __( 'Edit Announcement' ),
				'new_item' => __( 'New Announcement' ),
				'view_item' => __( 'View Announcement' ),
				'search_items' => __( 'Search Announcements' ),
				'not_found' =>  __( 'No Announcements found' ),
				'not_found_in_trash' => __( 'No Announcements found in Trash' ),
				'parent_item_colon' => ''
			);

		 	$args = array(
		     	'labels' => $labels,
		     	'singular_label' => __('Announcement', 'simple-announcements'),
		     	'public' => true,
			  	'capability_type' => 'post',
		     	'rewrite' => false,
		     	'supports' => array('title', 'editor'),
		     );
		 	register_post_type('announcements', $args);
		}



		//Create meta box
		function jltwann_add_metabox() {
			add_meta_box( 'jeweltheme_metabox_id', 'Scheduling', array( $this, 'jltwann_metabox' ), 'announcements', 'side', 'high' );
		}


		//Add fields to meta box
		function jltwann_metabox( $post ) {
			$values = get_post_custom( $post->ID );
			$start_date = isset( $values['jeweltheme_start_date'] ) ? esc_attr( $values['jeweltheme_start_date'][0] ) : '';
			$end_date = isset( $values['jeweltheme_end_date'] ) ? esc_attr( $values['jeweltheme_end_date'][0] ) : '';
			wp_nonce_field( 'jeweltheme_metabox_nonce', 'metabox_nonce' );
			?>
				<p>
					<label for="start_date">Start date</label>
					<input type="text" name="jeweltheme_start_date" id="jeweltheme_start_date" value="<?php echo $start_date; ?>" />
				</p>
				<p>
					<label for="end_date">End date</label>
					<input type="text" name="jeweltheme_end_date" id="jeweltheme_end_date" value="<?php echo $end_date; ?>" />
				</p>
			<?php
		}




		//Validate & save meta box data
		public function jltwann_metabox_save( $post_id ) {
	
		    // Make sure data is set
			if( isset( $_POST['jeweltheme_start_date'] ) ) {
		        
		        $valid = 0;
		        $old_value = get_post_meta($post_id, 'jeweltheme_start_date', true);
		        
		        if( $_POST['jeweltheme_start_date'] != '' ){

		            $date = $_POST['jeweltheme_start_date'];
		            $date = explode( '-', (string) $date );
		            $valid = checkdate($date[1],$date[2],$date[0]);
		        }
		        
		        if($valid)
		            update_post_meta( $post_id, 'jeweltheme_start_date', $_POST['jeweltheme_start_date'] );
		        elseif (!$valid && $old_value)
		            update_post_meta( $post_id, 'jeweltheme_start_date', $old_value );
		        else
		            update_post_meta( $post_id, 'jeweltheme_start_date', '');
		    }
				
			if( !empty( $_POST['jeweltheme_end_date'] ) ) {

	            $old_value = get_post_meta($post_id, 'jeweltheme_end_date', true);
	            
	            $date = $_POST['jeweltheme_end_date'];
	            $date = explode( '-', (string) $date );
	            $valid = checkdate($date[1],$date[2],$date[0]);

		        if($valid){
		            update_post_meta( $post_id, 'jeweltheme_end_date', $_POST['jeweltheme_end_date'] );
		        } elseif (!$valid && $old_value){
		            update_post_meta( $post_id, 'jeweltheme_end_date', $old_value );
		        } else{
		            update_post_meta( $post_id, 'jeweltheme_end_date', '');
		        }
		    }
		}


		/**
		 * Activation Hook
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function jltwann_activate() {
			$current_jltwann_version = get_option( self::plugin_version_key(), null );

			if ( get_option( 'jltwann_activation_time' ) === false ) {
				update_option( 'jltwann_activation_time', strtotime( 'now' ) );
			}

			if ( is_null( $current_jltwann_version ) ) {
				update_option( self::plugin_version_key(), self::VERSION );
			}

			$allowed = get_option( Helper::jltwann_slug_cleanup() . '_allow_tracking', 'no' );

			// if it wasn't allowed before, do nothing .
			if ( 'yes' !== $allowed ) {
				return;
			}
			// re-schedule and delete the last sent time so we could force send again .
			$hook_name = Helper::jltwann_slug_cleanup() . '_tracker_send_event';
			if ( ! wp_next_scheduled( $hook_name ) ) {
				wp_schedule_event( time(), 'weekly', $hook_name );
			}
		}


		/**
		 * Add Body Class
		 *
		 * @param [type] $classes .
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwann_body_class( $classes ) {
			$classes .= ' wp-awesome-announcements ';
			return $classes;
		}

		/**
		 * Run Upgrader Class
		 *
		 * @return void
		 */
		public function jltwann_maybe_run_upgrades() {
			if ( ! is_admin() && ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Run Upgrader .
			$upgrade = new Upgrade_Plugin();

			// Need to work on Upgrade Class .
			if ( $upgrade->if_updates_available() ) {
				$upgrade->run_updates();
			}
		}

		/**
		 * Include methods
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function includes() {
			new Assets();
			new Recommended_Plugins();
			new Row_Links();
			new Pro_Upgrade();
			new Notifications();
			new Featured();
			new Feedback();
		}


		/**
		 * Initialization
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwann_init() {
			$this->jltwann_load_textdomain();
		}


		/**
		 * Text Domain
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwann_load_textdomain() {
			$domain = 'wp-awesome-announcements';
			$locale = apply_filters( 'jltwann_plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, false, dirname( JLTWANN_BASE ) . '/languages/' );
		}
		
		
		

		/**
		 * Returns the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof JLT_Awesome_Announcement ) ) {
				self::$instance = new JLT_Awesome_Announcement();
				self::$instance->jltwann_init();
			}

			return self::$instance;
		}
	}

	// Get Instant of JLT_Awesome_Announcement Class .
	JLT_Awesome_Announcement::get_instance();
}