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

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_list_quota]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Quota\V1beta\Client\QuotaServiceClient;
use Google\Shopping\Merchant\Quota\V1beta\ListQuotaGroupsRequest;

/**
 * This class demonstrates how to list quota for a given Merchant Center account.
 */
class ListQuotaSample
{

    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId
     *      The account that owns the quota.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }


    /**
     * Lists quotas for a given Merchant Center account.
     *
     * @param string $accountId
     *      The Merchant Center account ID.
     * @throws ApiException
     */
    public static function listQuotas(string $accountId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $quotaServiceClient = new QuotaServiceClient($options);

        // Creates the parent resource name.
        $parent = self::getParent($accountId);

        // Creates the request.
        $request = new ListQuotaGroupsRequest(['parent' => $parent]);

        print "Sending list quotas request:\n";

        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $quotaServiceClient->listQuotaGroups($request);

            $count = 0;

            // Iterates over all rows in all pages and prints the quota group in each row.
            foreach ($response->iterateAllElements() as $quota) {
                print_r($quota);
                $count++;
            }
            print "The following count of quota were returned: ";
            print $count . "\n";
        } catch (ApiException $e) {
            print "Failed to list quota.\n";
            print $e->getMessage() . "\n";
        }
    }

    /**
     * Helper to execute the sample.
     * @throws ApiException
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listQuotas($config['accountId']);
    }
}

// Run the script
$sample = new ListQuotaSample();
$sample->callSample();
// [END merchantapi_list_quota]

