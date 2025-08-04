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
// [START merchantapi_list_accounts]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ListAccountsRequest;


/**
 * This class demonstrates how to list all the accounts the user making the request has access to.
 * Please note that "listAccounts" method charge API quota on behalf of each specific 
 * user running the request. "listSubAccounts" method is more suitable to list 
 * large number of sub-accounts.
 */
class ListAccounts
{
    public static function listAccounts(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountsServiceClient = new AccountsServiceClient($options);

        // Calls the API and catches and prints any network failures/errors.
        try {

            $request = new ListAccountsRequest();

            print "Sending list accounts request:\n";
            $response = $accountsServiceClient->listAccounts($request);

            $count = 0;

            // Iterates over all rows in all pages and prints the datasource in each row.
            // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
            foreach ($response->iterateAllElements() as $account) {
                print_r($account);
                $count++;
            }
            print "The following count of accounts were returned: ";
            print $count . PHP_EOL;
        } catch (ApiException $e) {
            print "An error has occured: \n";
            print $e->getMessage();
        }
    }

    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listAccounts($config);
    }
}

$sample = new ListAccounts();
$sample->callSample();
// [END merchantapi_list_accounts]