<?php


/* Block direct access */
defined( 'ABSPATH' ) || exit();

/**
 * Get template files
 *
 * since 0.0.1
 *
 * @param        $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 *
 * @return void
 */
function wp_portfolio_showcase_template( $template_name, $args = array(), $template_path = 'wp-portfolio-showcase', $default_path = '' ) {

	/* Add php file extension to the template name */
	$template_name = $template_name . '.php';

	/* Extract the args to variables */
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	/* Look within passed path within the theme - this is high priority. */
	$template = locate_template( array( trailingslashit( $template_path ) . $template_name ) );

	/* Get default template. */
	if ( ! $template && false !== $default_path ) {
		$default_path = $default_path ? $default_path : WP_PORTFOLIO_SHOWCASE_TEMPLATES_DIR;
		if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}
	};

	// Return what we found.
	include( apply_filters( 'wp_portfolio_showcase_locate_template', $template, $template_name, $template_path ) );

}

/**
 * Station Listing
 */
function portfolio_page_content() {

	ob_start();
	wp_portfolio_showcase_template( 'portfolio' );
	$html = ob_get_clean();

	global $wp_query;

	$post = get_post(prince_get_settings('portfolio_page'));

	$content_prefix = apply_filters('the_content', $post->post_content);

	$post_title = apply_filters('the_title', $post->post_title);

	$post_name = ! empty( $post->post_name ) ? $post->post_name : '';

	$dummy_post_properties = array(
		'ID'                    => 0,
		'post_status'           => 'publish',
		'post_author'           => '',
		'post_parent'           => 0,
		'post_type'             => 'page',
		'post_date'             => '',
		'post_date_gmt'         => '',
		'post_modified'         => '',
		'post_modified_gmt'     => '',
		'post_content'          => $content_prefix . $html,
		'post_title'            => $post_title,
		'post_excerpt'          => '',
		'post_content_filtered' => '',
		'post_mime_type'        => '',
		'post_password'         => '',
		'post_name'             => $post_name,
		'guid'                  => '',
		'menu_order'            => 0,
		'pinged'                => '',
		'to_ping'               => '',
		'ping_status'           => '',
		'comment_status'        => 'closed',
		'comment_count'         => 0,
		'filter'                => 'raw',
	);

	// Set the $post global.
	$post = new WP_Post( (object) $dummy_post_properties );

	// Copy the new post global into the main $wp_query.
	$wp_query->post  = $post;
	$wp_query->posts = array( $post );

	// Prevent comments form from appearing.
	$wp_query->post_count    = 1;
	$wp_query->is_404        = false;
	$wp_query->is_page       = true;
	$wp_query->is_single     = true;
	$wp_query->max_num_pages = 0;

	// Prepare everything for rendering.
	setup_postdata( $post );
	remove_all_filters( 'the_content' );
	remove_all_filters( 'the_excerpt' );

	add_filter( 'template_include', 'wp_portfolio_showcase_force_single_template_filter' );
}


/**
 * Force the loading of one of the single templates instead of whatever template was about to be loaded.
 *
 * @param string $template Path to template.
 *
 * @return string
 * @since 2.0.2.1
 */
function wp_portfolio_showcase_force_single_template_filter( $template ) {
	$possible_templates = array(
		'page',
		'single',
		'singular',
		'index',
	);


	foreach ( $possible_templates as $possible_template ) {
		$path = get_query_template( $possible_template );
		if ( $path ) {
			return $path;
		}
	}

	return $template;
}

function wp_portfolio_showcase_template_redirect() {
	if ( ! is_page( prince_get_settings( 'portfolio_page' ) ) ) {
		return;
	}
	portfolio_page_content();

}

add_action( 'template_redirect', 'wp_portfolio_showcase_template_redirect' );