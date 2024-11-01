<?php

defined( 'ABSPATH' ) || exit();

//set settings option name
add_filter( 'prince_options_id', function () {
	return 'wp_portfolio_showcase_settings';
} );

//set prince_settings_id
add_filter( 'prince_settings_id', function () {
	return 'wp_portfolio_showcase_prince_settings';
} );

//set settings page menu slug
add_filter( 'prince_settings_menu_slug', function () {
	return 'wp-portfolio-showcase-settings';
} );

//set settings page title
add_filter( 'prince_settings_page_title', function () {
	return esc_html__( 'WP Portfolio Showcase Settings', 'wp-portfolio-showcase' );
} );

/**
 * Initialize the custom Settings.
 */
add_action( 'admin_init', 'wp_portfolio_showcase_settings' );

/**
 * Build the custom settings & update Prince.
 *
 * @return    void
 * @since     0.0.1
 */
function wp_portfolio_showcase_settings() {

	/* Prince is not loaded yet, or this is not an admin request */
	if ( ! function_exists( 'prince_settings_id' ) || ! is_admin() ) {
		return;
	}

	/**
	 * Get a copy of the saved settings array.
	 */
	$saved_settings = get_option( prince_settings_id(), array() );

	/**
	 * Custom settings array that will eventually be
	 * passes to the Prince Settings API Class.
	 */

	$custom_settings = array(

		'sections' => array(
			array(
				'id'    => 'general',
				'icon'  => 'dashicons dashicons-admin-generic',
				'title' => esc_html__( 'General Settings', 'wp-portfolio-showcase' )
			),
			array(
				'id'    => 'display',
				'icon'  => 'dashicons dashicons-laptop',
				'title' => esc_html__( 'Display Settings', 'wp-portfolio-showcase' )
			),

		),
		'settings' => array(
			//general
			array(
				'id'      => 'delete_data',
				'label'   => esc_html__( 'Delete Data on Plugin Uninstalling', 'wp-portfolio-showcase' ),
				'desc'    => esc_html__( 'Turn on to delete all the data (Portfolio, Settings) on uninstalling of this plugin.', 'wp-portfolio-showcase' ),
				'std'     => 'off',
				'type'    => 'on_off',
				'section' => 'general'
			),

			//start display settings
			array(
				'id'      => 'portfolio_page',
				'label'   => esc_html__( 'Portfolio Page', 'wp-portfolio-showcase' ),
				'desc'    => esc_html__( 'Select the portfolio page where the portfolio will be displayed', 'wp-portfolio-showcase' ),
				'std'     => get_option( 'wp_portfolio_showcase_page' ),
				'type'    => 'page_select',
				'section' => 'display'
			),

			array(
				'id'      => 'posts_per_page',
				'label'   => __( 'Items per Page', 'wp-portfolio-showcase' ),
				'desc'    => __( 'Enter the number, How many portfolio items will be displayed in the default portfolio page', 'wp-portfolio-showcase' ),
				'std'     => 12,
				'type'    => 'number',
				'section' => 'display'
			),

			array(
				'id'      => 'columns',
				'label'   => esc_html__( 'Portfolio Columns', 'wp-portfolio-showcase' ),
				'desc'    => esc_html__( 'Select how many portfolio items will be displayed per row in Desktop. For tablet the default value is 2 and for mobile the value is 1', 'wp-portfolio-showcase' ),
				'std'     => 3,
				'type'    => 'select',
				'choices' => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
				],
				'section' => 'display'
			),
		)
	);

	/* allow settings to be filtered before saving */
	$custom_settings = apply_filters( prince_settings_id() . '_args', $custom_settings );

	/* settings are not the same update the DB */
	if ( $saved_settings !== $custom_settings ) {
		update_option( prince_settings_id(), $custom_settings );
	}

	/* Lets Prince know the UI Builder is being overridden */
	global $prince_has_custom_settings;
	$prince_has_custom_settings = true;

}