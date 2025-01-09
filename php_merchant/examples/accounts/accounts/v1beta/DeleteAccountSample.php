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
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\DeleteAccountRequest;


/**
 * This class demonstrates how to delete a given Merchant Center account.
 */
class DeleteAccount
{
    // [START deleteAccount]
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }

    // This method can delete a standalone, MCA or sub-account. If you delete an MCA,
    // all sub-accounts will also be deleted.
    // Admin user access is required to execute this method.
    public static function deleteAccount(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountsServiceClient = new AccountsServiceClient($options);


        // Gets the account ID from the config file.
        $accountId = $config['accountId'];

        // Creates account name to identify the account.
        $name = self::getParent($accountId);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = new DeleteAccountRequest([
                'name' => $name,
                // Optional. If set to true, the account will be deleted even if it has offers or
                // provides services to other accounts. Defaults to 'false'.
                'force' => true,
            ]);

            print "Sending Delete Account request\n";
            $accountsServiceClient->deleteAccount($request); // No response returned on success.
            print "Delete successful.\n";
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }
    // [END deleteAccount]
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::deleteAccount($config);
    }
}

$sample = new DeleteAccount();
$sample->callSample();


