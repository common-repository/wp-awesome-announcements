<?php
namespace JLTWANN\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Featured' ) ) {

	/**
	 * Featured global class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Featured {

		/**
		 * Constructor
		 */
		public function __construct() {
			if ( is_admin() ) {
				add_filter( 'install_plugins_table_api_args_featured', array( $this, 'jltwann_featured_plugins_tab' ) );
			}
		}

		/**
		 * Helper function for adding plugins to fav list.
		 *
		 * @param [type] $args .
		 */
		public function jltwann_featured_plugins_tab( $args ) {
			add_filter( 'plugins_api_result', array( $this, 'jltwann_plugins_api_result' ), 10, 3 );

			return $args;
		}

		/**
		 * Add our plugins to recommended list.
		 *
		 * @param [type] $res .
		 * @param [type] $action .
		 * @param [type] $args .
		 */
		public function jltwann_plugins_api_result( $res, $action, $args ) {
			remove_filter( 'plugins_api_result', array( $this, 'jltwann_plugins_api_result' ), 10, 1 );

			// Plugin list which you want to show as feature in dashboard.
			// $res = $this->jltwann_add_plugin_favs('image-hover-effects-elementor-addon', $res); .
            $res = $this->jltwann_add_plugin_favs( 'ultimate-blocks-for-gutenberg', $res );
            $res = $this->jltwann_add_plugin_favs( 'adminify', $res );
            $res = $this->jltwann_add_plugin_favs( 'master-addons', $res );
            
			return $res;
		}

		/**
		 * Add single plugin to list of favs.
		 *
		 * @param [type] $plugin_slug .
		 * @param [type] $res .
		 */
		public function jltwann_add_plugin_favs( $plugin_slug, $res ) {
			if ( ! empty( $res->plugins ) && is_array( $res->plugins ) ) {
				foreach ( $res->plugins as $plugin ) {
					if ( is_object( $plugin ) && ! empty( $plugin->slug ) && $plugin_slug === $plugin->slug ) {
						return $res;
					}
				} // foreach
			}

			$plugin_info = new \stdClass();
			if ( get_transient( 'jltwann-plugin-info-' . $plugin_slug == $plugin_info ) ) {
				array_unshift( $res->plugins, $plugin_info );
			} else {
				$plugin_info = plugins_api(
					'plugin_information',
					array(
						'slug'   => $plugin_slug,
						'is_ssl' => is_ssl(),
						'fields' => array(
							'banners'           => true,
							'reviews'           => true,
							'downloaded'        => true,
							'active_installs'   => true,
							'icons'             => true,
							'short_description' => true,
						),
					)
				);

				if ( ! is_wp_error( $plugin_info ) ) {
					$res->plugins[] = $plugin_info;
					set_transient( 'jltwann-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7 );
				}
			}

			return $res;
		}
	}
}