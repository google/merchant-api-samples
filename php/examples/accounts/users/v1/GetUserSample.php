<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Demonstrates how to get a single user for a given Merchant Center account.
 */

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';
// [START merchantapi_get_user]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\GetUserRequest;
use Google\Shopping\Merchant\Accounts\V1beta\Client\UserServiceClient;

/**
 * Retrieves a user.
 *
 * @param array $config The configuration data.
 * @param string $email The email address of the user.
 * @return void
 */
function getUser($config, $email): void
{
    // Gets the OAuth credentials to make the request.
    $credentials = Authentication::useServiceAccountOrTokenFile();

    // Creates options config containing credentials for the client to use.
    $options = ['credentials' => $credentials];

    // Creates a client.
    $userServiceClient = new UserServiceClient($options);

    // Creates user name to identify user.
    $name = 'accounts/' . $config['accountId'] . "/users/" . $email;

    // Calls the API and catches and prints any network failures/errors.
    try {
        $request = new GetUserRequest(['name' => $name]);

        print "Sending Get user request:\n";
        $response = $userServiceClient->getUser($request);

        print "Retrieved User below\n";
        print_r($response);
    } catch (ApiException $e) {
        print $e->getMessage();
    }
}

$config = Config::generateConfig();
$email = "testUser@gmail.com";

getUser($config, $email);
// [END merchantapi_get_user]