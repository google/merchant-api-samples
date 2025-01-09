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
 * Demonstrates how to list all the users for a given Merchant Center account.
 */

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\ListUsersRequest;
use Google\Shopping\Merchant\Accounts\V1beta\Client\UserServiceClient;


// [START listUsers]
/**
 * Lists users.
 *
 * @param array $config The configuration data.
 * @return void
 */
function listUsers($config): void
{
    // Gets the OAuth credentials to make the request.
    $credentials = Authentication::useServiceAccountOrTokenFile();

    // Creates options config containing credentials for the client to use.
    $options = ['credentials' => $credentials];

    // Creates a client.
    $userServiceClient = new UserServiceClient($options);

    // Creates parent to identify the account from which to list all users.
    $parent = sprintf("accounts/%s", $config['accountId']);

    // Calls the API and catches and prints any network failures/errors.
    try {
        $request = new ListUsersRequest(['parent' => $parent]);

        print "Sending list users request:\n";
        $response = $userServiceClient->listUsers($request);

        $count = 0;
        foreach ($response->iterateAllElements() as $element) {
            print_r($element);
            $count++;
        }
        print "The following count of elements were returned: ";
        print $count . "\n";
    } catch (ApiException $e) {
        print $e->getMessage();
    }
}
// [END listUsers]


$config = Config::generateConfig();
listUsers($config);
