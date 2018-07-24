<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly   ?>
<link rel='stylesheet' id='wpulist-tpl-css'  href='<?php echo plugins_url('css/style.css', __FILE__); ?>' type='text/css' media='all' />
<script type="text/javascript" src="<?php echo plugins_url('../../js/wpulist_scripts_themes.js', __FILE__); ?>"></script>
<input type="hidden" id="theme_count" value="92" />
<input type="hidden" id="frm_id" value="<?php echo $frm['frm_id']; ?>" />
<input type="hidden" id="ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
<input type="hidden" id="background_url" value="<?php echo wpulist_form_image($frm['frm_id'], plugins_url('images/bg.jpg', __FILE__)); ?>" />
<div class="thme92">
    <div class="left">
        <h1 class="title"><?php echo $frm_info->post_title; ?></h1>
        <div class="bdtext">
            <?php echo apply_filters('the_content', $frm_info->post_content); ?>
        </div>
        <form method="post" id="wpulist_frm_<?php echo $frm['frm_id'] ?>" name="wpulist_frm_<?php echo $frm['frm_id'] ?>">
            <?php echo wpulist_generate_frm_fields($frm['frm_id'], $wpulist_frm_items, 1, $css_name, $css_email); ?>
            <input type="submit" id="btn_<?php echo $frm['frm_id']; ?>" value="<?php _e($wpulist_frm_items['fld_sbmt_val'], 'wp-ultimate-list'); ?>" class="frmbutton">
            <div id="wpulist_frm_response"></div>
        </form>
        <p class="disclaimer"><?php echo get_post_meta($frm_info->ID, 'wpulist_frm_disclaimer', true); ?></p>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>

</div>
<?php wpulist_setFormViews($frm['frm_id']); ?>