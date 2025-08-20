
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
// [START merchantapi_update_account_relationship]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1\AccountRelationship;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountRelationshipsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\UpdateAccountRelationshipRequest;

/**
 * This class demonstrates how to update an account relationship.
 */
class UpdateAccountRelationshipSample
{
    /**
     * Updates a specific account relationship.
     *
     * @param array $config The configuration file for authentication.
     * @param int $providerId The ID of the provider for the relationship.
     */
    public static function updateAccountRelationshipSample(array $config, int $providerId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountRelationshipsServiceClient = new AccountRelationshipsServiceClient($options);

        // The name of the account relationship to update.
        // Format: accounts/{account}/relationships/{provider}
        $name = $accountRelationshipsServiceClient->accountRelationshipName(
            $config['accountId'],
            $providerId
        );

        // Creates an AccountRelationship with the updated fields.
        $accountRelationship = new AccountRelationship([
            'name' => $name,
            'account_id_alias' => 'alias'
        ]);

        // Creates a field mask to specify which fields to update. In this case,
        // only `account_id_alias` will be updated.
        $fieldMask = new FieldMask([
            'paths' => ['account_id_alias']
        ]);

        // Creates the request.
        $request = new UpdateAccountRelationshipRequest([
            'account_relationship' => $accountRelationship,
            'update_mask' => $fieldMask
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending Update AccountRelationship request%s", PHP_EOL);
            $response = $accountRelationshipsServiceClient->updateAccountRelationship($request);
            printf("Updated AccountRelationship below%s", PHP_EOL);
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
        // The ID of the provider for which you want to update the relationship.
        $providerId = 111;
        self::updateAccountRelationshipSample($config, $providerId);
    }
}

// Runs the sample.
$sample = new UpdateAccountRelationshipSample();
$sample->callSample();
// [END merchantapi_update_account_relationship]