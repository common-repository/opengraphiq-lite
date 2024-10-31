<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

<div class="wrap">
    <h2><?php echo esc_html__('Bulk Creation of Open Graph Images', 'opengraphiq') ?></h2>
    <p><?php echo esc_html__('The Open Graph images will be created for the following posts:', 'opengraphiq') ?></p>
    <div id="listwrapper" data-url="<?php echo esc_attr($redirect_url) ?>" data-nonce="<?php echo esc_attr($nonce) ?>">
    <?php
        if ($query->have_posts() ) : 
            echo '<ol>';
            while ( $query->have_posts() ) : $query->the_post();
                echo '<li class="post-to-edit" data-id="' . esc_attr(get_the_ID()) . '">';
                echo esc_html(the_title());
                echo '</li>';
            endwhile;
            echo '</ol>';
            wp_reset_postdata();
        endif;
    ?>
    </div>
    <div id="bulkgenerate" class="button"><?php echo esc_html__('OK, generate OG images', 'opengraphiq') ?></div>
    <div id="bulkfinished" class="bulk-finished-button button"><?php echo esc_html__('Back to the post list', 'opengraphiq') ?></div>
    <div id="opengraphiqpostcanvas" class="og-post-canvas og-post-canvas-clean"></div>
</div> 


