<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly                             ?>
<?php

// action hook to process the ajax request for storing subscriber data.
add_action('wp_ajax_wpul_process_ajax', 'wpulist_process_frm');
add_action('wp_ajax_nopriv_wpul_process_ajax', 'wpulist_process_frm');

// Process ajax request via this function. and call storing function to save data
function wpulist_process_frm() {
    global $wpdb;

    $frm_id = sanitize_key($_POST['wpul_frm_id']);
    if (
            !isset($_POST['wpulist_frm_' . $frm_id]) || !wp_verify_nonce($_POST['wpulist_frm_' . $frm_id], 'chk_nonce_' . $frm_id)
    ) {
        _e('Invalid form submission', 'wp-ultimate-list');
        exit;
    } else {
        $wpulist_ml_info_option = get_option(('wpulist_ml_info'));
        $wpulist_ml_info = unserialize($wpulist_ml_info_option);

        $do = sanitize_text_field($_POST['do']);

        if ($do == 'wpul_submit_frm') {

            $wpul_frm_id = sanitize_key($_POST['wpul_frm_id']);
            $wpulist_frm_items = get_post_meta($wpul_frm_id, 'wpulist_frm_items', true);
            update_option('wpul_frm_id', $wpul_frm_id);
            $list_data = explode('|', $wpulist_frm_items['fld_ml_id']);
            $wpul_email = sanitize_email($_POST['email_' . $wpul_frm_id]);
            $full_uname = sanitize_text_field($_POST['name_' . $wpul_frm_id]);
            $full_name = explode(' ', $full_uname, 2);
            $list_api_data = explode('|', $wpulist_ml_info[$list_data[0]]);

            // Add subscriber to local mailing list
            wpulist_submit_frm_data($wpul_email, $full_uname, $wpulist_frm_items['fld_ml_id'], $wpul_frm_id);

            // call 3rd party function if active

            switch ($list_data[0]) {
                case 1:
                    // Local only
                    break;
                case 2:
                    // MailChimp
                    // Send subscriber data to mailchimp
                    wpulist_mailchimp_subscriber_status($wpul_email, 'subscribed', $list_data[1], $wpulist_ml_info[2]);
                    break;
                case 3:
                    // Aweber
                    include (plugin_dir_path(__FILE__) . 'integration/aweber/aweber_api/aweber_api.php');

                    $aweber_api_tokens2 = get_option('wpulist_api_aweber_tokens');
                    if ($aweber_api_tokens2) {
                        $aweber_api_tokens = unserialize($aweber_api_tokens2);
                    }

                    $aweber = new AWeberAPI($list_api_data[2], $list_api_data[3]);
                    try {
                        $account = $aweber->getAccount($list_api_data[0], $list_api_data[1]);
                        $account_id = $account->id;

                        $listURL = "/accounts/{$account_id}/lists/{$list_data[1]}";
                        $list = $account->loadFromUrl($listURL);
                        $params = array(
                            'email' => $wpul_email,
                            'name' => $full_uname
                        );
                        $subscribers = $list->subscribers;
                        $new_subscriber = $subscribers->create($params);
                    } catch (AWeberAPIException $exc) {
                        update_option('aweber_api_last_error', serialize($exc));
                    }
                    break;
                case 4:
                    // GetResponse
                    // Include Json RPC Client
                    include (plugin_dir_path(__FILE__) . 'integration/getresponse/jsonRPCClient.php');
                    $api_key = $wpulist_ml_info[4]; //Place API key here
                    $api_url = 'http://api2.getresponse.com';

                    # initialize JSON-RPC client
                    $client = new jsonRPCClient($api_url);
                    // Add contact to selected campaign id
                    try {
                        $result_contact = $client->add_contact(
                                $api_key, array(
                            'campaign' => $list_data[1],
                            'name' => $full_uname,
                            'email' => $wpul_email
                                )
                        );
                    } catch (Exception $e) {
                        update_option('getresponse_api_last_error', serialize($e->getMessage()));
                    }
                    break;
                case 6:
                    // Constant Contact

                    include (plugin_dir_path(__FILE__) . 'integration/constantcontact/constantcontact.php');
                    $constantcontact = new ConstantContact($list_api_data[0], $list_api_data[1]);
                    $constant_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $constantcontact_lists = $constantcontact->add_user($constant_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('constant_api_last_error', serialize($e->getMessage()));
                    }
                    // update_option('constant_api_last_error', serialize($e->getMessage()));

                    break;
                case 8:
                    // MadMimi
                    // Include api class

                    include (plugin_dir_path(__FILE__) . 'integration/madmimi/MadMimi.class.php');
                    $mailer = new MadMimi($list_api_data[1], $list_api_data[0]);
                    try {
                        $mm_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1], 'add_list' => $list_data[1]);
                        $mailer->AddUser($mm_user);
                    } catch (Exception $e) {
                        update_option('madmimi_api_last_error', serialize($e->getMessage()));
                    }
                    break;
                case 9:
                    // Vertical Response

                    include (plugin_dir_path(__FILE__) . 'integration/verticalresponse/verticalresponse.php');
                    $access_key = $wpulist_ml_info[9];
                    $VerticalResponse = new VerticalResponse($access_key);
                    $vertical_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $VerticalResponse->add_vertical_user($vertical_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('verticalresponse_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 10:
                    // freshmail

                    include (plugin_dir_path(__FILE__) . 'integration/freshmail/freshmail.php');
                    $Freshmail = new Freshmail($list_api_data[0], $list_api_data[1]);
                    $fresh_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $Freshmail->add_Freshmail_user($fresh_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('freshmail_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 11:
                    // vision6

                    include (plugin_dir_path(__FILE__) . 'integration/vision6/vision.php');
                    $api_key = $wpulist_ml_info[11];
                    $Vision6 = new Vision6($api_key);
                    $vision6_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $Vision6->add_Vision6_user($vision6_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('vision_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 12:
                    // customerio

                    include (plugin_dir_path(__FILE__) . 'integration/customerio/customerio.php');
                    $Customerio = new Customerio($list_api_data[0], $list_api_data[1]);

                    $customerio_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $Customerio->add_customerio_user($customerio_user);
                    } catch (Exception $e) {
                        update_option('customerio_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 13:
                    // sendinblue

                    include (plugin_dir_path(__FILE__) . 'integration/Sendinblue/sendinblue.php');
                    $api_key = $wpulist_ml_info[13];
                    $Sendinblue = new Sendinblue($api_key);

                    $sendinblue_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $Sendinblue->add_sendinblue_user($sendinblue_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('sendinblue_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 14:
                    // sendgrid

                    include (plugin_dir_path(__FILE__) . 'integration/sendgrid/sendgrid.php');
                    $api_key = $wpulist_ml_info[14];
                    $Sendgrid = new Sendgrid($api_key);

                    $sendgrid_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $get_recipients = $Sendgrid->add_sendgrid_user($sendgrid_user);
                        $recipients = json_decode($get_recipients);
                        $recipients_id = $recipients->persisted_recipients[0];
                        if (!empty($recipients_id)) {
                            $Sendgrid->add_user_in_list($sendgrid_user, $list_data[1], $recipients_id);
                        }
                    } catch (Exception $e) {
                        update_option('sendgrid_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 15:
                    // dotmailer

                    include (plugin_dir_path(__FILE__) . 'integration/dotmailer/dotmailer.php');
                    $Dotmailer = new DotMailer($list_api_data[0], $list_api_data[1], $list_api_data[2]);

                    $dotmailer_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $dotmailer = $Dotmailer->add_dotmailer_user($dotmailer_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('dotmailer_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                /* case 16:
                  // beanchmarkemail

                  include (plugin_dir_path(__FILE__) . 'integration/benchmarkemail/benchmarkemail.php');
                  $BenchMarkerEmail = new BenchMarkerEmail($list_api_data[0], $list_api_data[1]);

                  $beanchmarker_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                  try {
                  $BenchMarkerEmail->add_BenchMarkerEmail_user($beanchmarker_user,$list_data[1]);
                  } catch (Exception $e) {
                  update_option('dotmailer_api_last_error', serialize($e->getMessage()));
                  }

                  break; */

                case 17:
                    // CampaignMonitor

                    include (plugin_dir_path(__FILE__) . 'integration/campaignmonitor/campaignmonitor.php');
                    $CampaignMonitor = new CampaignMonitor($list_api_data[0], $list_api_data[1]);

                    $cm_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $CampaignMonitor->add_campaignmonitor_user($cm_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('CampaignMonitor_api_last_error', serialize($e->getMessage()));
                    }

                    break;

                case 18:
                    // ActiveCampaign

                    include (plugin_dir_path(__FILE__) . 'integration/activecampaign/activecampaign.php');
                    $ActiveCampaign = new ActiveCamp($list_api_data[0], $list_api_data[1]);

                    $ac_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $ActiveCampaign->add_activecampaign_user($ac_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('ActiveCampaign_api_last_error', serialize($e->getMessage()));
                    }

                    break;

                case 19:
                    // icontact

                    include (plugin_dir_path(__FILE__) . 'integration/icontact/icontact.php');
                    $iContact = new iContact($list_api_data[0], $list_api_data[1], $list_api_data[2]);

                    $ic_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $iContact_User_Id = $iContact->add_icontact_user($ic_user);
                        if ($iContact_User_Id->contactId) {
                            $iContact->add_user_in_list($iContact_User_Id->contactId, $list_data[1]);
                        }
                    } catch (Exception $e) {
                        update_option('iContact_api_last_error', serialize($e->getMessage()));
                    }

                    break;

                case 20:
                    // infusionsoft

                    include (plugin_dir_path(__FILE__) . 'integration/infusionsoft/infusionsoft.php');
                    $InfusionSoft = new InfusionSoft($list_api_data[0], $list_api_data[1]);

                    $in_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $id = $InfusionSoft->add_InfusionSoft_user($in_user, "14072744");
                    } catch (Exception $e) {
                        update_option('InfusionSoft_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 21:
                    // hubspot

                    include (plugin_dir_path(__FILE__) . 'integration/hubspot/hubspot.php');
                    $api_key = $wpulist_ml_info[21];
                    $Hubspot = new HubSpot($api_key);

                    $hub_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $vid = $Hubspot->add_hubspot_user($hub_user);
                        $Hubspot->add_user_in_list($vid, $hub_user, $list_data[1]);
                    } catch (Exception $e) {
                        update_option('hubspot_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 22:

                    include (plugin_dir_path(__FILE__) . 'integration/sugarcrm/sugarcrm.php');
                    $SugarCrm = new SugarCrm($list_api_data[0], $list_api_data[1], $list_api_data[2]);

                    $sugar_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $session = $SugarCrm->check_login();
                        $session_id = $session->id;
                        $SugarCrm->add_sugar_user($session_id, $sugar_user);
                    } catch (Exception $e) {
                        update_option('sugarcrm_api_last_error', serialize($e->getMessage()));
                    }

                    break;
                case 23:

                    include (plugin_dir_path(__FILE__) . 'integration/salesforce/salesforces.php');

                    $salesforces = new salesforces($list_api_data[0], $list_api_data[1], $list_api_data[2], $list_api_data[3], $list_api_data[4]);
                    if (empty($full_name[1])) {
                        $full_name[1] = $wpul_email;
                    }
                    $sf_user = array('email' => $wpul_email, 'firstName' => $full_name[0], 'lastName' => $full_name[1]);
                    try {
                        $response = $salesforces->check_login();
                        $instance_url = $response->instance_url;
                        $access_token = $response->access_token;

                        if (!empty($instance_url)) {
                            $salesforces->add_salesforce_user($sf_user, $instance_url, $access_token);
                        }
                    } catch (Exception $e) {
                        update_option('salesforce_api_last_error', serialize($e->getMessage()));
                    }

                    break;
            }

            echo get_post_meta($wpul_frm_id, 'wpulist_frm_conf_msg', true);
            wp_die();
        }
    }
}

// Function to store data in local database and send to 3rd party if active.

function wpulist_submit_frm_data($email, $user_name, $eml_list_id, $frm_id) {
    global $wpdb;
    $wpulist_usr_ip = $_SERVER['REMOTE_ADDR'];

    $wpul_sql_check = "SELECT email_address FROM " . $wpdb->prefix . "wpulist_emails WHERE email_address = '%s' AND email_list_id = '%s' ";
    $wpulist_emails = $wpdb->get_results($wpdb->prepare($wpul_sql_check, array($email, $eml_list_id)));
    if (!$wpulist_emails) {
        $wpul_sql_save = "INSERT INTO " . $wpdb->prefix . "wpulist_emails (email_address, email_usr_name, email_list_id, email_frm_id, usr_ip, date_added) VALUES ('$email', '$user_name', '$eml_list_id', $frm_id, '$wpulist_usr_ip', now()) ";
        $wpdb->query($wpul_sql_save);
    }
}

// save 3rd party integration settings
function wpulist_update_list_integration_settings() {
    global $wpdb;

    $wpul_vendor_lists_serialized = get_option('wpulist_ml_info', 0);
    $wpul_vendor_lists = unserialize($wpul_vendor_lists_serialized);

    $ml_manager = sanitize_text_field($_POST['ml_manager']);
    $ml_api_key = sanitize_text_field($_POST['wpu_api_key']);

    if ($ml_manager == 3) {
        $aweber_api_data = unserialize(get_option('wpulist_api_aweber'));
        $aweber_key = sanitize_text_field($_POST['aweber_access_key']);
        $aweber_secret = sanitize_text_field($_POST['aweber_access_secret']);
        $counsumer_key = sanitize_text_field($aweber_api_data['consumerKey']);
        $counsumer_secret = $aweber_api_data['consumerSecret'];
        $ml_api_key = $aweber_key . '|' . $aweber_secret . '|' . $counsumer_key . '|' . $counsumer_secret;
    }

    if ($ml_manager == 8) {
        $service_username = sanitize_text_field($_POST['service_username']);
        $ml_api_key = $ml_api_key . '|' . $service_username;
    }
    if ($ml_manager == 6) {
        $service_token = sanitize_text_field($_POST['service_token']);
        $default_api = "dmsav862cjuqendzzuey4p3e";
        $ml_api_key = $default_api . '|' . $service_token;
    }
    if ($ml_manager == 9) {
        $service_token = sanitize_text_field($_POST['service_vr_token']);
        $ml_api_key = $service_token;
    }
    if ($ml_manager == 10) {
        $service_token = sanitize_text_field($_POST['service_fr_token']);
        $ml_api_key = $ml_api_key . '|' . $service_token;
    }
    if ($ml_manager == 12) {
        $service_token = sanitize_text_field($_POST['service_cio_token']);
        $ml_api_key = $ml_api_key . '|' . $service_token;
    }
    if ($ml_manager == 15) {
        $service_token_url = sanitize_text_field($_POST['service_durl_token']);
        $service_token_username = sanitize_text_field($_POST['service_duname_token']);
        $service_token_password = sanitize_text_field($_POST['service_dpass_token']);
        $ml_api_key = $service_token_url . '|' . $service_token_username . '|' . $service_token_password;
    }
    if ($ml_manager == 16) {
        $service_token_user = sanitize_text_field($_POST['service_bmu_token']);
        $service_token_pass = sanitize_text_field($_POST['service_bmp_token']);
        $ml_api_key = $service_token_user . '|' . $service_token_pass;
    }
    if ($ml_manager == 17) {
        $service_token = sanitize_text_field($_POST['service_cm_token']);
        $ml_api_key = $ml_api_key . '|' . $service_token;
    }
    if ($ml_manager == 18) {
        $service_token = sanitize_text_field($_POST['service_ac_token']);
        $ml_api_key = $ml_api_key . '|' . $service_token;
    }
    if ($ml_manager == 19) {
        $service_token_appid = sanitize_text_field($_POST['service_icid_token']);
        $service_token_app_pass = sanitize_text_field($_POST['service_icpass_token']);
        $service_token_app_user = sanitize_text_field($_POST['service_icuser_token']);
        $ml_api_key = $service_token_appid . '|' . $service_token_app_pass . '|' . $service_token_app_user;
    }
    if ($ml_manager == 20) {
        $service_token = sanitize_text_field($_POST['service_in_token']);
        $ml_api_key = $ml_api_key . '|' . $service_token;
    }
    if ($ml_manager == 22) {
        $service_token_url = sanitize_text_field($_POST['service_surl_token']);
        $service_token_username = sanitize_text_field($_POST['service_suname_token']);
        $service_token_password = sanitize_text_field($_POST['service_spass_token']);
        $ml_api_key = $service_token_url . '|' . $service_token_username . '|' . $service_token_password;
    }
    if ($ml_manager == 23) {
        $service_token_key = sanitize_text_field($_POST['service_sfkey_token']);
        $service_token_id = sanitize_text_field($_POST['service_sfskey_token']);
        $service_token_username = sanitize_text_field($_POST['service_sfuname_token']);
        $service_token_pass = sanitize_text_field($_POST['service_sfpass_token']);
        $service_token_security = sanitize_text_field($_POST['service_sfsecurity_token']);
        $ml_api_key = $service_token_key . '|' . $service_token_id . '|' . $service_token_username . '|' . $service_token_pass . '|' . $service_token_security;
    }
    $ml_manager_info = array(
        $ml_manager => $ml_api_key,
    );
    $wpul_vendor_lists[$ml_manager] = $ml_api_key;
    update_option('wpulist_ml_info', serialize($wpul_vendor_lists));

    _e('Integration Settings Updated.', 'wp-ultimate-list');
    die();
}

add_action('wp_ajax_wpul_integration_settings', 'wpulist_update_list_integration_settings');


