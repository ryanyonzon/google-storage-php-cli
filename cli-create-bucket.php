<?php
/**
 * Create Google Storage bucket
 * Written by Ryan Yonzon <hello@ryanyonzon.com>
 */

set_include_path("vendor/google/apiclient/src/" . PATH_SEPARATOR . get_include_path());

require_once 'Google/Client.php';
require_once 'Google/Service/Storage.php';

// Configuration stuff
$config = require_once 'config/Credentials.php';

$project_id = 'PROJECT-ID-HERE';
$bucket_name = "BUCKET-NAME-HERE";

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

// https://developers.google.com/storage/docs/json_api/v1/buckets/insert
$bucket = new Google_Service_Storage_Bucket();
$bucket->setName($bucket_name);

$options = array();
$result = $service->buckets->insert($project_id, $bucket, $options);

// print the object result
print_r($result);
