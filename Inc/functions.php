<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @version       1.0.0
 * @package       JLT_Awesome_Announcement
 * @license       Copyright JLT_Awesome_Announcement
 */

if ( ! function_exists( 'jltwann_option' ) ) {
	/**
	 * Get setting database option
	 *
	 * @param string $section default section name jltwann_general .
	 * @param string $key .
	 * @param string $default .
	 *
	 * @return string
	 */
	function jltwann_option( $section = 'jltwann_general', $key = '', $default = '' ) {
		$settings = get_option( $section );

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}

if ( ! function_exists( 'jltwann_exclude_pages' ) ) {
	/**
	 * Get exclude pages setting option data
	 *
	 * @return string|array
	 *
	 * @version 1.0.0
	 */
	function jltwann_exclude_pages() {
		return jltwann_option( 'jltwann_triggers', 'exclude_pages', array() );
	}
}

if ( ! function_exists( 'jltwann_exclude_pages' ) ) {
	/**
	 * Get exclude pages except setting option data
	 *
	 * @return string|array
	 *
	 * @version 1.0.0
	 */
	function jltwann_exclude_pages_except() {
		return jltwann_option( 'jltwann_triggers', 'exclude_pages_except', array() );
	}
}