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

// [START merchantapi_create_online_return_policy]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\OnlineReturnPolicyServiceClient;
use Google\Shopping\Merchant\Accounts\V1\CreateOnlineReturnPolicyRequest;
use Google\Shopping\Merchant\Accounts\V1\OnlineReturnPolicy;
use Google\Shopping\Merchant\Accounts\V1\OnlineReturnPolicy\ItemCondition;
use Google\Shopping\Merchant\Accounts\V1\OnlineReturnPolicy\Policy;
use Google\Shopping\Merchant\Accounts\V1\OnlineReturnPolicy\Policy\Type;
use Google\Shopping\Merchant\Accounts\V1\OnlineReturnPolicy\ReturnMethod;

/**
 * This class demonstrates how to create an OnlineReturnPolicy for a given
 * Merchant Center account.
 */
class CreateOnlineReturnPolicySample
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
     * Creates an online return policy for your Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     */
    public static function createOnlineReturnPolicy(array $config): void
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
            // Defines the policy details, such as the return window.
            $policy = new Policy(['type' => Type::LIFETIME_RETURNS]);

            // Constructs the full OnlineReturnPolicy object.
            $onlineReturnPolicy = new OnlineReturnPolicy([
                'label' => 'US Return Policy',
                'return_policy_uri' =>
                    'https://www.google.com/returnpolicy-sample',
                'countries' => ['US'],
                'policy' => $policy,
                'item_conditions' => [ItemCondition::PBNEW],
                'return_methods' => [ReturnMethod::IN_STORE],
                'process_refund_days' => 10,
            ]);

            // Creates the request object.
            $request = new CreateOnlineReturnPolicyRequest([
                'parent' => $parent,
                'online_return_policy' => $onlineReturnPolicy,
            ]);

            printf("Sending create OnlineReturnPolicy request:%s", PHP_EOL);
            $response = $onlineReturnPolicyServiceClient->createOnlineReturnPolicy(
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
        self::createOnlineReturnPolicy($config);
    }
}

// Runs the script.
$sample = new CreateOnlineReturnPolicySample();
$sample->callSample();
// [END merchantapi_create_online_return_policy]
