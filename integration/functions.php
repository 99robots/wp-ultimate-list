<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly       ?>
<?php

$page = isset($_REQUEST['page']) ? sanitize_key($_REQUEST['page']) : null;

if (isset($page) && $page == 'wpulist_setup_aweber') {
    add_action('init', 'wpulist_start_session', 1);
}

require_once ('aweber/functions_aweber.php');
