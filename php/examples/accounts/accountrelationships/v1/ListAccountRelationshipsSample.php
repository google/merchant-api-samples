
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

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';
// [START merchantapi_list_account_relationships]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountRelationshipsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ListAccountRelationshipsRequest;

/**
 * This class demonstrates how to list all the relationships of an account.
 */
class ListAccountRelationshipsSample
{
    /**
     * Lists all account relationships for a given Merchant Center account.
     *
     * @param array $config The configuration file for authentication.
     */
    public static function listAccountRelationshipsSample(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountRelationshipsServiceClient = new AccountRelationshipsServiceClient($options);

        // The parent account from which to list all account relationships.
        // Format: accounts/{account}
        $parent = AccountRelationshipsServiceClient::accountName($config['accountId']);

        // Creates the request.
        $request = new ListAccountRelationshipsRequest([
            'parent' => $parent
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending list account relationships request:%s", PHP_EOL);
            $response = $accountRelationshipsServiceClient->listAccountRelationships($request);

            $count = 0;

            // Iterates over all relationships and prints them.
            // The `iterateAllElements` method automatically handles pagination.
            foreach ($response->iterateAllElements() as $accountRelationship) {
                print $accountRelationship->serializeToJsonString(true) . PHP_EOL;
                $count++;
            }
            printf("The following count of account relationships were returned: %d%s", $count, PHP_EOL);
        } catch (ApiException $e) {
            printf("An error has occured: %s", PHP_EOL);
            print $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listAccountRelationshipsSample($config);
    }
}

// Runs the sample.
$sample = new ListAccountRelationshipsSample();
$sample->callSample();
// [END merchantapi_list_account_relationships]
