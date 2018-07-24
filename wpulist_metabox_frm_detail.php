<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly                                                                                                                                                                   ?>
<?php
// Features meta box in Forms

add_action('add_meta_boxes', 'wpulist_meta_box1');

function wpulist_meta_box1() {
    global $PluginName;
    add_meta_box(
            'wpulist_section1', __('WP Email Subscribe', $PluginName), 'wpulist_frm_box_html', 'wpulistform', 'normal', 'high'
    );
}

function wpulist_frm_box_html($post) {
    global $wpdb;
    wp_register_script('wpulist_script_metabox_frm_detail', plugins_url('/js/wpulist_metabox_frm_detail.js', __FILE__), '', '1.0', true);
    wp_enqueue_script('wpulist_script_metabox_frm_detail');
    wp_register_style('wpulist_style_metabox_frm_detail', plugins_url('/css/wpulist_metabox_frm_detail.css', __FILE__), false, '1.0');
    wp_enqueue_style('wpulist_style_metabox_frm_detail');

    $wpulist_frm_items = get_post_meta($post->ID, 'wpulist_frm_items', true);
    $wpulist_frm_disclaimer = get_post_meta($post->ID, 'wpulist_frm_disclaimer', true);
    $wpulist_fld_conf_msg = get_post_meta($post->ID, 'wpulist_frm_conf_msg', true);
    $wpulist_upload_img = get_post_meta($post->ID, 'wpulist_upload_img', true);

    // Get mailing list manager data for API Key.
    $wpulist_ml_info_option = get_option(('wpulist_ml_info'));
    $wpulist_ml_info = unserialize($wpulist_ml_info_option);

    $wpulist_check_image_width = "300";
    $wpulist_check_image_height = "200";
    ?>

    <div class="wpulist_frm_dtl_main">
        <?php
        add_thickbox();
        #include plugin_dir_path(__FILE__) . '/templates_list.php';
        ?>
        <div id="location-box" class="wpulist_lclbx_sbcrbenone ">
            <?php
            $tpl_list = array(
                '1' => 'theme1/index.php|Theme 1|screenshots/theme1.jpg|750x550',
                '2' => 'theme2/index.php|Theme 2|screenshots/theme2.jpg|750x550',
                '3' => 'theme3/index.php|Theme 3|screenshots/theme3.jpg|750x550',
                '14' => 'theme14/index.php|Theme 14|screenshots/theme14.jpg',
                '15' => 'theme15/index.php|Theme 15|screenshots/theme15.jpg|750x550',
                '49' => 'theme49/index.php|Theme 49|screenshots/theme49.jpg|750x550',
                '55' => 'theme55/index.php|Theme 55|screenshots/theme55.jpg|750x550',
                '62' => 'theme62/index.php|Theme 62|screenshots/theme62.jpg',
                '63' => 'theme63/index.php|Theme 63|screenshots/theme63.jpg|225x142',
                '70' => 'theme70/index.php|Theme 70|screenshots/theme70.jpg|281x234',
                '79' => 'theme79/index.php|Theme 79|screenshots/theme79.jpg|253x203',
                '92' => 'theme92/index.php|Theme 92|screenshots/theme92.jpg|750x550',
            );
            ?>
            <form id="wpulist_frm_tpl" class="wputkthumbslct" name="wpulist_frm_tpl">
                <div id="wpulist_frm_tpl" class="wputkthumbslct">
                    <ul>

                        <?php
                        foreach ($tpl_list as $key => $tpl) {
                            $tpl_info = explode('|', $tpl);
                            ?>
                            <li>
                                <label><img src="" class="theme_image" data-image="<?php echo $tpl_info[2]; ?>">
                                    <input name="chk_tpl_id" id="chk_tpl_id" data-screenshot="<?php echo $tpl_info[2]; ?>" data-name="<?php echo $tpl_info[1]; ?>" data-dimensions="<?php echo $tpl_info[3]; ?>" class="chk_tpl_id" type="radio" value="<?php echo $key; ?>"><?php echo $tpl_info[1] . '<br>'; ?>
                                </label>
                            </li>
                        <?php } ?>
                        <div style="clear:both"></div>

                    </ul>
                    <div class="wpulist_fixbtndiv"><input type="button" value="Submit" id="closelink" class="wpulist_dctvt_btn" ></div>
                </div>
            </form>
        </div>
        <div class="wpulist_frm_dtl_lft">
            <p>
                <a href="#TB_inline?width=600&height=190&inlineId=location-box" class="thickbox action-button"><?php _e('Select Theme', 'wp-ultimate-list') ?></a>

                <input type="hidden" name="tpl_id" id="tpl_id" oninvalid="this.setCustomValidity('<?php _e('Please select a template', 'wp-ultimate-list'); ?>')" value="<?php echo $wpulist_frm_items['tpl_id']; ?>">
                <input type="hidden" id="plugin_url" value="<?php echo plugin_dir_url(__FILE__); ?>" />

            </p>
            <?php
            $tpl_details = isset($wpulist_frm_items['tpl_id']) ? explode("|", $tpl_list[$wpulist_frm_items['tpl_id']]) : null;
            ?>
            <p class="upload_image_sec  <?php
            if ($tpl_details[3]) {
                echo "upload_image_secactv";
            }
            ?>">
                <label><?php _e('Upload Image', 'wp-ultimate-list'); ?></label>
                <input type="text" value="<?php echo $wpulist_upload_img; ?>" name="wpulist_upload_image" id="wpulist_upload_image" /> <input type="button" class="button" value="Upload" id="uploadImageWpulist" />
                <input type="hidden" id="dimensions" />
            </p>
            <p id="wpulist_upload_image_alert"><strong id="wpulist_upload_image_alert_msg"></strong></p>
            <p  class="upload_image_sec"><strong id="wpulist_upload_image_dimen_msg"><?php echo __('Recommended image size:', 'wp-ultimate-list') . $tpl_details[3]; ?></strong></p>
            <p>
                <?php
                $sql_wpul_get_ml = "SELECT id, email_list_name from " . $wpdb->prefix . "wpulist_emails_lists WHERE status = 1 ";
                $get_rows_wpul_ml = $wpdb->get_results($sql_wpul_get_ml);

                $list_data = isset($wpulist_frm_items['fld_ml_id']) ? explode('|', $wpulist_frm_items['fld_ml_id']) : null;
                ?>
                <label for="wpul_ml_id"><?php _e('Select Mailing List', 'wp-ultimate-list'); ?></label><br />
                <?php
                #echo 'list data: ' . $list_data[1];
                ?>
                <select name="wpul_ml_id" id="wpul_ml_id">
                    <?php foreach ($get_rows_wpul_ml as $ml_info) { ?>
                        <option value="<?php echo '1|' . $ml_info->id; ?>" <?php if ($list_data[1] == $ml_info->id) : ?> selected=""<?php endif; ?>><?php echo __('Local - ', 'wp-ultimate-list') . $ml_info->email_list_name; ?></option>
                        <?php
                    }
                    if ($wpulist_ml_info[2]) {
                        // MailChimp
                        // Add Mailchimp mailing lists in drop down

                        $mc_body = wpulist_mailchimp_getlists($wpulist_ml_info[2]);
                        foreach ($mc_body['lists'] as $key => $list_object) {
                            ?>
                            <option value="<?php echo '2|' . $list_object['id']; ?>" <?php if ($list_data[1] == $list_object['id']) : ?> selected=""<?php endif; ?>><?php echo __('MailChimp - ', 'wp-ultimate-list') . $list_object['name']; ?></option>

                            <?php
                        }
                    }
                    if ($wpulist_ml_info[3]) {
                        // Aweber

                        $aweber_api_data = explode('|', $wpulist_ml_info[3]);
                        if ($aweber_api_data) {
                            include (plugin_dir_path(__FILE__) . 'integration/aweber/aweber_api/aweber_api.php');
                        }


                        $aweber = new AWeberAPI($aweber_api_data[2], $aweber_api_data[3]);
                        $account = $aweber->getAccount($aweber_api_data[0], $aweber_api_data[1]);
                        $account_id = $account->id;

                        // Get list of aweber mailing list

                        $aw_get_lists = wpulist_get_aweber_lists($account);
                        #print_r($aw_get_lists);
                        #if (!$aw_get_lists) {
                        foreach ($aw_get_lists->data['entries'] as $aw_list) {
                            ?>
                            <option value="<?php echo '3|' . $aw_list['id']; ?>" <?php if ($list_data[1] == $aw_list['id']) : ?> selected=""<?php endif; ?>><?php echo __('Aweber - ', 'wp-ultimate-list') . $aw_list['name']; ?></option>

                            <?php
                        }
                    }
                    if ($wpulist_ml_info[4]) {
                        // GetResponse
                        // Include Json RPC Client
                        include (plugin_dir_path(__FILE__) . 'integration/getresponse/jsonRPCClient.php');
                        $api_key = $wpulist_ml_info[4]; //Place API key here
                        $api_url = 'http://api2.getresponse.com';

                        # initialize JSON-RPC client
                        $client = new jsonRPCClient($api_url);

                        try {

                            $name = array();
                            $result = $client->get_campaigns($api_key);

//Get Campaigns name and id.
                            foreach ($result as $r) {
                                $name = $r['name'];


                                $result2 = $client->get_campaigns(
                                        $api_key, array(
                                    'name' => array('EQUALS' => $name)
                                        )
                                );
                                $res = array_keys($result2);
                                $CAMPAIGN_IDs = array_pop($res);
                                ?>
                                <option value="<?php echo '4|' . $CAMPAIGN_IDs; ?>" <?php if ($list_data[1] == $CAMPAIGN_IDs) : ?> selected=""<?php endif; ?>><?php echo __('GetResponse - ', 'wp-ultimate-list') . $name; ?></option>
                                <?php
                            }
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                    if ($wpulist_ml_info[8]) {
                        // MadMimi
                        $mm_data = explode('|', $wpulist_ml_info[8]);

                        include (plugin_dir_path(__FILE__) . 'integration/madmimi/MadMimi.class.php');
                        $mailer = new MadMimi($mm_data[1], $mm_data[0]);
                        $mm_lists = $mailer->Lists();
                        // Parse lists using xml data parser
                        $xml = simplexml_load_string($mm_lists) or die("Error: Cannot create object");
                        foreach ($xml->children() as $mm_child) {
                            ?>
                            <option value="<?php echo '8|' . $mm_child[0]['id']; ?>" <?php if ($list_data[1] == $mm_child[0]['id']) : ?> selected=""<?php endif; ?>><?php echo __('MadMimi - ', 'wp-ultimate-list') . $mm_child[0]['name']; ?></option>
                            <?php
                        }
                    }
                    if ($wpulist_ml_info[6]) {
                        $list_srv_info = explode('|', $wpulist_ml_info[6]);
                        include (plugin_dir_path(__FILE__) . 'integration/constantcontact/constantcontact.php');
                        $constantcontact = new ConstantContact($list_srv_info[0], $list_srv_info[1]);
                        $constantcontact_lists = $constantcontact->get_constant_list();
                        if (isset($constantcontact_lists[0]->error_message)) {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('ConstantContact - Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        } else {
                            for ($result_count = 0; $result_count < count($constantcontact_lists); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $constantcontact_lists[$result_count]->id) : ?> selected=""<?php endif; ?> value="<?php _e("6|" . $constantcontact_lists[$result_count]->id, 'wp-ultimate-list'); ?>"><?php _e('ConstantContact - ' . $constantcontact_lists[$result_count]->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                    }
                    if ($wpulist_ml_info[9]) {   //vertical response
                        $list_srv_info = $wpulist_ml_info[9];
                        include (plugin_dir_path(__FILE__) . 'integration/verticalresponse/verticalresponse.php');
                        $VerticalResponse = new VerticalResponse($list_srv_info);
                        $VerticalResponse_lists = $VerticalResponse->get_vertical_response_list();
                        $VerticalResponse_item_lists = $VerticalResponse_lists->items;
                        if (isset($VerticalResponse_lists->items)) {

                            for ($result_count = 0; $result_count < count($VerticalResponse_item_lists); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $VerticalResponse_item_lists[$result_count]->attributes->id) : ?> selected=""<?php endif; ?> value="<?php _e("9|" . $VerticalResponse_item_lists[$result_count]->attributes->id, 'wp-ultimate-list'); ?>"><?php _e('VerticalResponse - ' . $VerticalResponse_item_lists[$result_count]->attributes->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('VerticalResponse - Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//vertical_response
                    if ($wpulist_ml_info[10]) {   //freshmail
                        $list_srv_info = explode('|', $wpulist_ml_info[10]);
                        include (plugin_dir_path(__FILE__) . 'integration/freshmail/freshmail.php');
                        $Freshmail = new Freshmail($list_srv_info[0], $list_srv_info[1]);
                        $Freshmail_list = $Freshmail->get_Freshmail_list();
                        $Freshmail_lists = $Freshmail_list['lists'];

                        if (isset($Freshmail_list['lists'])) {

                            for ($result_count = 0; $result_count < count($Freshmail_lists); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $Freshmail_lists[$result_count]['subscriberListHash']) : ?> selected=""<?php endif; ?> value="<?php _e("10|" . $Freshmail_lists[$result_count]['subscriberListHash'], 'wp-ultimate-list'); ?>"><?php _e('Freshmail - ' . $Freshmail_lists[$result_count]['name'], 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('Freshmail- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//freshmail
                    if ($wpulist_ml_info[11]) {   //vision6
                        $list_srv_info = $wpulist_ml_info[11];
                        include (plugin_dir_path(__FILE__) . 'integration/vision6/vision.php');

                        $Vision6 = new Vision6($list_srv_info);
                        $Vision6_lists = $Vision6->get_Vision6_list();


                        if (isset($Vision6_lists[0]['id'])) {

                            for ($result_count = 0; $result_count < count($Vision6_lists); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $Vision6_lists[$result_count]['id']) : ?> selected=""<?php endif; ?> value="<?php _e("11|" . $Vision6_lists[$result_count]['id'], 'wp-ultimate-list'); ?>"><?php _e('Vision6 - ' . $Vision6_lists[$result_count]['name'], 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('Vision6- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//vision6
                    if ($wpulist_ml_info[12]) {   //customerio
                        $list_srv_info = explode('|', $wpulist_ml_info[12]);

                        $list = 'customerio';
                        ?>
                        <option <?php if ($list_data[1] == $list) : ?> selected=""<?php endif; ?> value="<?php _e("12|customerio", 'wp-ultimate-list'); ?>"><?php _e('Customer.io - Contact', 'wp-ultimate-list') ?></option>
                        <?php
                    }//customerio
                    if ($wpulist_ml_info[13]) {   //sendinblue
                        $list_srv_info = $wpulist_ml_info[13];
                        include (plugin_dir_path(__FILE__) . 'integration/Sendinblue/sendinblue.php');

                        $Sendinblue = new Sendinblue($list_srv_info);
                        $sendinblue_lists = $Sendinblue->get_sendinblue_list();
                        $sendinblue_list = $sendinblue_lists['data']['lists'];
                        if ($sendinblue_lists['code'] == 'success') {

                            for ($result_count = 0; $result_count < count($sendinblue_list); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $sendinblue_list[$result_count]['id']) : ?> selected=""<?php endif; ?> value="<?php _e("13|" . $sendinblue_list[$result_count]['id'], 'wp-ultimate-list'); ?>"><?php _e('Sendinblue - ' . $sendinblue_list[$result_count]['name'], 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('Sendinblue- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//sendinblue
                    if ($wpulist_ml_info[14]) {   //sendinblue
                        $list_srv_info = $wpulist_ml_info[14];
                        include (plugin_dir_path(__FILE__) . 'integration/sendgrid/sendgrid.php');

                        $Sendgrid = new Sendgrid($list_srv_info);
                        $Sendgrid_list = $Sendgrid->get_sendgrid_list();
                        $Sendgrid_lists = $Sendgrid_list->lists;

                        if (isset($Sendgrid_list->lists)) {

                            for ($result_count = 0; $result_count < count($Sendgrid_lists); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $Sendgrid_lists[$result_count]->id) : ?> selected=""<?php endif; ?> value="<?php _e("14|" . $Sendgrid_lists[$result_count]->id, 'wp-ultimate-list'); ?>"><?php _e('SendGrid - ' . $Sendgrid_lists[$result_count]->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('SendGrid- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//sendinblue
                    if ($wpulist_ml_info[15]) {   //Dotmailer
                        $list_srv_info = explode('|', $wpulist_ml_info[15]);
                        include (plugin_dir_path(__FILE__) . 'integration/dotmailer/dotmailer.php');

                        try {
                            $Dotmailer = new DotMailer($list_srv_info[0], $list_srv_info[1], $list_srv_info[2]);
                            $Dotmailer_list = $Dotmailer->get_dotmailer_list();
                        } catch (Exception $e) {
                            // echo $e->getMessage();
                        }


                        if (!empty($Dotmailer_list) && !is_object($Dotmailer_list)) {

                            for ($result_count = 0; $result_count < count($Dotmailer_list); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $Dotmailer_list[$result_count]->id) : ?> selected=""<?php endif; ?> value="<?php _e("15|" . $Dotmailer_list[$result_count]->id, 'wp-ultimate-list'); ?>"><?php _e('DotMailer - ' . $Dotmailer_list[$result_count]->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('DotMailer- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//Dotmailer
                    /* if ($wpulist_ml_info[16]) {   //BenchMarkEmail
                      //echo shell_exec('pear config-get php_dir');
                      //echo "<pre>";
                      // echo exec('whereis php');

                      // echo "</pre>";
                      //echo  exec('which php');
                      $list_srv_info = explode('|', $wpulist_ml_info[16]);
                      include (plugin_dir_path(__FILE__) . 'integration/benchmarkemail/benchmarkemail.php');
                      $BenchMarkerEmail = new BenchMarkerEmail($list_srv_info[0], $list_srv_info[1]);
                      $BenchMarkerEmail_list = $BenchMarkerEmail->get_BenchMarkerEmail_list();
                      // exit;
                      if(!empty($BenchMarkerEmail_list))
                      {

                      for($result_count = 0; $result_count < count($BenchMarkerEmail_list);$result_count++) {
                      ?>
                      <option <?php if ($list_data[1] == $BenchMarkerEmail_list[$result_count]['id']) : ?> selected=""<?php endif; ?> value="<?php _e("16|".$BenchMarkerEmail_list[$result_count]['id'],'wp-ultimate-list');?>"><?php _e('BenchMarkerEmail - '.$BenchMarkerEmail_list[$result_count]['listname'],'wp-ultimate-list') ?></option>
                      <?php
                      }

                      }
                      else
                      {
                      ?>
                      <option value="<?php _e('error' ,'wp-ultimate-list'); ?>"><?php _e('BenchMarkerEmail- Error ' ,'wp-ultimate-list'); ?></option>
                      <?php
                      }

                      }//BenchMarkEmail */
                    if ($wpulist_ml_info[17]) {   //CampaignMonitor
                        $list_srv_info = explode('|', $wpulist_ml_info[17]);
                        include (plugin_dir_path(__FILE__) . 'integration/campaignmonitor/campaignmonitor.php');

                        $CampaignMonitor = new CampaignMonitor($list_srv_info[0], $list_srv_info[1]);
                        $CampaignMonitor_list = $CampaignMonitor->get_campaignmonitor_list();

                        if (!empty($CampaignMonitor_list) && !is_object($CampaignMonitor_list)) {

                            for ($result_count = 0; $result_count < count($CampaignMonitor_list); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $CampaignMonitor_list[$result_count]->ListID) : ?> selected=""<?php endif; ?> value="<?php _e("17|" . $CampaignMonitor_list[$result_count]->ListID, 'wp-ultimate-list'); ?>"><?php _e('CampaignMonitor - ' . $CampaignMonitor_list[$result_count]->Name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            //echo $CampaignMonitor_list->Message;
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('CampaignMonitor- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//CampaignMonitor
                    if ($wpulist_ml_info[18]) {   //ActiveCampaign
                        $list_srv_info = explode('|', $wpulist_ml_info[18]);
                        include (plugin_dir_path(__FILE__) . 'integration/activecampaign/activecampaign.php');

                        $ActiveCampaign = new ActiveCamp($list_srv_info[0], $list_srv_info[1]);
                        $ActiveCampaign_list = $ActiveCampaign->get_activecampaign_list();

                        if ($ActiveCampaign_list['success'] == 1) {

                            for ($result_count = 0; $result_count < count($ActiveCampaign_list) - 5; $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $ActiveCampaign_list[$result_count]->id) : ?> selected=""<?php endif; ?> value="<?php _e("18|" . $ActiveCampaign_list[$result_count]->id, 'wp-ultimate-list'); ?>"><?php _e('ActiveCampaign - ' . $ActiveCampaign_list[$result_count]->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            // echo $ActiveCampaign_list['result_message'];
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('ActiveCampaign- Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//ActiveCampaign
                    if ($wpulist_ml_info[19]) {   //iContact
                        $list_srv_info = explode('|', $wpulist_ml_info[19]);
                        include (plugin_dir_path(__FILE__) . 'integration/icontact/icontact.php');


                        try {
                            $iContact = new iContact($list_srv_info[0], $list_srv_info[1], $list_srv_info[2]);
                            $iContact_list = $iContact->get_icontact_list();
                        } catch (Exception $e) {
                            //echo $e->getMessage();
                        }


                        if (!empty($iContact_list)) {

                            for ($result_count = 0; $result_count < count($iContact_list); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $iContact_list[$result_count]->listId) : ?> selected=""<?php endif; ?> value="<?php _e("19|" . $iContact_list[$result_count]->listId, 'wp-ultimate-list'); ?>"><?php _e('iContact - ' . $iContact_list[$result_count]->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('iContact - Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//iContact
                    if ($wpulist_ml_info[20]) {   //InfusionSoft
                        $list_srv_info = explode('|', $wpulist_ml_info[12]);

                        $list = 'InfusionSoft';
                        ?>
                        <option <?php if ($list_data[1] == $list) : ?> selected=""<?php endif; ?> value="<?php _e("20|InfusionSoft", 'wp-ultimate-list'); ?>"><?php _e('InfusionSoft - Contact', 'wp-ultimate-list') ?></option>
                        <?php
                    }//InfusionSoft
                    if ($wpulist_ml_info[21]) {   //hubspot
                        $list_srv_info = explode('|', $wpulist_ml_info[21]);
                        include (plugin_dir_path(__FILE__) . 'integration/hubspot/hubspot.php');

                        $Hubspot = new HubSpot($list_srv_info[0], $list_srv_info[1], $list_srv_info[2]);
                        $Hubspot_list = $Hubspot->get_hubspot_list();
                        $hub_lists = $Hubspot_list->lists;


                        if (isset($Hubspot_list->lists)) {

                            for ($result_count = 0; $result_count < count($hub_lists); $result_count++) {
                                ?>
                                <option <?php if ($list_data[1] == $hub_lists[$result_count]->listId) : ?> selected=""<?php endif; ?> value="<?php _e("21|" . $hub_lists[$result_count]->listId, 'wp-ultimate-list'); ?>"><?php _e('HubSpot - ' . $hub_lists[$result_count]->name, 'wp-ultimate-list') ?></option>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <option value="<?php _e('error', 'wp-ultimate-list'); ?>"><?php _e('HubSpot - Error ', 'wp-ultimate-list'); ?></option>
                            <?php
                        }
                    }//hubspot
                    if ($wpulist_ml_info[22]) {   //SugarCRM
                        // $list_srv_info = explode('|', $wpulist_ml_info[12]);
                        $list = 'SugarCRM';
                        ?>
                        <option <?php if ($list_data[1] == $list) : ?> selected=""<?php endif; ?> value="<?php _e("22|SugarCRM", 'wp-ultimate-list'); ?>"><?php _e('SugarCRM - Contact', 'wp-ultimate-list') ?></option>
                        <?php
                    }//SugarCRM
                    if ($wpulist_ml_info[23]) {   //salesforce
                        //$list_srv_info = explode('|', $wpulist_ml_info[23]);
                        $list = 'SalesForces';
                        ?>
                        <option <?php if ($list_data[1] == $list) : ?> selected=""<?php endif; ?> value="<?php _e("23|SalesForces", 'wp-ultimate-list'); ?>"><?php _e('SalesForces - Contact', 'wp-ultimate-list') ?></option>
                        <?php
                    }//salesforce
                    ?>

                </select>
            </p>
            <?php
            if ($wpulist_frm_items['fld_sbmt_val'] == '') {
                $sbmt_val = __('Submit', 'wp-ultimate-list');
            } else {
                $sbmt_val = $wpulist_frm_items['fld_sbmt_val'];
            }
            ?>
            <div class="wpulist_nme_enbl">
                <input type="checkbox" name="fld_name" id="fld_name" value="1" <?php if ($wpulist_frm_items['fld_name'] == 1) { ?>checked=""<?php } ?>>
                <label for = "fld_name"><?php _e('Enable "Name" field?', 'wp-ultimate-list'); ?></label>
            </div>
            <div class="wpulist_eml_enbl">
                <input type="checkbox" disabled="true" name="fld_email" id="fld_email" value="1" checked="" required="">
                <label for="fld_email"><?php _e('Enable "Email" field?', 'wp-ultimate-list'); ?></label>
            </div>
            <p>
                <label for="fld_sbmt_val"><?php _e('Submit button text ', 'wp-ultimate-list'); ?></label><br />
                <input type="text" name="fld_sbmt_val" id="fld_sbmt_val" value="<?php echo $sbmt_val; ?>" size="50">
            </p>
            <p>
                <label for="fld_conf_msg"><?php _e('Confirmation OR Thank you message (This is dispalyed after form is submitted) ', 'wp-ultimate-list'); ?></label><br />
                <input type="text" name="fld_conf_msg" id="fld_conf_msg" value="<?php echo $wpulist_fld_conf_msg; ?>" size="50">
            </p>
            <p>
                <label><?php _e('Under the form text (ie;
        Disclaimer) - HTML allowed', 'wp-ultimate-list'); ?></label>
                <textarea name="wpulist_frm_disclaimer" id="wpulist_frm_disclaimer" rows="5"><?php echo ($wpulist_frm_disclaimer); ?></textarea>
            </p>
            <input type="hidden" id="post_id_shortcode" value="<?php echo $post->ID; ?>" />
            <?php $tpl_id = $wpulist_frm_items['tpl_id']; ?>
            <p>
                <?php _e('Copy this shortcode and paste in your page or post editor where you want the form to display.', 'wp-ultimate-list'); ?>
                <br />
            <p id="shortcode_copy"><strong id="shortcode_text"><?php _e('[wpulist_forms frm_id=' . $post->ID . ' tpl_id=' . $tpl_id . ']', 'wp-ultimate-list'); ?></strong></p>


        </div>
        <div class="wpulist_frm_dtl_tmb_rght">
            <h2 id="theme_name"><?php echo $tpl_details[1]; ?></h2>
            <img src="<?php echo plugin_dir_url(__FILE__) . "/themes/" . $tpl_details[2]; ?>" <?php if (!$tpl_details[2]) { ?>class="theme_screenshot_hidden"<?php } ?> id="theme_screenshot" />
        </div>
        <div style="clear:both"></div>
    </div>
    <?php
}

// Save form function
add_action('save_post', 'wpulist_save_form_data');

function wpulist_save_form_data($postID) {
    global $wpdb;
    $frm_items = array();
// called after a form is saved
    if ($parent_id = wp_is_post_revision($postID)) {
        $postID = $parent_id;
    }

    $wpulist_frm_disc = isset($_POST['wpulist_frm_disclaimer']) ? sanitize_text_field($_POST['wpulist_frm_disclaimer']) : null;
    $wpulist_upload_image = isset($_POST['wpulist_upload_image']) ? sanitize_file_name($_POST['wpulist_upload_image']) : null;



    update_post_meta($postID, 'wpulist_frm_disclaimer', $wpulist_frm_disc);

    $frm_items['fld_name'] = isset($_POST['fld_name']) ? sanitize_text_field($_POST['fld_name']) : null;
    $frm_items['fld_email'] = isset($_POST['fld_email']) ? sanitize_email($_POST['fld_email']) : null;
    $frm_items['fld_sbmt_val'] = isset($_POST['fld_sbmt_val']) ? sanitize_text_field($_POST['fld_sbmt_val']) : null;
    $frm_items['fld_ml_id'] = isset($_POST['wpul_ml_id']) ? sanitize_text_field($_POST['wpul_ml_id']) : null;
    $fld_conf_msg = isset($_POST['fld_conf_msg']) ? sanitize_text_field($_POST['fld_conf_msg']) : null;
    $frm_items['tpl_id'] = isset($_POST['tpl_id']) ? sanitize_key($_POST['tpl_id']) : null;


    update_post_meta($postID, 'wpulist_frm_items', $frm_items);
    update_post_meta($postID, 'wpulist_frm_conf_msg', $fld_conf_msg);
    update_post_meta($postID, 'wpulist_upload_img', $wpulist_upload_image);
}
