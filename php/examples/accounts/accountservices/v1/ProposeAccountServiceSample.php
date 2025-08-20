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

// [START merchantapi_propose_account_service]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\AccountAggregation;
use Google\Shopping\Merchant\Accounts\V1\AccountService;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountServicesServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ProposeAccountServiceRequest;

/**
 * This class demonstrates how to propose an account service.
 */
class ProposeAccountServiceSample
{
    /**
     * A helper function to create the account name string.
     *
     * @param string $accountId The ID of the account.
     *
     * @return string The account name has the format: `accounts/{account_id}`
     */
    private static function toAccountName(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Proposes a new account service.
     *
     * @param array $config The configuration data used for authentication and
     *     getting the account ID.
     * @param string $providerId The ID of the provider account.
     */
    public static function proposeAccountService(
        array $config,
        string $providerId
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountServicesServiceClient = new AccountServicesServiceClient($options);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $accountAggregation = new AccountAggregation();
            $accountService = (new AccountService())
                ->setAccountAggregation($accountAggregation);

            $request = (new ProposeAccountServiceRequest())
                ->setParent(self::toAccountName($config['accountId']))
                ->setProvider(self::toAccountName($providerId))
                ->setAccountService($accountService);

            print "Sending Propose AccountService request\n";
            $response = $accountServicesServiceClient->proposeAccountService($request);
            print "Proposed AccountService below\n";
            print $response->serializeToJsonString(true) . PHP_EOL;
        } catch (ApiException $e) {
            printf("An error has occurred: %s%s", $e->getMessage(), PHP_EOL);
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();

        // Update this with the Merchant Center provider ID you want to get the
        // relationship for.
        $providerId = 111;
        self::proposeAccountService($config, $providerId);
    }
}

// Run the script
$sample = new ProposeAccountServiceSample();
$sample->callSample();
// [END merchantapi_propose_account_service]
