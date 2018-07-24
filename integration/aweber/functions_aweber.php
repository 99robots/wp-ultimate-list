<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly     ?>
<?php

function app_output_buffer() {
    ob_start();
}

// soi_output_buffer
add_action('init', 'app_output_buffer');

function display_access_tokens($aweber) {
    global $wpdb;
    $aweber_api_tokens_ex = get_option('wpulist_api_aweber_tokens');

    print_r($aweber_api_tokens_ex);
    if ($aweber_api_tokens_ex) {
        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {

            $aweber->user->requestToken = $_GET['oauth_token'];
            $aweber->user->verifier = $_GET['oauth_verifier'];
            $aweber->user->tokenSecret = $_SESSION['wpulist_aweber_secret']; //$_COOKIE['secret'];

            list($accessTokenKey, $accessTokenSecret) = $aweber->getAccessToken();

            $aweber_api_tokens = array(
                'accessTokenKey' => $accessTokenKey,
                'accessTokenSecret' => $accessTokenSecret,
                'accessSecret' => $_SESSION['wpulist_aweber_secret'],
            );

            // add aweber access tokens in options table
            update_option('wpulist_api_aweber_tokens', serialize($aweber_api_tokens));

            // Get_option of mailing list service provides and add Aweber to activate it.
            $wpulist_ml_info_option = get_option(('wpulist_ml_info'));
            $wpulist_ml_info = unserialize($wpulist_ml_info_option);

            $wpulist_ml_info[3] = 'Aweber';
            update_option('wpulist_ml_info', serialize($wpulist_ml_info));

            _e('Thank you for authorizing. Your Aweber account is successfully connected.', 'wp-ultimate-list');
        } else {

            if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                print "This request must be made from a web browser\n";
                exit;
            }

            $callbackURL = wpulist_get_self();
            list($key, $secret) = $aweber->getRequestToken($callbackURL);
            $authorizationURL = $aweber->getAuthorizeUrl();

            #setcookie('secret', $secret);
            $_SESSION['wpulist_aweber_secret'] = $secret;

            wp_redirect($authorizationURL);
        }
    }
}

function wpulist_get_self() {
    return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function display_available_lists($account) {
    print "Please add one of the lines of PHP Code below to the top of your script for the proper list<br>" .
            "then click <a href=\"" . wpulist_get_self() . "\">here</a> to continue<p>";

    $listURL = "/accounts/{$account->id}/lists/";
    $lists = $account->loadFromUrl($listURL);
    foreach ($lists->data['entries'] as $list) {
        print "\$list_id = '{$list['id']}'; // list name:{$list['name']}\n</br>";
    }
}

function wpulist_get_aweber_lists($account) {
    $listURL = "/accounts/{$account->id}/lists/";
    $lists = $account->loadFromUrl($listURL);
    return $lists;
}

function wpulist_aweber_subscribe($email_address, $user_name, $list_id) {

    $aweber_api_data = unserialize(get_option('wpulist_api_aweber'));
    if ($aweber_api_data) {
        include (plugin_dir_path(__FILE__) . 'integration/aweber/aweber_api/aweber_api.php');
    }
    $aweber_api_tokens2 = get_option('wpulist_api_aweber_tokens');
    if ($aweber_api_tokens2) {
        $aweber_api_tokens = unserialize($aweber_api_tokens2);
    }

    $aweber = new AWeberAPI($aweber_api_data['consumerKey'], $aweber_api_data['consumerSecret']);

    try {
        $account = $aweber->getAccount($aweber_api_tokens['accessTokenKey'], $aweber_api_tokens['accessTokenSecret']);
        $account_id = $account->id;

        $listURL = "/accounts/{$account_id}/lists/{$list_id}";
        $list = $account->loadFromUrl($listURL);
        $params = array(
            'email' => '$email_address',
            'name' => '$user_name'
        );
        $subscribers = $list->subscribers;
        $new_subscriber = $subscribers->create($params);
    } catch (AWeberAPIException $exc) {
        print "<h3>AWeberAPIException:</h3>";
        print " <li> Type: $exc->type              <br>";
        print " <li> Msg : $exc->message           <br>";
        print " <li> Docs: $exc->documentation_url <br>";
        print "<hr>";

        update_option('aweber_api_last_error', serialize($exc));
    }
}
