<?php
session_start();
if (isset($_GET['accessKey'])) {
    ?>
    <script>
        window.location.href = '<?php echo $_SESSION['site_url'] . "&accessKey=" . $_GET['accessKey'] . "&accessSecret=" . $_GET['accessSecret']; ?>';
    </script>
    <?php
}
if (isset($_POST['site_url'])) {
    $_SESSION['site_url'] = $_POST['site_url'];
}

if (isset($_GET['rt'])) {
    #$return_url = $_GET['rt'];
    $_SESSION['return_url'] = $_GET['rt'];
}
require_once('aweber_api/aweber_api.php');

$wpulist_aweber_keys = array(
    'consumerKey' => 'AkZGcUtTCntHtN6wdC0uHWGT',
    'consumerSecret' => 'mfrSfXBFGUhupexEmr8sZxfcfYO38omprwuLfCaG',
);


/*
  if ($_REQUEST['oauth_token'] <> '' && $_REQUEST['oauth_verifier'] <> '') {
  setcookie('consumerKey', $_REQUEST['aweber_consumer_key'], time() + 3600);
  setcookie('consumerSecret', $_REQUEST['aweber_consumer_secret'], time() + 3600);
  sleep(1);
  }
 */
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

    header("Location:" . $_SESSION['return_url']);

    if (!$list_id) {
        display_available_lists($account);
        exit;
    }

    print "Your script is configured properly! " .
            "You can now start to develop your API calls, see the example in this script.<br><br>" .
            "Be sure to set \$test_email if you are going to use the example<p>";

    //example: create a subscriber
    /*
      $test_email = '';
      if (!$test_email){
      print "Assign a valid email address to \$test_email and retry";
      exit;
      }
      $listURL = "/accounts/{$account_id}/lists/{$list_id}";
      $list = $account->loadFromUrl($listURL);
      $params = array(
      'email' => $test_email,
      'ip_address' => '127.0.0.1',
      'ad_tracking' => 'client_lib_example',
      'misc_notes' => 'my cool app',
      'name' => 'John Doe'
      );
      $subscribers = $list->subscribers;
      $new_subscriber = $subscribers->create($params);
      print "{$test_email} was added to the {$list->name} list!";
     */
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
    #return $_SESSION['site_url'];
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

        /* print "Please add these lines of code to the top of your script:<br><br>" .
          "\$accessKey = '{$accessTokenKey}';\n<br>" .
          "\$accessSecret = '{$accessTokenSecret}';\n<br>" .
          "<br><br>" .
          "Then click <a href=\"" . get_self() . "\">here</a> to continue"; */
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
    'email' => 'sales@intensewp.com',
    'name' => 'Intense WP'
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