<?php

if (!function_exists('curl_init')) {
    die('ConstantContact for PHP requires the PHP cURL extension.');
}

class ConstantContact {

    function __construct($api_key, $acces_token) {
        $this->acces_token = $acces_token;
        $this->api_key = $api_key;
    }

    function get_constant_list() {

        $url = "https://api.constantcontact.com/v2/lists?api_key=" . $this->api_key;

        $list = $this->makeconstantrequest($url, "GET", "");
        $result_arr = json_decode($list);
        return $result_arr;
    }

    function add_user($constant_user, $list) {

        $contact = new stdClass();
        if (isset($constant_user["firstName"]))
            $contact->first_name = $constant_user["firstName"];
        if (isset($constant_user["lastName"]))
            $contact->last_name = $constant_user["lastName"];

        // Create the email addresses array and add an email address object.
        $contact->email_addresses = Array(new stdClass());
        // Set the email address
        $contact->email_addresses[0]->email_address = $constant_user["email"];
        //Create the list array on the object.
        $contact->lists = Array(new stdClass());
        // Add a list by ID. 1 is the original General Interest list.
        $contact->lists[0]->id = $list;
        $url = "https://api.constantcontact.com/v2/contacts?action_by=ACTION_BY_OWNER&api_key=" . $this->api_key;
        $result = $this->makeconstantrequest($url, "POST", json_encode($contact));
        return $result;
    }

    function makeconstantrequest($url, $method, $body) {
        $set = get_option('my_ccoptions');
        $theHeaders = Array("Content-type: application/json;charset=UTF-8");
        $theHeaders[] = 'Authorization: Bearer ' . $this->acces_token;
        $rq = curl_init();
        curl_setopt($rq, CURLOPT_URL, $url);
        curl_setopt($rq, CURLOPT_HTTPHEADER, $theHeaders);
        curl_setopt($rq, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($rq, CURLOPT_HEADER, 0);
        curl_setopt($rq, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($rq, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($rq, CURLOPT_CUSTOMREQUEST, $method);
        if ($body) {
            curl_setopt($rq, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($rq, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        if (!$result = curl_exec($rq)) {
            //Return a JSON formatted Curl Error
            return "{\"curl_error\":\"Error " . curl_errno($rq) . " " . curl_error($rq) . "\"}";
        } else {
            curl_close($rq);
            return $result;
        }
    }

}

?>