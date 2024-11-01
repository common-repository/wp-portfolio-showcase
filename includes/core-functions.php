<?php

defined( 'ABSPATH' ) || exit(); // Exit if accessed directly.


/**
 * Get post meta value. Default value return if meta value is empty
 *
 * @param $post_id
 * @param $key
 * @param string $default
 *
 * @return mixed|string
 */
function prince_get_meta( $post_id, $key, $default = '' ) {
	$meta = get_post_meta( $post_id, $key, true );

	return ! empty( $meta ) ? $meta : $default;
}

function prince_get_settings( $key, $default = '' ) {
	$settings = get_option( 'wp_portfolio_showcase_settings' );

	return ! empty( $settings[ $key ] ) ? $settings[ $key ] : $default;
}

function wp_portfolio_showcase_thumbnail( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		$img = get_the_post_thumbnail( $post_id, 'large' );
	} else {
		$img = '<img src="' . WP_PORTFOLIO_SHOWCASE_ASSETS_URL . '/images/work-1.jpg">';
	}

	echo $img;
}

function wp_portfolio_showcase_category( $post_id ) {
	$categories = wp_get_object_terms( $post_id, 'portfolio_category' );

	echo ! empty( $categories ) ? $categories[0]->name : '';
}

function wp_portfolio_showcase_video( $post_id ) {
	$url = get_post_meta( $post_id, 'video', true );

	$video = '';
	if ( ! empty( $url ) ) {
		if ( attachment_url_to_postid( $url ) ) {
			$video = do_shortcode( '[video src="' . $url . '"]' );
		} else {
			$video = wp_oembed_get( $url );
		}
	}

	echo $video;
}

function wp_portfolio_showcase_url( $post_id ) {
	$url = prince_get_meta( $post_id, 'url' );

	echo ! empty( $url ) ? '<a href="' . $url . '" target="_blank" class="demo">Visit website</a>' : '';
}

function wp_portfolio_showcase_client( $post_id ) {
	$client = prince_get_meta( $post_id, 'client' );

	echo ! empty( $client ) ? '<span><strong>Client </strong>: ' . $client . '</span>' : '';
}

function wp_portfolio_showcase_date( $post_id ) {
	$date = prince_get_meta( $post_id, 'date' );

	echo ! empty( $date ) ? '<span><strong>Date </strong>: ' . $date . '</span>' : '';
}

function wp_portfolio_showcase_skills( $post_id ) {
	$skills = prince_get_meta( $post_id, 'skills' );

	echo ! empty( $skills ) ? '<span><strong>Skills </strong>: ' . $skills . '</span>' : '';
}

function wp_portfolio_showcase_category_class( $post_id ) {
	$categories = wp_get_object_terms( $post_id, 'portfolio_category' );
	$categories = wp_list_pluck( $categories, 'slug' );

	echo implode( ' ', $categories );
}