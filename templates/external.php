<?php defined('ABSPATH') || exit(); ?>


<div class="item <?php wp_portfolio_showcase_category_class($post->ID); ?>">
    <div class="project_content">

        <div class="portfolio-img">
			<?php wp_portfolio_showcase_thumbnail( $post->ID ); ?>
        </div>

        <div class="info">
            <a href="<?php echo prince_get_meta( $post->ID, 'url' ); ?>" target="_blank">
                <h3><?php echo get_the_title( $post->ID ); ?></h3>
                <p><?php wp_portfolio_showcase_category( $post->ID ); ?></p>
            </a>
        </div>

    </div>
</div>