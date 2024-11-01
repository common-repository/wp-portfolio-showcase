<?php defined( 'ABSPATH' ) || exit(); ?>

<div class="portfolio_sec">
    <div class="ul-filter">
        <span data-filter="*" class="active_filter">All</span>

		<?php
		$terms = get_terms( [
			'taxonomy' => 'portfolio_category'
		] );

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				printf( '<span data-filter=".%1$s">%2$s</span>', $term->slug, $term->name );
			}
		}

		?>
    </div>

    <div class="portfolio-items portfolio-col-<?php echo prince_get_settings( 'columns' ); ?>">

		<?php

		$posts_per_page = isset( $shortcode_args['number'] ) ? $shortcode_args['number'] : prince_get_settings( 'posts_per_page', 12 );

		$args = [
			'posts_per_page' => $posts_per_page,
			'post_type'      => 'portfolio',
			'paged'          => ! empty( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : 0
		];

		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {
			$query->the_post();

			$type = prince_get_meta( get_the_ID(), 'type', 'default' );
			wp_portfolio_showcase_template( $type, [ 'post' => get_post( get_the_ID() ) ] );
		}

		?>

    </div>

	<?php if ( ! isset( $is_shortcode ) ) { ?>
        <div class="portfolio-pagination">
            <nav id="post-navigation" class="navigation pagination" role="navigation" aria-label="Post Navigation">
                <div class="nav-links">
					<?php
					$paged      = ! empty( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : 0;
					$translated = __( 'Page', 'wp-portfolio-showcase' ); // Supply translatable string

					echo paginate_links( array(
						'format'             => '?paginate=%#%',
						'current'            => max( 1, $paged ),
						'total'              => $query->max_num_pages,
						'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
						'mid_size'           => 1,
						'prev_text'          => esc_html__( 'Previous', 'wp-portfolio-showcase' ),
						'next_text'          => esc_html__( 'Next', 'wp-portfolio-showcase' ),
					) );

					?>
                </div>
            </nav>
        </div>
	<?php } else {
		if ( 'true' == $shortcode_args['show_more'] ) { ?>
            <div class="portfolio-more">
                <a href="<?php echo get_the_permalink( prince_get_settings( 'portfolio_page' ) ); ?>"><?php echo $shortcode_args['show_more_text']; ?></a>
            </div>
		<?php }
	} ?>
</div>
