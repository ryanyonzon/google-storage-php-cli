<?php
/**
 * List storage bucket(s)
 * Written by Ryan Yonzon <ryanyonzon@gmail.com>
 */

set_include_path("vendor/google/apiclient/src/" . PATH_SEPARATOR . get_include_path());

require_once 'Google/Client.php';
require_once 'Google/Service/Storage.php';

// Configuration stuff
$config = require_once 'config/Credentials.php';

$project_id = 'PROJECT-ID-HERE';

// Create Google Client
$client = new Google_Client();
$client->setApplicationName("Storage CLI");

$key = file_get_contents('key/' . $config->key_filename);

$client->setAssertionCredentials(new Google_Auth_AssertionCredentials(
    $config->service_account_name,
        array(
            'https://www.googleapis.com/auth/devstorage.read_write'
            ),
        $key
    )
);
$client->setClientId($config->client_id);

// Create Storage Service
$service = new Google_Service_Storage($client);
$options = array();
$result = $service->buckets->listBuckets($project_id, $options);

// print the object result
print_r($result);

// Or iterate through item results and get/show bucket IDs

/* 
foreach ($result->getItems() as $k => $v) {
    echo $v->getId() . "\n";
}
*/
