<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit;

/**
 * Class MetaBox
 *
 * Handle metaboxes
 *
 * @package WP_Portfolio_Showcase
 *
 * @since 0.0.1
 */
class WP_Portfolio_Showcase_MetaBox {

	/**
	 * MetaBox constructor.
	 * Initialize the custom Meta Boxes for prince-settings api.
	 *
	 * @since 0.0.1
	 */
	function __construct() {
		add_action( 'admin_init', array( $this, 'meta_boxes' ) );
		add_filter( 'prince_media_buttons', array( $this, 'wp_editor_media_buttons' ), 10, 2 );
		add_filter( 'prince_tinymce', array( $this, 'wp_editor_tinymce' ), 10, 2 );
	}

	/**
	 * Create a custom meta boxes array that we pass to
	 * the Prince Meta Box API Class.
	 *
	 * @since 0.0.1
	 */
	function meta_boxes() {

		$metaboxes = [];

		$metaboxes['wp_portfolio_showcase_metabox'] = array(
			'id'       => 'wp_portfolio_showcase_metabox',
			'title'    => __( 'Portfolio Information', 'wp-portfolio-showcase' ),
			'pages'    => array( 'portfolio' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => apply_filters( 'wp_portfolio_showcase_metabox_fields', array(
					array(
						'label' => __( 'General', 'wp-portfolio-showcase' ),
						'id'    => 'general_tab',
						'type'  => 'tab',
					),
					array(
						'label'    => __( 'Portfolio Type', 'wp-portfolio-showcase' ),
						'id'       => 'type',
						'type'     => 'radio',
						'desc'     => __( 'Select the portfolio type.', 'wp-portfolio-showcase' ),
						'std'      => 'default',
						'operator' => 'and',
						'choices'  => array(
							array(
								'value' => 'default',
								'label' => __( 'Default', 'wp-portfolio-showcase' ),
							),
							array(
								'value' => 'gallery',
								'label' => __( 'Lightbox Gallery', 'wp-portfolio-showcase' ),
							),
							array(
								'value' => 'video',
								'label' => __( 'Popup Video', 'wp-portfolio-showcase' ),
							),
							array(
								'value' => 'external',
								'label' => __( 'External Link', 'wp-portfolio-showcase' ),
							),
						)
					),
					array(
						'label' => __( 'Details', 'wp-portfolio-showcase' ),
						'id'    => 'details_tab',
						'type'  => 'tab',
					),
					array(
						'label' => __( 'Client', 'wp-portfolio-showcase' ),
						'id'    => 'client',
						'type'  => 'text',
						'desc'  => __( 'Enter the project client name (Optional).', 'wp-portfolio-showcase' ),
						'attrs' => array(
							'placeholder' => 'Client Name'
						),
					),

					array(
						'label' => __( 'Date', 'wp-portfolio-showcase' ),
						'id'    => 'date',
						'type'  => 'text',
						'desc'  => __( 'Enter the project date (Optional).', 'wp-portfolio-showcase' ),
						'attrs' => array(
							'placeholder' => 'March 19, 2019'
						),
					),

					array(
						'label' => __( 'Skills', 'wp-portfolio-showcase' ),
						'id'    => 'skills',
						'type'  => 'text',
						'desc'  => __( 'Enter the project related skills (Optional).', 'wp-portfolio-showcase' ),
						'attrs' => array(
							'placeholder' => 'HTML5, JS, CSS3'
						),
					),

					array(
						'label' => __( 'URL', 'wp-portfolio-showcase' ),
						'id'    => 'url',
						'type'  => 'text',
						'desc'  => __( 'Enter the project URL (Optional).', 'wp-portfolio-showcase' ),
						'attrs' => array(
							'placeholder' => 'https://projecturl.com'
						),
					),
					array(
						'label' => __( 'Gallery/ Video', 'wp-portfolio-showcase' ),
						'id'    => 'gallery_tab',
						'type'  => 'tab',
					),
					array(
						'label' => __( 'Portfolio Gallery', 'wp-portfolio-showcase' ),
						'desc'  => __( 'Select the images for the portfolio', 'wp-portfolio-showcase' ),
						'id'    => 'gallery',
						'type'  => 'gallery',
					),
					array(
						'label' => __( 'Portfolio Video', 'wp-portfolio-showcase' ),
						'desc'  => __( 'Enter the video URL or select the video from media', 'wp-portfolio-showcase' ),
						'id'    => 'video',
						'type'  => 'upload',
					),

				)
			)

		);


		if ( function_exists( 'prince_register_meta_box' ) ) {
			foreach ( $metaboxes as $metabox ) {
				prince_register_meta_box( $metabox );
			}
		}

	}

	/**
	 * Hide wp_editor media buttons for metabox specific ids
	 *
	 * @param $true
	 * @param $field_id
	 *
	 * @return bool
	 * @since 0.0.1
	 *
	 */
	function wp_editor_media_buttons( $true, $field_id ) {


		if ( 'additional' == $field_id ) {
			return false;
		}

		return $true;
	}

	/**
	 * Disallow wp_editor tinymce editor for metabox specific field ids
	 *
	 * @param $true
	 * @param $field_id
	 *
	 * @return bool
	 * @since 0.0.1
	 *
	 */
	function wp_editor_tinymce( $true, $field_id ) {
		if ( 'additional' == $field_id ) {
			return false;
		}

		return $true;
	}

}

new WP_Portfolio_Showcase_MetaBox();
