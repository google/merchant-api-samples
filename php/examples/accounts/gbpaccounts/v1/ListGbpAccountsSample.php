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
// [START merchantapi_list_gbp_accounts]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\GbpAccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\GbpAccount;
use Google\Shopping\Merchant\Accounts\V1\ListGbpAccountsRequest;

/**
 * This class demonstrates how to get the list of GBP accounts for a given
 * Merchant Center account.
 */
class ListGbpAccountsSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The account ID.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Retrieves the list of GBP accounts for a given Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     *
     * @return void
     */
    public static function listGbpAccounts(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $gbpAccountsServiceClient = new GbpAccountsServiceClient($options);

        // Creates the parent account name to identify the merchant.
        $parent = self::getParent($config['accountId']);

        // Creates the request to list GBP accounts.
        $request = new ListGbpAccountsRequest([
            'parent' => $parent
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending list GBP accounts request:%s", PHP_EOL);
            $response = $gbpAccountsServiceClient->listGbpAccounts($request);

            $count = 0;

            // Iterates over all the GBP accounts in the response and prints them.
            foreach ($response->iterateAllElements() as $gbpAccount) {
                /** @var GbpAccount $gbpAccount */
                print_r($gbpAccount->serializeToJsonString());
                $count++;
            }
            printf(
                "The following count of elements were returned: %d%s",
                $count,
                PHP_EOL
            );
        } catch (ApiException $e) {
            printf("An error has occurred: %s%s", $e->getMessage(), PHP_EOL);
        }
    }

    /**
     * Helper to execute the sample.
     *
     * @return void
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listGbpAccounts($config);
    }
}

// Runs the script.
$sample = new ListGbpAccountsSample();
$sample->callSample();
// [END merchantapi_list_gbp_accounts]