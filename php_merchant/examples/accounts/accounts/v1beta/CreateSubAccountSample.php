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
use Google\Shopping\Merchant\Accounts\V1beta\Account;
use Google\Shopping\Merchant\Accounts\V1beta\AccountAggregation;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\CreateAndConfigureAccountRequest;
use Google\Shopping\Merchant\Accounts\V1beta\CreateAndConfigureAccountRequest\AddAccountService;
use Google\Type\TimeZone;

/**
 * This class demonstrates how to create a sub-account under an MCA account.
 */
class CreateSubAccount
{
    // [START createSubAccount]
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }


    public static function createSubAccount(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountsServiceClient = new AccountsServiceClient($options);

        // Creates parent/provider to identify the MCA account into which to insert the subaccount.
        $parent = self::getParent($config['accountId']);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = new CreateAndConfigureAccountRequest([
                'account' => (new Account([
                    'account_name' => 'Demo Business',
                    'adult_content' => false,
                    'time_zone' => (new TimeZone(['id' => 'America/New_York'])),
                    'language_code' => 'en-US',
                ])),
                'service' => [
                    (new AddAccountService([
                        'provider' => $parent,
                        'account_aggregation' => new AccountAggregation,
                    ])),
                ],
            ]);

            print "Sending Create SubAccount request\n";
            $response = $accountsServiceClient->createAndConfigureAccount($request);
            print "Inserted Account Name below\n";
            // Format: `accounts/{account}
            print $response->getName() . PHP_EOL;
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }
    // [END createSubAccount]
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::createSubAccount($config);
    }
}

$sample = new CreateSubAccount();
$sample->callSample();


