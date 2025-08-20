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

// [START merchantapi_get_online_return_policy]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\OnlineReturnPolicyServiceClient;
use Google\Shopping\Merchant\Accounts\V1\GetOnlineReturnPolicyRequest;

/**
 * This class demonstrates how to get an OnlineReturnPolicy for a given
 * Merchant Center account.
 */
class GetOnlineReturnPolicySample
{
    /**
     * A helper function to create the name string.
     *
     * @param string $accountId The merchant account ID.
     * @param string $returnPolicyId The return policy ID.
     *
     * @return string The name has the format:
     *     `accounts/{account}/onlineReturnPolicies/{return_policy}`
     */
    private static function getName(
        string $accountId,
        string $returnPolicyId
    ): string {
        return sprintf(
            'accounts/%s/onlineReturnPolicies/%s',
            $accountId,
            $returnPolicyId
        );
    }

    /**
     * Gets an online return policy from your Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $returnPolicyId The ID of the return policy to retrieve.
     */
    public static function getOnlineReturnPolicy(
        array $config,
        string $returnPolicyId
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $onlineReturnPolicyServiceClient =
            new OnlineReturnPolicyServiceClient($options);

        // Creates the name of the return policy to retrieve.
        $name = self::getName($config['accountId'], $returnPolicyId);

        // Calls the API and catches and prints any network failures/errors.
        try {
            // Creates the request object.
            $request = new GetOnlineReturnPolicyRequest(['name' => $name]);

            printf("Sending Get OnlineReturnPolicy request:%s", PHP_EOL);
            $response = $onlineReturnPolicyServiceClient->getOnlineReturnPolicy(
                $request
            );

            printf("Retrieved OnlineReturnPolicy below%s", PHP_EOL);
            // The `serializeToJsonString()` method is chosen here for better
            // readability of the output.
            printf('%s%s', $response->serializeToJsonString(true), PHP_EOL);
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
        // Replace with the actual return policy ID you want to retrieve.
        $returnPolicyId = '<SET_RETURN_POLICY_ID>';
        self::getOnlineReturnPolicy($config, $returnPolicyId);
    }
}

// Runs the script.
$sample = new GetOnlineReturnPolicySample();
$sample->callSample();
// [END merchantapi_get_online_return_policy]
