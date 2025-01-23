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
 * Demonstrates how to update a user to make it an admin of the MC account.
 */

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';
// [START merchantapi_update_user]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1beta\AccessRight;
use Google\Shopping\Merchant\Accounts\V1beta\UpdateUserRequest;
use Google\Shopping\Merchant\Accounts\V1beta\User;
use Google\Shopping\Merchant\Accounts\V1beta\Client\UserServiceClient;


/**
 * Updates a user.
 *
 * @param array $config The configuration data.
 * @param string $email The email address of the user.
 * @param int $accessRight The access right to grant the user.
 * @return void
 */
function updateUser($config, $email, $accessRights): void
{
    // Gets the OAuth credentials to make the request.
    $credentials = Authentication::useServiceAccountOrTokenFile();

    // Creates options config containing credentials for the client to use.
    $options = ['credentials' => $credentials];

    // Creates a client.
    $userServiceClient = new UserServiceClient($options);

    // Creates user name to identify user.
    $name = 'accounts/' . $config['accountId'] . "/users/" . $email;

    $user = (new User())
        ->setName($name)
        ->setAccessRights($accessRights);

    $fieldMask = (new FieldMask())->setPaths(['access_rights']);

    // Calls the API and catches and prints any network failures/errors.
    try {
        $request = new UpdateUserRequest([
            'user' => $user,
            'update_mask' => $fieldMask,
        ]);

        print "Sending Update User request\n";
        $response = $userServiceClient->updateUser($request);
        print "Updated User Name below\n";
        print $response->getName() . "\n";
    } catch (ApiException $e) {
        print $e->getMessage();
    }
}


$config = Config::generateConfig();
$email = "testUser@gmail.com";
$accessRights = [AccessRight::ADMIN];

updateUser($config, $email, $accessRights);
// [END merchantapi_update_user]