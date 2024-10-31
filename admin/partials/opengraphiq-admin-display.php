<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

<div class="wrap">
    <h2><?php echo esc_html__('OpenGraphiq Settings', 'opengraphiq') ?></h2>
    <form method="post">
        <input type="hidden" name="og_template_nonce" value="<?php echo esc_attr($nonce_value) ?>">
        <h2><?php echo esc_html__('General Settings', 'opengraphiq') ?></h2>
        <p><?php echo esc_html__('Please choose the default OpenGraphiq template to be used. This can be overriden for specific post types or even individual posts in' , 'opengraphiq') ?> <a href="http://1.envato.market/MXro4P" target="_blank"><?php echo esc_html__('Pro Version', 'opengraphiq')?> </a> </p>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="opengraphiq_setting_default_template"><?php echo esc_html__('Default template', 'opengraphiq') ?></label>
                    </th>
                    <td>
                        <?php $this->opengraphiq_setting_default_template_cb(); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2><?php echo esc_html__('Post types', 'opengraphiq') ?></h2>
        <p><?php echo esc_html__('In Lite version of the plugin you can use OpenGraphiq with Posts. If you wish to use it with other post types (including custom post types) please upgrade to', 'opengraphiq')?> <a href="http://1.envato.market/MXro4P" target="_blank"><?php echo esc_html__('Pro Version', 'opengraphiq')?></a>!</p>


        <h2><?php echo esc_html__('Debug mode', 'opengraphiq') ?></h2>
        <p><?php echo esc_html__('If in debug mode, the plugin prints existing meta tag values as comments for easier troubleshooting of meta tag values on frontend', 'opengraphiq') ?></p>
        <table class="form-table" role="presentation">
            <tbody>
                <?php $this->opengraphiq_setting_debug_cb(); ?>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__('Save changes', 'opengraphiq')?>"></p>
    </form>
</div> 
