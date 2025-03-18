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
// [START merchantapi_list_account_issues]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AccountIssueServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\ListAccountIssuesRequest;

/**
 * Lists all the account issues of an account.
 */
class ListAccountIssues
{
    /**
     * A helper function to create the parent string.
     *
     * @param array $accountId
     *      The account.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent($accountId)
    {
        return sprintf("accounts/%s", $accountId);
    }

    /**
     * Lists all the account issues for a given Merchant Center account.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the acccount ID.
     * @return void
     */
    public static function listAccountIssuesSample($config): void
    {
        // Gets the OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountIssueServiceClient = new AccountIssueServiceClient($options);

        // Creates parent.
        $parent = self::getParent($config['accountId']);

        // Creates the request.
        $request = new ListAccountIssuesRequest(['parent' => $parent]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            print "Sending list account issues request:\n";
            $response = $accountIssueServiceClient->listAccountIssues($request);

            $count = 0;
            // Iterates over all elements and prints the issue in each row.
            foreach ($response->iterateAllElements() as $accountIssue) {
                print_r($accountIssue);
                $count++;
            }
            print "The following count of account issues were returned: ";
            print $count . "\n";
        } catch (ApiException $e) {
            print "An error has occured: \n";
            print $e->getMessage() . "\n";
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

        // Lists the account issues.
        self::listAccountIssuesSample($config);
    }
}

// Run the script
$sample = new ListAccountIssues();
$sample->callSample();
// [END merchantapi_list_account_issues]

