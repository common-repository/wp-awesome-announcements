<?php
namespace JLTWANN\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Assets' ) ) {

	/**
	 * Assets Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 * @version     2.0.4
	 */
	class Assets {

		/**
		 * Constructor method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'jltwann_enqueue_scripts' ), 100 );
			add_action( 'admin_enqueue_scripts', array( $this, 'jltwann_admin_enqueue_scripts' ), 100 );
			add_action( 'wp_footer', array( $this, 'jltwann_display_announcement' ) );
		}


		/**
		 * Get environment mode
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function get_mode() {
			return defined( 'WP_DEBUG' ) && WP_DEBUG ? 'development' : 'production';
		}

		/**
		 * Enqueue Scripts
		 *
		 * @method wp_enqueue_scripts()
		 */
		public function jltwann_enqueue_scripts() {
			
			// CSS Files .
			wp_enqueue_style( 'wp-awesome-announcements-frontend', JLTWANN_ASSETS . 'css/wp-awesome-announcements-frontend.css', JLTWANN_VER, 'all' );

			// JS Files .
			// wp_enqueue_script( 'announcements', JLTWANN_ASSETS . 'js/announcements.js', array( 'jquery' ) );
		    wp_enqueue_script( 'cookies', JLTWANN_ASSETS . 'js/jquery.cookie.js', array( 'jquery' ) );
		    wp_enqueue_script( 'cycle', JLTWANN_ASSETS . 'js/jquery.cycle.lite.js', array( 'jquery' ) );
			wp_enqueue_script( 'wp-awesome-announcements-frontend', JLTWANN_ASSETS . 'js/wp-awesome-announcements-frontend.js', array( 'jquery' ), JLTWANN_VER, true );
		}


		/**
		 * Enqueue Scripts
		 *
		 * @method admin_enqueue_scripts()
		 */
		public function jltwann_admin_enqueue_scripts() {

		    global $post;

			if( ( !isset($post) || $post->post_type != 'announcements' ))
			return;
		 
			// CSS Files .
			wp_enqueue_style( 'datepicker-style', JLTWANN_ASSETS . 'css/ui-lightness/jquery-ui.css');
			wp_enqueue_style( 'wp-awesome-announcements-admin', JLTWANN_ASSETS . 'css/wp-awesome-announcements-admin.css', JLTWANN_VER, 'all' );
			
			// JS Files .
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
		    wp_enqueue_script( 'announcements', JLTWANN_ASSETS . 'js/announcements.js', array( 'jquery' ) );
			wp_enqueue_script( 'wp-awesome-announcements-admin', JLTWANN_ASSETS . 'js/wp-awesome-announcements-admin.js', array( 'jquery' ), JLTWANN_VER, true );

			// wp_localize_script(
			// 	'wp-awesome-announcements-admin',
			// 	'JLTWANNCORE',
			// 	array(
			// 		'admin_ajax'        => admin_url( 'admin-ajax.php' ),
			// 		'recommended_nonce' => wp_create_nonce( 'jltwann_recommended_nonce' ),
			// 	)
			// );

		}



		//Display announcements
		public function jltwann_display_announcement() {

		    global $wpdb;
		    //Select announcements, which start before and end after current date and those with empty dates
		    $jltwann_ids = $wpdb->get_results("SELECT `m1`.`post_id` FROM " . $wpdb->prefix . "postmeta `m1`
		                                   JOIN " . $wpdb->prefix . "postmeta `m2` ON `m1`.`post_id` = `m2`.`post_id`                                   
		                                   WHERE 
		                                   (`m1`.`meta_key` = 'jeweltheme_start_date' AND (UNIX_TIMESTAMP(`m1`.`meta_value`) < UNIX_TIMESTAMP() OR `m1`.`meta_value` = ''))                                   
		                                   AND 
		                                   (`m2`.`meta_key` = 'jeweltheme_end_date' AND (UNIX_TIMESTAMP(`m2`.`meta_value`) > UNIX_TIMESTAMP() OR `m2`.`meta_value` = ''))",                                   
		                                   ARRAY_N);
		    

		    if ($jltwann_ids){
		        foreach ($jltwann_ids as $id){
		            $post_id[] = $id[0];            
		        }
		        $ids = implode(",",$post_id);
		        
		        $announcements = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts AS `posts` WHERE `posts`.`ID` IN (".$ids.")");

			    
			    //HTML output
			    if($announcements) {
			        ?>
			            <div id="announcements" class="hidden"> 
			                <div class="wrapper">
			                    <a class="close" href="#" id="close"><?php _e('x', 'simple-announcements'); ?></a>                    
			                    <div class="jeweltheme_message">
				                    <?php foreach ($announcements as $announcement) { 
				                    	echo do_shortcode(wpautop(($announcement->post_content)));
				                    }
				                    ?>
			                    </div>
			                </div>
			            </div>
			        <?php
				}

		    }


		}


	}
}