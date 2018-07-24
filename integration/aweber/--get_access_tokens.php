<?php #if (!defined('ABSPATH')) exit; // Exit if accessed directly        ?>
<?php
session_start();
$accessKeyURL = ($_GET['accessKey']);
$accessSecretURL = ($_GET['accessSecret']);
$Post_Site_URL = ($_POST['site_url']);

if (isset($accessKeyURL)) {
    ?>
    <script>
        window.location.href = '<?php echo $_SESSION['site_url'] . "&accessKey=" . $accessKeyURL . "&accessSecret=" . $accessSecretURL; ?>';
    </script>
    <?php
}
if (isset($Post_Site_URL)) {
    $_SESSION['site_url'] = $Post_Site_URL;
}

require_once('aweber_api/aweber_api.php');

$wpulist_aweber_keys = array(
    'consumerKey' => 'AkZGcUtTCntHtN6wdC0uHWGT',
    'consumerSecret' => 'mfrSfXBFGUhupexEmr8sZxfcfYO38omprwuLfCaG',
);


// Step 1: assign these values from https://labs.aweber.com/apps
$consumerKey = $wpulist_aweber_keys['consumerKey'];
$consumerSecret = $wpulist_aweber_keys['consumerSecret'];

//// Step 2: load this PHP file in a web browser, and follow the instructions to set
// the following variables:
$accessKey = '';
$accessSecret = '';
$list_id = ''; // list name:Aw Default List



if (!$consumerKey || !$consumerSecret) {
    print "You need to assign \$consumerKey and \$consumerSecret at the top of this script and reload.<br><br>" .
            "These are listed on <a href=\"https://labs.aweber.com/apps\" target=\"_blank\">https://labs.aweber.com/apps<a><br>\n";
    exit;
}


$aweber = new AWeberAPI($consumerKey, $consumerSecret);
if (!$accessKey || !$accessSecret) {

    display_access_tokens($aweber);
}

try {
    $account = $aweber->getAccount($accessKey, $accessSecret);
    $account_id = $account->id;

    if (!$list_id) {
        display_available_lists($account);
        exit;
    }

    print "Your script is configured properly! " .
            "You can now start to develop your API calls, see the example in this script.<br><br>" .
            "Be sure to set \$test_email if you are going to use the example<p>";
} catch (AWeberAPIException $exc) {
    print "<h3>AWeberAPIException:<h3>";
    print " <li> Type: $exc->type <br>";
    print " <li> Msg : $exc->message <br>";
    print " <li> Docs: $exc->documentation_url <br>";
    print "<hr>";
    exit(1);
}

function get_self() {
    return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function display_available_lists($account) {
    print "Please add one of the lines of PHP Code below to the top of your script for the proper list<br>" .
            "then click <a href=\"" . get_self() . "\">here</a> to continue<p>";

    $listURL = "/accounts/{$account->id}/lists/";
    $lists = $account->loadFromUrl($listURL);
    foreach ($lists->data['entries'] as $list) {
        print "\$list_id = '{$list['id']}'; // list name:{$list['name']}\n</br>";
    }
}

function display_access_tokens($aweber) {
    if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {

        $aweber->user->requestToken = $_GET['oauth_token'];
        $aweber->user->verifier = $_GET['oauth_verifier'];
        $aweber->user->tokenSecret = $_COOKIE['secret'];

        list($accessTokenKey, $accessTokenSecret) = $aweber->getAccessToken();

        $tokenKey = $accessTokenKey;
        $tokenSecret = $accessTokenSecret;
        ?>
        <script>
            window.location.href = '<?php echo "?accessKey=" . $tokenKey . "&accessSecret=" . $tokenSecret; ?>';
        </script>
        <?php
        exit;
    }

    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        print "This request must be made from a web browser\n";
        exit;
    }

    $callbackURL = get_self();
    list($key, $secret) = $aweber->getRequestToken($callbackURL);
    $authorizationURL = $aweber->getAuthorizeUrl();

    setcookie('secret', $secret);

    header("Location: $authorizationURL");
    exit();
}

$subscriber = array(
    'email' => 'hozyali1@hotmail.com',
    'name' => 'Ali Hozy'
);


// PRO-TIP: If you get this error: '400 custom_fields: Invalid key name.' the custom_field name(s) have not been created in you list yet.
// refer to https://help.aweber.com/hc/en-us/articles/204027516-How-Do-I-Create-Custom-Fields-
// $aweber->adapter->debug = true; // debugging
$HTTP_METHOD = 'POST';
$URL = "/accounts/{$account_id}/lists/{$list_id}/subscribers";
$PARAMETERS = array(
    'ws.op' => 'create',
    'email' => $subscriber['email'],
    'name' => $subscriber['name'],
);
$RETURN_FORMAT = array(
    'return' => 'headers'
);

$resp = $aweber->adapter->request($HTTP_METHOD, $URL, $PARAMETERS, $RETURN_FORMAT);

if ($resp['Status-Code'] == 201) {
    // we will be using $subscriber_id in example 3
    $subscriber_id = array_pop(explode('/', $resp['Location']));
    print "New subscriber added, subscriber_id: {$subscriber_id}\n";
} else {
    print "Failure: " . print_r($resp, 1) . "\n";
}
?>