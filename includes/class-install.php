<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 */
class WP_Portfolio_Showcase_Install {

	public static function activate() {
		self::create_pages();
		self::update_option();
	}

	/**
	 * Create pages
	 *
	 * @since 2.1.0
	 */
	private static function create_pages() {
		if ( get_page_by_title( 'Portfolio' ) ) {
			return;
		}

		$id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_title'  => esc_html__( 'Portfolio', 'wp-portfolio-showcase' ),
			'post_status' => 'publish',
		) );

		update_option( 'wp_portfolio_showcase_page', $id );

	}


	private static function update_option() {
		update_option( 'wp_portfolio_showcase_flush_rewrite_rules', true );
		set_transient( 'wp_portfolio_showcase_display_review_notice', 'off', 72 * HOUR_IN_SECONDS );
	}

}