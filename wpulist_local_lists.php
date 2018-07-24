<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly      ?>
<h2><?php _e('Local Mailing Lists', 'wp-ultimate-list'); ?></h2>
<div class="">
    <div class="row-fluid">
        <div class="col-md-6">&nbsp;</div>
        <div class="col-md-6">&nbsp;</div>
    </div>
    <form id="frm_wpulist_mailing_list" class="form-inline" name="frm_wpulist_mailing_list" method="POST" action="">
        <div class="form-group">
            <label for="wpulist_mailing_list_name"><?php _e('Mailing list: ', 'wp-ultimate-list'); ?></label>
            <input name="wpulist_mailing_list_name" type="text" id="wpulist_mailing_list_name" required class="form-control" value="" placeholder="<?php _e('Mailing List Name ', 'wp-ultimate-list'); ?>">
        </div>
        <div class="form-group"><button type="submit" class="wpulist_dctvt_btn dctvt_btnll" id="btn_re_save_feature_cat"><?php _e('Add Mailing List', 'wp-ultimate-list'); ?></button></div>
        <?php wp_nonce_field('chk_nonce_stng', 'wpulist_frm_settings'); ?>
    </form>


    <div class="table-responsive">
        <table class="widefat widefat22">
            <tbody id="the-list">
                <?php
                $email_lists = $wpdb->get_results("select id,email_list_name from " . $wpdb->prefix . "wpulist_emails_lists", ARRAY_A);
                foreach ($email_lists as $email_list) {
                    echo '<tr id="mail_list_' . $email_list['id'] . '">';
                    echo '<td width="80%">' . $email_list['email_list_name'] . '</td>';
                    echo '<td width="10%" style="text-align:center"><a id="p_delete" class="wpulist_dctvt_btn" href="javascript:wpmulti_delete_email_list(\'' . $email_list['id'] . '\',\'' . __('Are you sure? This action cannot be undone!', 'wp-ultimate-list') . '\');">' . __('Delete', 'wp-ultimate-list') . '</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</div>