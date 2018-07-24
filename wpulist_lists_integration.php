<?php if (!defined('ABSPATH')) exit; ?>
<?php
$wpulist_ml_info_option = get_option(('wpulist_ml_info'));
$wpulist_ml_info = isset($wpulist_ml_info_option) ? unserialize($wpulist_ml_info_option) : null;
wp_register_script('wpulist_script_list_integration', plugins_url('/js/wpulist_list_integration.js', __FILE__), '', '1.0', true);
wp_enqueue_script('wpulist_script_list_integration');
if (session_id()) {

} else {
    session_start();
    $_SESSION['site_url_vr'] = admin_url('admin.php?page=wpulist_mailing_lists_integration');
}

if (isset($_REQUEST['accessSecret'])) {
    $accessSecret = sanitize_text_field($_GET['accessSecret']);
}
if (isset($_REQUEST['accessKey'])) {
    $accessKey = sanitize_text_field($_GET['accessKey']);
}
if (isset($_REQUEST['vr_access_token'])) {
    $vr_access_token = sanitize_text_field($_GET['vr_access_token']);
}

if (isset($yes_submit)) {
    $yes_submit = sanitize_text_field($_REQUEST["yes_submit"]);
    $wpulist_ml_info[sanitize_text_field($_REQUEST["deacitvateservice"])] = "";
    update_option('wpulist_ml_info', serialize($wpulist_ml_info));
}
$wpulist_ml_info_option = get_option(('wpulist_ml_info'));
$wpulist_ml_info = unserialize($wpulist_ml_info_option);

$mailchimp = isset($wpulist_ml_info[2]) ? $wpulist_ml_info[2] : null;
$mailchimp_array = explode("|", $mailchimp);

$awber = isset($wpulist_ml_info[3]) ? $wpulist_ml_info[3] : null;
$awber_array = explode("|", $awber);

$icontact = isset($wpulist_ml_info[19]) ? $wpulist_ml_info[19] : null;
$icontact_array = explode("|", $icontact);


$ml_managers = get_option('wpulist_list_managers');
$ml_manager = unserialize($ml_managers);

//details access link and help link
$ml_managers_details = get_option('wpulist_list_managers_details');
$ml_manager_detail = unserialize($ml_managers_details);

$mailchimp_detail = isset($ml_manager_detail[2]) ? $ml_manager_detail[2] : null;
$mailchimp_dtl_arr = explode("|", $mailchimp_detail);

$awber_dtl = isset($ml_manager_detail[3]) ? $ml_manager_detail[3] : null;
$awber_dtl_arr = explode("|", $awber_dtl);

$GetResponse_dtl = isset($ml_manager_detail[4]) ? $ml_manager_detail[4] : null;
$GetResponse_dtl_arr = explode("|", $GetResponse_dtl);

