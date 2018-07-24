<?php if (!defined('ABSPATH')) exit; ?>
<?php
wp_register_script('wpulist_script_admin_dashboard', plugins_url('/js/wpulist_admin_dashboard.js', __FILE__), '', '1.0', true);
wp_enqueue_script('wpulist_script_admin_dashboard');

//Get wpulist settings array
$wpulist_settings = unserialize(get_option('wpulist_settings'));
if (isset($_REQUEST["yes_submit"])) {
    $yes_submt = sanitize_text_field($_REQUEST["yes_submit"]);
}
if (isset($_REQUEST["no_submit"])) {
    $no_submt = sanitize_text_field($_REQUEST["no_submit"]);
}

if (isset($yes_submt)) {
    $wpulist_settings["enable_geo_ip"] = 1;
    update_option('wpulist_settings', serialize($wpulist_settings));
}
if (isset($no_submt)) {
    $wpulist_settings["enable_geo_ip"] = 0;
    update_option('wpulist_settings', serialize($wpulist_settings));
}
$wpulist_settings = unserialize(get_option('wpulist_settings'));

if (isset($_REQUEST["frm"])) {
    $frm_id = sanitize_key($_REQUEST['frm']);
}
if (isset($_REQUEST["paged"])) {
    $paged = sanitize_key($_REQUEST['paged']);
}
if (isset($_REQUEST["numlist"])) {
    $num_list = sanitize_key($_REQUEST['numlist']);

    if ($num_list > 500) {
        $num_list = 500;
    } else if ($num_list == '') {
        $num_list = 25;
    }
    if ($paged == '') {
        $paged = 1;
        $start = 0;
    } else {
        $start = $num_list * ($paged - 1);
    }
} else {
    $num_list = null;
}

if (isset($_POST['export']) && sanitize_key($_POST['export'])) {
    wpulist_export_excel_subscribers($wpulist_settings);
}
$del_ = isset($_POST['delete']) ? sanitize_key($_POST['delete']) : null;

$delete_results = isset($_POST['delete_results']) ? sanitize_key($_POST['delete_results']) : null;

if ($del_ && $delete_results) {
    $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "wpulist_emails where id in (" . implode(",", $delete_results) . ")"));
}

$wpulist_sql_total_subs = "SELECT COUNT(id) as numemails FROM " . $wpdb->prefix . "wpulist_emails ";
if (isset($frm_id)) {
    $wpulist_sql_total_subs .= " WHERE email_frm_id = " . (int) $frm_id;
}
$wpulist_get_total_rows = $wpdb->get_row($wpulist_sql_total_subs);
$total_subs = $wpulist_get_total_rows->numemails;
@$num_page = ceil($total_subs / $num_list);
?>
<h1><?php _e('Mailing List Subscribers', 'wp-ultimate-list'); ?></h1>

<?php add_thickbox(); ?>
<form action="" method="post" id="exportForm">
    <input type="hidden" name="export" value="excel" />
</form>
<div id="location-box" class="wpulist_lclbx_sbcrbenone ">
    <p>
    <form method="post" action="" class="wpulist_sttngppup">
        <?php _e('Enable location display of the subscriber?', 'wp-ultimate-list') ?><br />
        <input type="submit" value="Yes" name="yes_submit" class="wpulist_sttngppupyes" />
        <input type="submit" value="No" name="no_submit" class="wpulist_sttngppupno" />
    </form>
</p>
</div>

<div id="delete-box" class="wpulist_lclbx_sbcrbedltnone">
    <p>
    <form method="post" action="" class="wpulist_sttngppup">
        <?php _e('Are you sure you want to delete the selected records?', 'wp-ultimate-list') ?><br />
        <?php _e('This will NOT delete the data from your 3rd Party Service.', 'wp-ultimate-list') ?><br />
        <input type="button" value="Yes" id="wpulist_delete_subscriber_yes" name="yes_submit" class="wpulist_sttngppupyes" />
        <input type="button" value="No" id="wpulist_delete_subscriber_no" name="no_submit" class="wpulist_sttngppupno" />
    </form>
</p>
</div>

<a href="#TB_inline?width=600&height=190&inlineId=location-box" class="thickbox wpulist_sttngico"><?php _e('Settings', 'wp-ultimate-list') ?></a>

