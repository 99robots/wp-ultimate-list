<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly   ?>
<h1><?php _e('WP Ultimate List - Debug Mode', 'wp-ultimate-list'); ?></h1>
<?php
//Get wpulist settings array
$wpulist_settings = unserialize(get_option('wpulist_settings'));
?>

<div></div>
<div class="dshbsttelstlft">

    <div class="activemaillistbox">
        <h1><?php _e('Active Services', 'wp-ultimate-list'); ?></h1>
        <div class="clear"></div>

        <table width="100%" border="0">
            <?php
            $wpul_vendor_lists_serialized = get_option('wpulist_ml_info', 0);
            $wpul_vendor_lists = unserialize($wpul_vendor_lists_serialized);
            ?>
            <tr>
                <td width="25%">
                    <strong><?php _e('Service', 'wp-ultimate-list'); ?></strong></td>
                <td width="65%"><strong><?php _e('API Key', 'wp-ultimate-list'); ?></strong></td>

            </tr>
            <?php
            if ($wpul_vendor_lists) {
                foreach ($wpul_vendor_lists as $listidval => $listname) {
                    ?>
                    <tr>
                        <td width="25%">
                            <?php
                            ?>
                            <strong><?php echo $ml_manager[$listidval]; ?></strong></td>
                        <td width="65%"><?php echo $listname; ?></td>

                    </tr>
                    <?php
                }
            }
            ?>

        </table>
    </div>


</div>
