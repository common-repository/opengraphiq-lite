<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

<div class="wrap og-admin-single-post">
    <input type="hidden" id="og_template_nonce" name="og_template_nonce" value="<?php echo esc_attr($nonce_value) ?>">
    <input type="hidden" id="og_post_id" name="og_post_id" value="<?php echo esc_attr($post->ID) ?>">
    <label for="opengraphiq_setting_default_template"><?php echo esc_html__('Override the general template', 'opengraphiq') ?></label>
    <?php $this -> opengraphiq_meta_box_template_cb($post) ?>
    <div id="ogtemplateyes">
        
    <?php 
    global $pagenow;
        if( $pagenow != "post-new.php" ){
            if ($img_url !='') {
    ?>
		<div id="og-post-image-thickbox" style="display:none;"><img src="<?php echo esc_attr($img_url)?>?rnd=<?php echo rand( 1, 100000 );?>" alt="OpenGraphiq Image"  id=""/></div>
        <div id="og-post-image">
			    <a href="#TB_inline?&width=600&height=550&inlineId=og-post-image-thickbox" title="" class="thickbox" rel="og-gallery">
                    <img src="<?php echo esc_attr($img_url)?>?rnd=<?php echo rand( 1, 100000 );?>" alt="OpenGraphiq Image" />
                </a>
        </div>

        <?php
            } else {
        ?>
        <div id="og-post-image-thickbox" src="" style="display:none"><img src="" alt="OpenGraphiq Image"  id=""/></div>
        <div id="og-post-image" style="display:none">
			    <a href="TB_inline?&width=600&height=550&inlineId=og-post-image-thickbox" title="" class="thickbox" rel="og-gallery">
                    <img src="" alt="OpenGraphiq Image" />
                </a>
            </div>    
        <?php

            }  
        ?>

        <div class="og-facebook-debugger"><a href="https://developers.facebook.com/tools/debug/?q=<?php echo esc_attr(get_permalink( $post )); ?>" target="_blank" class="button"><?php echo esc_html__('Facebook OG debugger', 'opengraphiq') ?></a></div>
        <div class="og-twitter-validator"><a href="https://cards-dev.twitter.com/validator/" target="_blank" class="button"><?php echo esc_html__('Twitter Card validator', 'opengraphiq') ?></a></div>
        <div id='bulkgenerate' class="button button-primary"><?php echo esc_html__("Generate new image", 'opengraphiq')?></div>
        <?php
            }  else {
         ?>
            <div id="og-post-image-thickbox" src="" style="display:none"><img src="" alt="OpenGraphiq Image"  id=""/></div>

            <div id="og-post-image" style="display:none">
			    <a href="#TB_inline?&width=600&height=550&inlineId=og-post-image-thickbox" title="" class="thickbox" rel="og-gallery">
                    <img src="" alt="OpenGraphiq Image" />
                </a>
            </div>

            <div class="og-facebook-debugger" style="display:none"><a href="https://developers.facebook.com/tools/debug/?q=<?php echo get_permalink( $post ); ?>" target="_blank" class="button"><?php echo esc_html__('Facebook OG debugger', 'opengraphiq') ?></a></div>
            <div class="og-twitter-validator" style="display:none"><a href="https://cards-dev.twitter.com/validator/" target="_blank" class="button"><?php echo esc_html__('Twitter Card validator', 'opengraphiq') ?></a></div>
            <div id='bulkgenerate' class="button button-primary" style="display:none"><?php echo esc_html__("Generate new image")?></div>
        <?php        
        
            }
        ?>
    </div>

    <div id="ogtemplateno">
        <div id='bulksave' class="button button-primary"><?php echo esc_html__("Save template settings", 'opengraphiq')?></div>
    </div>
    <div id='ogresponse' class='og-response'></div>
    <div id="opengraphiqpostcanvas" class="og-post-canvas og-post-canvas-clean"></div>
</div> 
