<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// [START merchantapi_list_account_services]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountServicesServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ListAccountServicesRequest;

/**
 * This class demonstrates how to list all the account services of an account.
 */
class ListAccountServicesSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The account that owns the product.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Lists all account services for a given account.
     *
     * @param array $config The configuration data used for authentication and
     *     getting the account ID.
     */
    public static function listAccountServices(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountServicesServiceClient = new AccountServicesServiceClient($options);

        // Creates parent to identify the account from which to list all
        // account services.
        $parent = self::getParent($config['accountId']);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = (new ListAccountServicesRequest())
                ->setParent($parent);

            print "Sending list account services request:\n";
            $response = $accountServicesServiceClient->listAccountServices($request);

            $count = 0;

            // Iterates over all rows in all pages and prints the service in
            // each row. Automatically uses the `nextPageToken` if returned to
            // fetch all pages of data.
            foreach ($response->iterateAllElements() as $accountService) {
                print $accountService->serializeToJsonString(true) . PHP_EOL;
                $count++;
            }
            printf(
                "The following count of account services were returned: %d%s",
                $count,
                PHP_EOL
            );
        } catch (ApiException $e) {
            printf("An error has occurred: %s%s", $e->getMessage(), PHP_EOL);
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listAccountServices($config);
    }
}

// Run the script
$sample = new ListAccountServicesSample();
$sample->callSample();
// [END merchantapi_list_account_services]