$icontact_dtl = isset($ml_manager_detail[19]) ? $ml_manager_detail[19] : null;
$icontact_dtl_arr = explode("|", $icontact_dtl);
?>
<div class="nsl-box nsl-box22">
    <h1><?php _e('Mailing List Manager', 'wp-ultimate-list'); ?></h1>

    <?php add_thickbox(); ?>
    <div class = "clear"></div>
    <div class="wpulist_mlmgridboxes">
        <ul>

            <?php
            foreach ($ml_manager as $ml_id => $ml_name) {
                if ($ml_id < 2)
                    continue;
                ?>
                <li>
                    <?php
                    $ml_managers_dtl = $ml_manager_detail[$ml_id];
                    $ml_m_dtl = explode("|", $ml_managers_dtl);
                    ?>
                    <a href="<?php echo $ml_m_dtl[2]; ?>" target = "_blank" class="wpulist_hlp_int">?</a>
                    <div class="thmb"><img src="<?php echo plugins_url('integration/logos/' . $ml_id . ".png", __FILE__); ?>" /></div>
                    <a href="javascript:void(0);" data-id="<?php echo $ml_id; ?>" class="wpulist_atvmlmlistpg <?php
                    if ($wpulist_ml_info[$ml_id] == '') {
                        echo "wpulist_dshsrvlgbtnactv";
                    } else {
                        echo "wpulist_dshsrvlgbtnnone";
                    }
                    ?>" ><?php _e('Activate', 'wp-ultimate-list'); ?></a>
                    <a href="javascript:void(0);" data-id="<?php echo $ml_id; ?>" class="wpulist_edtmlmlistpg <?php
                    if ($wpulist_ml_info[$ml_id] != '') {
                        echo "wpulist_dshsrvlgbtnactv";
                    } else {
                        echo "wpulist_dshsrvlgbtnnone";
                    }
                    ?>">Edit</a>
                    <a href="javascript:void(0);" data-id="<?php echo $ml_id; ?>" class="wpulist_deatvmlmlistpg <?php
                    if ($wpulist_ml_info[$ml_id] != '') {
                        echo "wpulist_dshsrvlgbtnactv";
                    } else {
                        echo "wpulist_dshsrvlgbtnnone";
                    }
                    ?>"><?php _e('Deactivate', 'wp-ultimate-list'); ?></a>
                </li>
            <?php } ?>
        </ul>


    </div>
    <div id="deactivatepopup" class="wpulist_dshsrvkeyfldnone">
        <p>
        <form method="post" action="" class="deactivaeform wpulist_sttngppup">
            <?php _e('Are you sure you want to deactivate this service? This will remove the API details completely?', 'wp-ultimate-list'); ?>
            <br />
            <input type="hidden" value="" class="deacitvateservice" name="deacitvateservice" />
            <input type="submit" value="Yes" name="yes_submit" class="wpulist_sttngppupyes" />
            <input type="button" value="No" name="no_submit" class="wpulist_sttngppupno" />
        </form>
        </p>
    </div>
    <a href="#TB_inline?width=600&height=190&inlineId=deactivatepopup" class="thickbox wpulist_sttngico deactivatelink wpulist_dshsrvkeyfldnone"><?php _e('Deactivate', 'wp-ultimate-list'); ?></a>

    <div class = "clear"></div>

