<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly                  ?>
<div class="dshbsttelstrgt">
    <h2 class="wpulist_dshh2heading1"><?php _e('Active Services', 'wp-ultimate-list'); ?></h2>
    <?php
// get active services array
    $wpulist_ml_info_option = get_option(('wpulist_ml_info'));
    $wpulist_ml_info = unserialize($wpulist_ml_info_option);
    // Get mailing list manager data for service name
    $ml_services = unserialize(get_option('wpulist_list_managers'));
    ?>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Service', 'wp-ultimate-list'); ?></th>

                <th><?php _e('Subscribers', 'wp-ultimate-list'); ?></th>

            </tr>
        </thead>
        <tbody id="the-list">
            <?php
            if ($wpulist_ml_info) {
                // loop through the array of existing active services
                global $wpdb;

                foreach ($wpulist_ml_info as $subkey => $list_object) {
                    // count subscribers by services ID
                    #echo $subkey;
                    if ($subkey) {
                        $wpulist_sql_get_subsc = "select count(SUBSTR(email_list_id, 1,1)) as list_ids from " . $wpdb->prefix . "wpulist_emails where SUBSTR(email_list_id, 1,1) = " . $subkey;
                        #echo $wpulist_sql_get_subsc;
                        $wpulist_rows_subs = $wpdb->get_row($wpulist_sql_get_subsc);
                        ?>
                        <tr>
                            <td width="50%"><img src="<?php echo plugins_url('integration/logos/' . $subkey . '.png', __FILE__); ?>" class="dshbsttelstrgtlogo" /></td>

                            <td><p class="wpulist_subs_rght_nmb"><?php echo esc_html($wpulist_rows_subs->list_ids); ?></p></td>

                        </tr>
                    <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>