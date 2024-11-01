<?php defined('ABSPATH') || exit(); ?>


<div class="item <?php wp_portfolio_showcase_category_class( $post->ID ); ?>">
    <div class="project_content">

        <div class="portfolio-img">
			<?php wp_portfolio_showcase_thumbnail( $post->ID ); ?>
        </div>

        <div class="info">
            <a class="popup-with-zoom-anim" href="#portfolio-<?php echo $post->ID; ?>">
                <h3><?php echo get_the_title( $post->ID ); ?></h3>
                <p><?php wp_portfolio_showcase_category( $post->ID ); ?></p>
            </a>
        </div>

    </div>
</div><!--Project Details-->

<div id="portfolio-<?php echo $post->ID; ?>" class="zoom-anim-dialog mfp-hide portfolio-content">

    <div class="portfolio-media">

		<?php
		$slides = explode( ',', prince_get_meta( $post->ID, 'gallery' ) );

		if ( ! empty( array_filter($slides) ) ) { ?>
            <!--Start slider-->
            <div class="project__slider">
                <ul class="project-slider">
					<?php
					foreach ( $slides as $slide ) {
						printf( '<li>%s</li>', wp_get_attachment_image( $slide, 'large' ) );
					}
					?>
                </ul>
            </div><!--End slider-->

		<?php } elseif ( has_post_thumbnail( $post->ID ) ) {
		    echo get_the_post_thumbnail($post->ID, 'full');
		} ?>


        <!--= Video =-->
		<?php wp_portfolio_showcase_video( $post->ID ); ?>

    </div>

    <div class="portfolio-info">
        <h3><?php echo get_the_title( $post ) ?></h3>
        <div class="project_info">
			<?php wp_portfolio_showcase_client( $post->ID ); ?><?php wp_portfolio_showcase_date( $post->ID ); ?>

			<?php wp_portfolio_showcase_skills( $post->ID ); ?>
        </div>

        <p><?php echo apply_filters( 'the_content', $post->post_content ); ?></p>

		<?php wp_portfolio_showcase_url( $post->ID ); ?>

    </div>

</div>