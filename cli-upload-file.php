<?php
/**
 * Upload/store file on bucket
 * Written by Ryan Yonzon <ryanyonzon@gmail.com>
 */

set_include_path("vendor/google/apiclient/src/" . PATH_SEPARATOR . get_include_path());

require_once 'Google/Client.php';
require_once 'Google/Service/Storage.php';

// Configuration stuff
$config = require_once 'config/Credentials.php';

$project_id = 'PROJECT-ID-HERE';
$bucket_name = "BUCKET-NAME-HERE";

$local_file = "file/lorem-ipsum.txt"; // file to upload
$store_file_name = "lorem-ipsum.txt"; // the filename that'll be used when stored on Google Storage

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

// https://developers.google.com/storage/docs/json_api/v1/buckets/insert
$storage_object = new Google_Service_Storage_StorageObject();
$storage_object->setBucket($bucket_name);
$storage_object->setName($store_file_name);

// Create Storage Service
$service = new Google_Service_Storage($client);

$options = array(
        'data' => file_get_contents($local_file),
        'mimeType' => 'text/plain',
        'uploadType' => 'media' // media, multipart or resumable (https://developers.google.com/storage/docs/json_api/v1/how-tos/upload)
    );
$result = $service->objects->insert($bucket_name, $storage_object, $options);

print_r($result);