<form class="subslstmainfrmbx" id="deleteForm" action="" method="post">
    <a href="#TB_inline?width=600&height=190&inlineId=delete-box" class="thickbox dltesele expsubsbtn"><?php _e('Delete Selected', 'wp-ultimate-list') ?></a>
    <input name="delete" value="<?php _e('Delete Selected', 'wp-ultimate-list') ?>" type="hidden"/>
    <input name="exportsubs" value="<?php _e('Export Subscribers', 'wp-ultimate-list') ?>" id="exportButton" type="button" class="dltesele expsubsbtn" />
    <select name="listviewnum" id="subscriber_page_refresh" class="nmbrrcrdlst">
        <option value="admin.php?page=wpulist_subscribers&numlist=25" <?php if ($num_list == 25) : ?> selected=""<?php endif; ?>>25</option>
        <option value="admin.php?page=wpulist_subscribers&numlist=50" <?php if ($num_list == 50) : ?> selected=""<?php endif; ?>>50</option>
        <option value="admin.php?page=wpulist_subscribers&numlist=100" <?php if ($num_list == 100) : ?> selected=""<?php endif; ?>>100</option>
        <option value="admin.php?page=wpulist_subscribers&numlist=200" <?php if ($num_list == 200) : ?> selected=""<?php endif; ?>>200</option>
        <option value="admin.php?page=wpulist_subscribers&numlist=500" <?php if ($num_list == 500) : ?> selected=""<?php endif; ?>>500</option>
    </select>
    <?php if ($del_) { ?> <div class="deletenoti"><?php _e('Successfully deleted the selected records', 'wp-ultimate-list'); ?></div> <?php } ?>
    <div class="clear"></div>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Select', 'wp-ultimate-list'); ?></th>
                <th><?php _e('Name', 'wp-ultimate-list'); ?></th>
                <th><?php _e('Email', 'wp-ultimate-list'); ?></th>
                <th><?php _e('Form', 'wp-ultimate-list'); ?></th>
                <?php if ($wpulist_settings['enable_geo_ip'] == 1) { ?><th><?php _e('Location', 'wp-ultimate-list'); ?></th><?php } ?>
                <th><?php _e('Date & Time', 'wp-ultimate-list'); ?></th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php
            $wpulist_sql_subscribers = "SELECT id, email_address, email_usr_name, email_frm_id, date_added, usr_ip FROM " . $wpdb->prefix . "wpulist_emails ";
            if (isset($frm_id)) {
                $wpulist_sql_subscribers .= " WHERE email_frm_id = " . (int) $frm_id;
            }
            $wpulist_sql_subscribers .= " ORDER BY date_added DESC";
            if (isset($num_list)) {
                $wpulist_sql_subscribers .= " LIMIT  $start, $num_list";
            }

            $wpulist_rows_subscribers = $wpdb->get_results($wpulist_sql_subscribers);
            $c = true;
            if ($wpulist_rows_subscribers) {
                foreach ($wpulist_rows_subscribers as $subinfo) {
                    ?>

                    <tr <?php if ($c = !$c) { ?>class="alternate"<?php } ?>>
                        <td><input type="checkbox" class="qmn_delete_checkbox" name="delete_results[]" value="<?php echo esc_html($subinfo->id); ?>"></td>

                        <td><?php echo esc_html($subinfo->email_usr_name); ?></td>
                        <td><?php echo esc_html($subinfo->email_address); ?></td>
                        <td>
                            <?php
                            if ($subinfo->email_frm_id != '' OR $subinfo->email_frm_id >= 1) {
                                $form_data = get_post($subinfo->email_frm_id);
                                if ($form_data) {
                                    echo esc_html($form_data->post_title);
                                } else {
                                    echo 'N/A';
                                }
                            }
                            ?>
                        </td>
                        <?php if ($wpulist_settings['enable_geo_ip'] == 1) { ?>
                            <td><?php
                                $geo_ip_data = wpulist_get_ip_location($subinfo->usr_ip);
                                $output_location = "";
                                if ($geo_ip_data) {
                                    if ($geo_ip_data['city']) {
                                        $output_location = $geo_ip_data['city'] . ', ';
                                    }
                                    if ($geo_ip_data['region_name']) {
                                        $output_location = $output_location . $geo_ip_data['region_name'] . ', ';
                                    }
                                    if ($geo_ip_data['country_name']) {
                                        $output_location = $output_location . $geo_ip_data['country_name'];
                                    }
                                    echo $output_location;
                                }
                                ?>
                            </td>
                        <?php } ?>
                        <td><?php
                            $sub_date = $subinfo->date_added;
                            $date = date_create($sub_date);
                            echo date_format($date, get_option('date_format'));
                            ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</form>

<?php if ($num_page > 1) { ?>
    <ul class="paginationsubslst">
        <?php
        for ($i = 1; $i <= $num_page; $i++) {
            if ($i == $paged) {
                ?>
                <li><a class="active" href="#"><?php echo $i; ?></a></li>
                <?php } else { ?>
                <li><a href="admin.php?page=wpulist_subscribers&numlist=<?php echo $num_list; ?>&paged=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php
            }
        }
        ?></ul>
<?php } ?>
