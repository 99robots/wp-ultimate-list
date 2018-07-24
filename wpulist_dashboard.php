<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly                                   ?>
<h1><?php _e('WP Email Subscribe - Dashboard', 'wp-ultimate-list'); ?></h1>
<?php
$wpulist_chart_total_days = 30;
wp_register_style('wpmulti-styles-morris', plugins_url('/css/morris.css', __FILE__));
wp_register_script('wpmulti_scripts_raphel', plugins_url('/js/raphael-min.js', __FILE__), array(), '1.0', true);
wp_register_script('wpmulti_scripts_morris', plugins_url('/js/morris.min.js', __FILE__), array(), '1.0', true);

wp_enqueue_style('wpmulti-styles-morris');
wp_enqueue_script('wpmulti_scripts_raphel');
wp_enqueue_script('wpmulti_scripts_morris');

// jquery ui accordion scripts
wp_register_style('wpul-ui-style', plugins_url('/css/jqui-main.css', __FILE__), false, null);
wp_enqueue_style('wpul-ui-style');
wp_enqueue_script('jquery-ui-accordion');

wp_register_script('wpulist_script_admin_dashboard', plugins_url('/js/wpulist_admin_dashboard.js', __FILE__), '', '1.0', true);
wp_enqueue_script('wpulist_script_admin_dashboard');


//Get wpulist settings array
$wpulist_settings = unserialize(get_option('wpulist_settings'));
?>

<?php
$wpulist_sql_conv_desc = "SELECT email_frm_id, COUNT(email_frm_id) as form_total FROM " . $wpdb->prefix . "wpulist_emails group by email_frm_id order by form_total DESC limit 10";
$wpulist_rows_conv_desc = $wpdb->get_results($wpulist_sql_conv_desc);

