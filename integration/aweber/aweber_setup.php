<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly        ?>
<?php
add_action('init', 'app_output_buffer');

include ('aweber_api/aweber_api.php');

if ($_POST && $_POST['wpulflag'] == 99) {

}
?>
<div class="nsl-box" style="margin-top: 20px;">
    <h1><?php _e('Set up Aweber', 'wp-ultimate-list'); ?></h1>
    <p><?php _e('Please enter your Access Keys ', 'wp-ultimate-list'); ?></p>
    <div class="maillistset">
        <div id="wpulist_show_msg"></div>
        <?php
        #print($_SESSION['wpulist_aweber_secret']);
        $aweber_api_data = unserialize(get_option('wpulist_api_aweber'));
        $aweber_api_tokens2 = get_option('wpulist_api_aweber_tokens');
        if ($aweber_api_tokens) {
            $aweber_api_tokens = unserialize($aweber_api_tokens2);
        }
        #$aweber_api_data = unserialize($aweber_api_data_ser);
        /* echo '<pre>';
          print_r($aweber_api_data);
          echo '</pre>';
         */
        echo '<br /><br />';
        if ($aweber_api_data) {

            $aweber = new AWeberAPI($aweber_api_data['consumerKey'], $aweber_api_data['consumerSecret']);
            if (!$aweber_api_tokens) {
                display_access_tokens($aweber);
            }
        }
        ?>
        <p></p>
        <form id = "wpulist_aweber_settings" name = "wpulist_aweber_settings" method="post" target="_blank" action="<?php echo plugins_url('get_access_tokens.php', __FILE__); ?>">
            <div class="lft"><strong><?php _e('Aweber Access Key', 'wp-ultimate-list'); ?></strong></div>

            <div class="rght">
                <input type="text" name="aweber_access_key" id="aweber_access_key" value="<?php echo 'AkZGcUtTCntHtN6wdC0uHWGT'; ?>" required="" />
            </div>
            <div class = "clear"></div>
            <div class = "lft"><strong><?php _e('Aweber Access Secret', 'wp-ultimate-list'); ?></strong></div>
            <div class = "rght">
                <input type="text" name="aweber_access_secret" id="aweber_access_secret" value="<?php echo 'mfrSfXBFGUhupexEmr8sZxfcfYO38omprwuLfCaG'; ?>" required="" />
            </div>
            <div class = "clear"></div>
            <input type="hidden" name="wpulflag" value="99" />

            <?php submit_button(__('Get Access Tokens', 'wp-ultimate-list')); ?>
            <?php _e('Note: You will be prompted to login to your Aweber account and authenticate this app to complete the setup.', 'wp-ultimate-list'); ?>
        </form>
    </div>

</div>