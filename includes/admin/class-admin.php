<?php

defined( 'ABSPATH' ) || exit;

class WP_Portfolio_Showcase_Admin {

	public function __construct() {
		add_filter( 'prince_radio_images', [ $this, 'template_layout_images' ], 10, 2 );
		add_filter( 'prince_header_version_text', [ $this, 'settings_version_text' ] );
		add_filter( 'prince_header_logo_link', [ $this, 'settings_page_logo' ] );
		add_filter( 'prince_settings_parent_slug', [ $this, 'settings_menu' ] );
		add_filter( 'display_post_states', [ $this, 'portfolio_page_status' ], 10, 2 );
	}

	/**
	 * Settings page layout images
	 *
	 * @param $text
	 *
	 * @return array|string
	 * @since 0.0.1
	 *
	 */
	function template_layout_images( $choices, $field_id ) {

		if ( 'template_layout' != $field_id ) {
			return false;
		}

		$choices = array(
			array(
				'value' => 'left-sidebar',
				'label' => __( 'Left Country Sidebar', 'wp-portfolio-showcase' ),
				'src'   => PRINCE_ASSETS_URL . 'left-sidebar.png'
			),
			array(
				'value' => 'full-width',
				'label' => __( 'Full Width (no sidebar)', 'wp-portfolio-showcase' ),
				'src'   => PRINCE_ASSETS_URL . 'full-width.png'
			),
		);

		return $choices;
	}

	/**
	 * Settings page version text
	 *
	 * @param $text
	 *
	 * @return string
	 * @since 0.0.1
	 *
	 */
	function settings_version_text( $text ) {
		return __( 'WP Portfolio Showcase - ' . WP_PORTFOLIO_SHOWCASE_VERSION, 'wp-portfolio-showcase' );
	}

	/**
	 * Settings page logo
	 *
	 * @param $html
	 *
	 * @return string
	 * @since 0.0.1
	 *
	 */
	function settings_page_logo( $html ) {
		return '<a href="#" target="_blank"> <span style="position: relative; height: auto;margin-top: 1px;color:#eee;" class="dashicons dashicons-portfolio" ></span> </a>';
	}

	/**
	 * Add settings menu to Main menu
	 */
	function settings_menu() {
		return 'edit.php?post_type=portfolio';
	}

	/**
	 * Add status for portfolio page
	 *
	 * @param $states
	 * @param $post
	 *
	 * @return array
	 */
	function portfolio_page_status( $states, $post ) {

		if ( prince_get_option( 'portfolio_page' ) == $post->ID ) {

			$states[] = __( 'Portfolio Page', 'wp-portfolio-showcase' );

		}

		return $states;
	}

}

new WP_Portfolio_Showcase_Admin();