if ($wpulist_rows_conv_desc) {
    ?>

    <div class="clear"></div>
    <div class="dshbsttelstlft">

        <h2 class="wpulist_dshh2heading1"><?php _e('Top 10 Converting Forms', 'wp-ultimate-list'); ?></h2>
        <span><em><?php _e('In the last ' . $wpulist_chart_total_days . ' days', 'wp-ultimate-list'); ?></em></span>
        <div id="accordion">
            <?php
            $conv_no = 0;
            foreach ($wpulist_rows_conv_desc as $conv_info) {
                $conv_no++;
                $email_frm_id = $conv_info->email_frm_id;
                $form_data = get_post($email_frm_id);
                $form_views = get_post_meta($email_frm_id, 'wpulist_frm_views', true);
                ?>
                <h3><a href="">
                        <?php
                        if ($form_data) {
                            echo esc_html($form_data->post_title);
                        } else {
                            echo 'N/A';
                        }
                        ?></a>

                    <span class="dshbstte"><?php echo $conv_info->form_total; ?> <font class="wpulist_consmallfnt"><?php _e('Conversions', 'wp-ultimate-list'); ?></font></span>
                    <span class="dshbstte"><?php
                        //this is conversion rate
                        if ($form_views <> '' && $form_views > 0) {
                            $wpul_calc_conv = $conv_info->form_total / $form_views * 100;
                            echo number_format($wpul_calc_conv, 2) . '%';
                        } else {
                            echo '0.00%';
                        }
                        ?> <font class="wpulist_consmallfnt"><?php _e('Con. Rate', 'wp-ultimate-list'); ?></font> |</span>
                    <span class="dshbstte"><?php echo esc_html($form_views); ?> <font class="wpulist_consmallfnt"><?php _e('Views', 'wp-ultimate-list'); ?></font> |</span>
                    <div class="clear"></div>
                </h3><div>
                    <?php
                    $today_date = date('Y-m-d');
                    $today_query = "SELECT COUNT(email_frm_id) as form_total FROM " . $wpdb->prefix . "wpulist_emails where email_frm_id='%s' and date(date_added)='$today_date'";
                    $total_convs = $wpdb->get_row($wpdb->prepare($today_query, array($email_frm_id)), ARRAY_A);


                    $total_conv = array();
                    $total_conv[] = array(
                        'count' => $total_convs['form_total'],
                        'date' => $today_date,
                        'id' => $email_frm_id
                    );
                    $loop_date = $wpulist_chart_total_days - 1;
                    for ($i = 1; $i <= $loop_date; $i++) {
                        $past_date = date('Y-m-d', strtotime("-" . $i . " days"));
                        $today_query = "SELECT COUNT(email_frm_id) as form_total FROM " . $wpdb->prefix . "wpulist_emails where email_frm_id='%s' and date(date_added)='$past_date'";
                        $total_convs = $wpdb->get_row($wpdb->prepare($today_query, array($email_frm_id)), ARRAY_A);
                        $total_conv[] = array(
                            'count' => $total_convs['form_total'],
                            'date' => $past_date,
                            'id' => $email_frm_id
                        );
                    }
                    ?>
                    <input type="hidden" data-id="<?php echo $conv_no; ?>" class="dashboard_charts"/>

                    <?php foreach ($total_conv as $total_conv) { ?>
                        <input type="hidden" class="dashboard_charts_details_<?php echo $conv_no; ?>" data-chart="<?php echo $total_conv['date']; ?>/<?php echo $total_conv['count']; ?>/<?php echo $total_conv['id']; ?>" />
                    <?php } ?>
                    <div id="conv_forms_<?php echo $conv_no; ?>" class="wpulist_convchartdisply"></div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?php include 'wpulist_service_stats.php'; ?>
<div style="clear:both;"></div>
<div class="dshbsttelstcntr">
    <h2 class="wpulist_dshh2heading2"><?php _e('Recent Subscribers', 'wp-ultimate-list'); ?></h2>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Name', 'wp-ultimate-list'); ?></th>
                <th><?php _e('Email', 'wp-ultimate-list'); ?></th>
                <th><?php _e('Form', 'wp-ultimate-list'); ?></th>
                <?php if ($wpulist_settings['enable_geo_ip'] == 1) { ?><th><?php _e('Location', 'wp-ultimate-list'); ?></th><?php } ?>
                <th><?php _e('Date', 'wp-ultimate-list'); ?></th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php
            $wpulist_sql_subscribers = "SELECT id, email_address, email_usr_name, email_frm_id, date_added, usr_ip FROM " . $wpdb->prefix . "wpulist_emails order by date_added DESC limit 10 ";
            if (isset($frm_id)) {
                $wpulist_sql_subscribers .= " WHERE email_frm_id = " . (int) $frm_id;
            }
            if (isset($num_list)) {
                $wpulist_sql_subscribers .= " LIMIT  $start, $num_list";
            }

            $wpulist_rows_subscribers = $wpdb->get_results($wpulist_sql_subscribers);
            $c = true;
            if ($wpulist_rows_subscribers) {
                foreach ($wpulist_rows_subscribers as $subinfo) {
                    ?>

                    <tr <?php if ($c = !$c) { ?>class="alternate"<?php } ?>>

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
                                if ($geo_ip_data) {
                                    if ($geo_ip_data['city']) {
                                        $output_location = $geo_ip_data['city'] . ', ';
                                    }
                                    if ($geo_ip_data['region_name']) {
                                        $output_location = $output_location . $geo_ip_data['region_name'] . ', ';
                                    }
                                    if ($geo_ip_data['region_name']) {
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
    <a href="admin.php?page=wpulist_subscribers" class="dltesele expsubsbtn wpulist_expsubsbtndsh2"><?php _e('View All', 'wp-ultimate-list'); ?></a>

    <div class="wpulist_sepdividerdsh"></div>
    <div class="wpulistjnus">
        <h3><?php _e('Stay Updated and get more customized features directly to your inbox', 'wp-ultimate-list'); ?></h3>
        <div id="mc_embed_signup">
            <form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
                <p><?php _e('Leave your email below', 'wp-ultimate-list'); ?></p>
                <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Email Address" required=""><br />

                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                <div class="wpulist_mcabslte">
                    <input type="text" name="b_b8210f8523d1e31f37518d48e_155e3516fe" value="">
                </div>
                <div class="clear">
                    <input type="submit" value="Join Us" name="subscribe" id="mc-embedded-subscribe" class="bbutton">
                    <strong><?php _e('We promise, your email will not be shared or spammed ever.', 'wp-ultimate-list'); ?></strong>
                </div>
            </form>
        </div>
    </div>

</div>