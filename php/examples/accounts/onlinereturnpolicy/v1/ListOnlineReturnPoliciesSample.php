
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

// [START merchantapi_list_online_return_policies]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\OnlineReturnPolicyServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ListOnlineReturnPoliciesRequest;

/**
 * This class demonstrates how to list the OnlineReturnPolicies for a given
 * Merchant Center account.
 */
class ListOnlineReturnPoliciesSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The merchant account ID.
     *
     * @return string The parent has the format: `accounts/{account}`
     */
    private static function getParent(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Lists the online return policies for your Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     */
    public static function listOnlineReturnPolicies(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $onlineReturnPolicyServiceClient =
            new OnlineReturnPolicyServiceClient($options);

        // Creates the parent account resource name.
        $parent = self::getParent($config['accountId']);

        // Calls the API and catches and prints any network failures/errors.
        try {
            // Creates the request object.
            $request = new ListOnlineReturnPoliciesRequest([
                'parent' => $parent,
                'page_size' => 20,
            ]);

            printf("Sending List OnlineReturnPolicies request:%s", PHP_EOL);
            $pagedResponse = $onlineReturnPolicyServiceClient->listOnlineReturnPolicies(
                $request
            );

            $count = 0;

            // Iterates through all elements of the response and prints them.
            foreach ($pagedResponse->iterateAllElements() as $onlineReturnPolicy) {
                // The `serializeToJsonString()` method is chosen here for better
                // readability of the output.
                printf(
                    '%s%s',
                    $onlineReturnPolicy->serializeToJsonString(true),
                    PHP_EOL
                );
                $count++;
            }

            printf(
                'The following count of OnlineReturnPolicies is returned: %s',
                PHP_EOL
            );
            printf('%d%s', $count, PHP_EOL);
        } catch (ApiException $e) {
            printf('%s%s', $e->getMessage(), PHP_EOL);
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listOnlineReturnPolicies($config);
    }
}

// Runs the script.
$sample = new ListOnlineReturnPoliciesSample();
$sample->callSample();
// [END merchantapi_list_online_return_policies]