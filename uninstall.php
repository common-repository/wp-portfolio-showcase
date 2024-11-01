<?php

$settings = get_option( 'wp_portfolio_showcase_settings' );
if ( ! empty( $settings ) && 'on' == $settings['delete_data'] ) {
	global $wpdb;

	//delete pages
	wp_delete_post( $settings['portfolio_page'], true );

	// Delete options.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'wp\_portfolio\_showcase\_%';" );

	// Delete posts + data.
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'portfolio';" );
	$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

	// Delete term taxonomies.
	foreach ( array( 'portfolio_category', 'portfolio_tag' ) as $taxonomy ) {
		$wpdb->delete(
			$wpdb->term_taxonomy,
			array(
				'taxonomy' => $taxonomy,
			)
		);
	}

	// Delete orphan relationships.
	$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

	// Delete orphan terms.
	$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

	// Delete orphan term meta.
	if ( ! empty( $wpdb->termmeta ) ) {
		$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
	}

	// Clear any cached data that has been removed.
	wp_cache_flush();
}