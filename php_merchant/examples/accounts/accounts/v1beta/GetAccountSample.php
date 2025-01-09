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
use Google\Shopping\Merchant\Accounts\V1beta\AccountName;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\GetAccountRequest;

/**
 * This class demonstrates how to get a single Merchant Center account.
 */
class GetAccount
{
    // [START getAccount]
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }
    public static function getAccount(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountsServiceClient = new AccountsServiceClient($options);

        // Gets the account ID from the config file.
        $accountId = $config['accountId'];

        // Creates account name to identify account.
        $name = self::getParent($accountId);

        // Calls the API and catches and prints any network failures/errors.
        try {

            // The name has the format: accounts/{account}
            $request = new GetAccountRequest(['name' => $name]);

            print "Sending Get Account request:\n";
            $response = $accountsServiceClient->getAccount($request);

            print "Retrieved Account below\n";
            print_r($response);
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }
    // [END getAccount]
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::getAccount($config);
    }
}

$sample = new GetAccount();
$sample->callSample();

