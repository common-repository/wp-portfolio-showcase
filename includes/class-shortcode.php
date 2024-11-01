<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

/**
 * Class ShortCode
 *
 * add short codes
 *
 * @package WP_Portfolio_Showcase
 *
 * @since 0.0.1
 */
class WP_Portfolio_Showcase_ShortCode {

	/* constructor */
	public function __construct() {
		add_shortcode( 'portfolio_showcase', [ $this, 'portfolio' ] );
	}

	/**
	 * Portfolio Showcase
	 *
	 * @param $attrs
	 */
	function portfolio( $atts ) {
		$atts = shortcode_atts( array(
			'number'         => 9,
			'show_more'      => 'true',
			'show_more_text' => esc_html__( 'Show More', 'wp-portfolio-showcase' ),
		), $atts );

		ob_start();
		wp_portfolio_showcase_template( 'portfolio', [ 'shortcode_args' => $atts, 'is_shortcode' => true ] );
		$html = ob_get_clean();

		return $html;
	}


}

new WP_Portfolio_Showcase_ShortCode();