</div>
<div class="maillistsetmnbx">
    <div class="maillistset">
        <div id="wpulist_show_msg"></div>

        <form id = "wpulist_ml_settings" name = "wpulist_ml_settings" method = "post">
            <div class = "lft"><strong><?php _e('Mailing list service', 'wp-ultimate-list');
            ?></strong></div>
            <div class="rght">
                <select name="ml_manager">
                    <?php
                    foreach ($ml_manager as $ml_id => $ml_name) {
                        $apikey = "";
                        if (isset($wpulist_ml_info[$ml_id])) {
                            $data_Edit = $wpulist_ml_info[$ml_id];
                            $data_Edit_array = explode("|", $data_Edit);
                            $apikey = $data_Edit_array[0];
                        }
                        ?>
                        <option apikey="<?php echo $apikey; ?>" value = "<?php echo $ml_id; ?>" <?php if (isset($wpulist_ml_info[0]) && $wpulist_ml_info[0] == $ml_id || (isset($accessSecret) && $ml_id == 3) || (isset($vr_access_token) && $ml_id == 9)) : ?> selected=""<?php endif; ?>><?php echo $ml_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="servier_username" class="wpulist_dshsrvkeyfldnone">
                <div class = "clear"></div>
                <div class = "lft"><strong><?php _e('MadMimi Username or Email', 'wp-ultimate-list'); ?></strong></div>
                <div class = "rght">
                    <input type="text" name="service_username" class="field_2" value="<?php echo $MadMimi_array[1]; ?>" />
                </div>

            </div>
            <?php $aweber_data = explode("|", $wpulist_ml_info[3]); ?>
            <div id="aweber_access_key" style="width:100%" class="<?php
            if (isset($accessSecret)) {
                echo "wpulist_dshsrvkeyfldactv";
            } else {
                echo "wpulist_dshsrvkeyfldnone";
            }
            ?>">
                <div class = "clear"></div>
                <div class = "lft"><strong><?php _e('Access Key', 'wp-ultimate-list'); ?></strong>
                    <br /><input type="text" name="aweber_access_key" class="field_3" style="width:100%; padding:7px;" value="<?php
                    if (isset($accessSecret)) {
                        echo $accessKey;
                    } else {
                        echo $aweber_data[0];
                    }
                    ?>" />
                </div>

            </div>
            <div id="aweber_access_secret" style="width:100%" class="<?php
            if (isset($accessSecret)) {
                echo "wpulist_dshsrvkeyfldactv";
            } else {
                echo "wpulist_dshsrvkeyfldnone";
            }
            ?>">
                <div class = "clear"></div>
                <div class = "lft"><strong><?php _e('Access Secret', 'wp-ultimate-list'); ?></strong>
                    <br /> <input type="text" name="aweber_access_secret" class="field_3" style="width:100%; padding:7px;" value="<?php
                    if (isset($accessSecret)) {
                        echo $accessSecret;
                    } else {
                        echo $aweber_data[1];
                    }
                    ?>" /><br />
                    <a href = "javascript: void(0)" id = "href_val_aw"><?php _e('Get access token', 'wp-ultimate-list'); ?></a>
                    | <a href = "<?php echo esc_html($awber_dtl_arr[4]); ?>" id = "href_val_aw2" target="_blank"><?php _e('Signup for Free Aweber Account', 'wp-ultimate-list'); ?></a>
                </div>

            </div>
            <div class = "clear"></div>
            <div id="servier_token" class="wpulist_dshsrvkeyfldnone">

                <div class = "lft"><strong><?php _e('Constant Contact Access Token', 'wp-ultimate-list'); ?></strong></div>

                <div class = "rght">
                    <input type="text" name="service_token" value="<?php echo $constantcontact_array[1]; ?>" />
                    <a id = "href_val_cc" href = "" target = "_blank"><?php echo $constantcontact_dtl_arr[0]; ?></a>

                </div>
            </div>

            <div id = 'servier_api' class="<?php
            if (isset($accessSecret) || isset($vr_access_token)) {
                echo "wpulist_dshsrvkeyfldnone";
            } else {
                echo "wpulist_dshsrvkeyfldactv";
            }
            ?>">
                <div class = "clear"></div>
                <div class = "lft"><strong><?php _e('API Key', 'wp-ultimate-list'); ?></strong></div>
                <div class = "rght">
                    <input type="text" id="wpu_api_key" name="wpu_api_key" value="<?php echo $wpulist_ml_info[1]; ?>" />
                    <a href="#" id="href_val" target = "_blank" ><?php _e('Get API Key', 'wp-ultimate-list'); ?></a>
                    | <a href="#" id="href_val2" target = "_blank" ><?php _e('Free Signup', 'wp-ultimate-list'); ?></a>
                </div>
            </div>
            <div class = "clear"></div>
            <?php wp_nonce_field('chk_nonce_wpulist_ml_settings', 'wpulist_frm_wpulist_ml_settings'); ?>
            <?php submit_button(); ?>
        </form>
        <form action="<?php echo plugins_url('integration/aweber/get_access_tokens.php', __FILE__); ?>" method="post" id="getAweber" >
            <input type="hidden" name="site_url" value="<?php echo admin_url('admin.php ?page=wpulist_mailing_lists_integration'); ?>" />
        </form>
    </div>
</div>
<input type="hidden" id="mailchimp_dtl_arr_1" value="<?php echo $mailchimp_dtl_arr[1]; ?>" />
<input type="hidden" id="mailchimp_dtl_arr_2" value="<?php echo $mailchimp_dtl_arr[0]; ?>" />
<input type="hidden" id="mailchimp_dtl_arr_3" value="<?php echo $mailchimp_dtl_arr[3]; ?>" />
<input type="hidden" id="mailchimp_dtl_arr_4" value="<?php echo $mailchimp_dtl_arr[4]; ?>" />
<input type="hidden" id="GetResponse_dtl_arr_1" value="<?php echo $GetResponse_dtl_arr[1]; ?>" />
<input type="hidden" id="GetResponse_dtl_arr_2" value="<?php echo $GetResponse_dtl_arr[0]; ?>" />
<input type="hidden" id="GetResponse_dtl_arr_3" value="<?php echo $GetResponse_dtl_arr[3]; ?>" />
<input type="hidden" id="GetResponse_dtl_arr_4" value="<?php echo $GetResponse_dtl_arr[4]; ?>" />
<input type="hidden" id="HubSpot_dtl_arr_1" value="<?php echo $HubSpot_dtl_arr[1]; ?>" />
<input type="hidden" id="HubSpot_dtl_arr_2" value="<?php echo $HubSpot_dtl_arr[0]; ?>" />
<input type="hidden" id="Vision6_dtl_arr_1" value="<?php echo $Vision6_dtl_arr[1]; ?>" />
<input type="hidden" id="Vision6_dtl_arr_2" value="<?php echo $Vision6_dtl_arr[0]; ?>" />
<input type="hidden" id="SendInBlue_dtl_arr_1" value="<?php echo $SendInBlue_dtl_arr[1]; ?>" />
<input type="hidden" id="SendInBlue_dtl_arr_1" value="<?php echo $SendInBlue_dtl_arr[0]; ?>" />
<input type="hidden" id="SendGrid_dtl_arr_1" value="<?php echo $SendGrid_dtl_arr[1]; ?>" />
<input type="hidden" id="SendGrid_dtl_arr_2" value="<?php echo $SendGrid_dtl_arr[0]; ?>" />
<input type="hidden" id="MadMimi_dtl_arr_1" value="<?php echo $MadMimi_dtl_arr[1]; ?>" />
<input type="hidden" id="MadMimi_dtl_arr_2" value="<?php echo $MadMimi_dtl_arr[0]; ?>" />
<input type="hidden" id="constantcontact_dtl_arr_1" value="<?php echo $MadMimi_dtl_arr[1]; ?>" />
<input type="hidden" id="constantcontact_dtl_arr_2" value="<?php echo $MadMimi_dtl_arr[0]; ?>" />
<input type="hidden" id="VerticalResponse_dtl_arr_1" value="<?php echo $VerticalResponse_dtl_arr[1]; ?>" />
<input type="hidden" id="VerticalResponse_dtl_arr_2" value="<?php echo $VerticalResponse_dtl_arr[0]; ?>" />
<input type="hidden" id="FreshMail_dtl_arr_1" value="<?php echo $FreshMail_dtl_arr[1]; ?>" />
<input type="hidden" id="FreshMail_dtl_arr_2" value="<?php echo $FreshMail_dtl_arr[0]; ?>" />
<input type="hidden" id="Customer_dtl_arr_1" value="<?php echo $Customer_dtl_arr[1]; ?>" />
<input type="hidden" id="Customer_dtl_arr_2" value="<?php echo $Customer_dtl_arr[0]; ?>" />
<input type="hidden" id="DotMailer_dtl_arr_1" value="<?php echo $DotMailer_dtl_arr[1]; ?>" />
<input type="hidden" id="DotMailer_dtl_arr_2" value="<?php echo $DotMailer_dtl_arr[0]; ?>" />
<input type="hidden" id="CampaignMonitor_dtl_arr_1" value="<?php echo $CampaignMonitor_dtl_arr[1]; ?>" />
<input type="hidden" id="CampaignMonitor_dtl_arr_2" value="<?php echo $CampaignMonitor_dtl_arr[0]; ?>" />
<input type="hidden" id="ActiveCampaign_dtl_arr_1" value="<?php echo $ActiveCampaign_dtl_arr[1]; ?>" />
<input type="hidden" id="ActiveCampaign_dtl_arr_2" value="<?php echo $ActiveCampaign_dtl_arr[0]; ?>" />
<input type="hidden" id="icontact_dtl_arr_1" value="<?php echo $icontact_dtl_arr[1]; ?>" />
<input type="hidden" id="icontact_dtl_arr_2" value="<?php echo $icontact_dtl_arr[0]; ?>" />
<input type="hidden" id="InfusionSoft_dtl_arr_1" value="<?php echo $InfusionSoft_dtl_arr[1]; ?>" />
<input type="hidden" id="InfusionSoft_dtl_arr_2" value="<?php echo $InfusionSoft_dtl_arr[0]; ?>" />
<input type="hidden" id="SugarCrm_dtl_arr_1" value="<?php echo $SugarCrm_dtl_arr[1]; ?>" />
<input type="hidden" id="SugarCrm_dtl_arr_2" value="<?php echo $SugarCrm_dtl_arr[0]; ?>" />
<input type="hidden" id="SalesForces_dtl_arr_1" value="<?php echo $SalesForces_dtl_arr[1]; ?>" />
<input type="hidden" id="SalesForces_dtl_arr_2" value="<?php echo $SalesForces_dtl_arr[0]; ?>" />
<input type="hidden" id="awber_dtl_arr_1" value="<?php echo $awber_dtl_arr[1]; ?>" />
<input type="hidden" id="awber_dtl_arr_2" value="<?php echo $awber_dtl_arr[0]; ?>" />