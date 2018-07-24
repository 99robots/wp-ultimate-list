<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly                                                                           ?>
<?php

// New data function for MailChimp Using wordpress HTTP API
function wpulist_mailchimp_subscriber_status($email, $status, $list_id, $api_key, $merge_fields = array('FNAME' => '', 'LNAME' => '')) {
    $mc_post_data = array(
        'apikey' => $api_key,
        'email_address' => $email,
        'status' => $status,
        'merge_fields' => $merge_fields
    );

    $api_args = array(
        'method' => 'POST',
        'body' => json_encode($mc_post_data),
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
        )
    );

    $mc_api_url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/';

    $response = wp_remote_post($mc_api_url, $api_args);
    $mc_body = json_decode(wp_remote_retrieve_body($response));
    return $mc_body;
}

function wpulist_mailchimp_get_lists($api_key) {
    $api_dc = substr($api_key, strpos($api_key, '-') + 1); // us5, us8 etc
    $api_url = 'https://' . $api_dc . '.api.mailchimp.com/3.0/lists';

    $api_args = array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
        )
    );
    $response = wp_remote_post($api_url, $api_args);
    $body = json_decode(wp_remote_retrieve_body($response));
    return $body;
}

// New listing lists function for MailChimp Using wordpress HTTP API
function wpulist_mailchimp_getlists($api_key) {



    $api_args = array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key)
        )
    );
    $api_dc = substr($api_key, strpos($api_key, '-') + 1); // us5, us8 etc
    $api_url = 'https://' . $api_dc . '.api.mailchimp.com/3.0/lists';

    $mc_response = wp_remote_get($api_url, $api_args);
    $mc_body = json_decode(wp_remote_retrieve_body($mc_response), true);

    return $mc_body;
}
