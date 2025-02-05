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
// [START merchantapi_filter_accounts]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\ListAccountsRequest;

/**
 * This class demonstrates how to filter the accounts the user making the request has access to.
 */
class FilterAccounts
{
    public static function filterAccounts(array $config): void
    {

        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountsServiceClient = new AccountsServiceClient($options);

        // Calls the API and catches and prints any network failures/errors.
        try {

            // Filter for accounts with display names containing "store" and a provider with the ID "123":
            $filter = "accountName = \"*store*\" AND relationship(providerId = 123)";

            // Filter for all subaccounts of account "123":
            // $filter = "relationship(callerHasAccessToProvider() AND providerId = 123 AND service(type = \"ACCOUNT_AGGREGATION\"))";

            // $filter = "relationship(service(handshakeState = \"APPROVED\" AND type =
            // \"ACCOUNT_MANAGEMENT\") AND providerId = 123)";

            $request = new ListAccountsRequest(['filter' => $filter]);

            print "Sending list accounts request with filter:\n";
            $response = $accountsServiceClient->listAccounts($request);

            $count = 0;

            // Iterates over all rows in all pages and prints the sub-account
            // in each row.
            // `response.iterateAll()` automatically uses the `nextPageToken` and recalls the
            // request to fetch all pages of data.
            foreach ($response->iterateAllElements() as $account) {
                print_r($account); 
                $count++;
            }
            print "The following count of elements were returned: ";
            print $count . PHP_EOL;
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }

    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::filterAccounts($config);
    }
}

$sample = new FilterAccounts();
$sample->callSample();
// [END merchantapi_filter_accounts]