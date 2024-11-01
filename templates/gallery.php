<?php defined('ABSPATH') || exit(); ?>


<div class="item <?php wp_portfolio_showcase_category_class( $post->ID ); ?>">
    <div class="project_content">

        <div class="portfolio-img">
			<?php wp_portfolio_showcase_thumbnail( $post->ID ); ?>

			<?php
			$slides = explode( ',', prince_get_meta( $post->ID, 'gallery' ) );

			if ( ! empty( $slides ) ) { ?>
                <div class="portfolio-gallery">
					<?php

					foreach ( $slides as $slide ) {
						printf( '<a href="%1$s" title="%2$s"></a>',
							wp_get_attachment_image_url( $slide, 'large' ),
							wp_get_attachment_caption( $slide )
						);
					}

					if ( ! empty( $video = prince_get_meta( $post->ID, 'video' ) ) ) {
						printf( '<a href="%1$s" class="mfp-iframe"></a>', $video );
					}

					?>
                </div>
			<?php } ?>
        </div>

        <div class="info">
            <a href="#" class="image_overlay">
                <h3><?php echo get_the_title( $post->ID ); ?></h3>
                <p><?php wp_portfolio_showcase_category( $post->ID ); ?></p>
            </a>
        </div>

    </div>
</div>