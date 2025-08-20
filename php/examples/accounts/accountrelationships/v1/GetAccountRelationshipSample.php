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
// [START merchantapi_get_account_relationship]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountRelationshipsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\GetAccountRelationshipRequest;

/**
 * This class demonstrates how to get an account relationship.
 */
class GetAccountRelationshipSample
{
    /**
     * Retrieves a specific account relationship.
     *
     * @param array $config The configuration file for authentication.
     * @param int $providerId The ID of the provider for the relationship.
     */
    public static function getAccountRelationshipSample(array $config, int $providerId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountRelationshipsServiceClient = new AccountRelationshipsServiceClient($options);

        // The name of the account relationship to retrieve.
        // Format: accounts/{account}/relationships/{provider}
        $name = $accountRelationshipsServiceClient->accountRelationshipName(
            $config['accountId'],
            $providerId
        );

        // Creates the request.
        $request = new GetAccountRelationshipRequest([
            'name' => $name
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending Get Account Relationship request:%s", PHP_EOL);
            $response = $accountRelationshipsServiceClient->getAccountRelationship($request);
            printf("Retrieved Account Relationship below%s", PHP_EOL);
            print $response->serializeToJsonString(true) . PHP_EOL;
        } catch (ApiException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // Update this with the provider ID you want to get the relationship for.
        $providerId = 111;
        self::getAccountRelationshipSample($config, $providerId);
    }
}

// Runs the sample.
$sample = new GetAccountRelationshipSample();
$sample->callSample();
// [END merchantapi_get_account_relationship]
