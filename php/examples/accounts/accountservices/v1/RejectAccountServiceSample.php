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

// [START merchantapi_reject_account_service]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountServicesServiceClient;
use Google\Shopping\Merchant\Accounts\V1\RejectAccountServiceRequest;

/**
 * This class demonstrates how to reject an account service.
 */
class RejectAccountServiceSample
{
    /**
     * A helper function to create the name of the service.
     *
     * @param string $accountId The account that owns the product.
     * @param string $service The service to be rejected.
     *
     * @return string The name has the format:
     * `accounts/{account}/services/{service}`
     */
    private static function getName(string $accountId, string $service): string
    {
        return sprintf('accounts/%s/services/%s', $accountId, $service);
    }

    /**
     * Rejects an account service.
     *
     * @param array $config The configuration data used for authentication and
     *     getting the account ID.
     * @param string $service The service to be rejected.
     */
    public static function rejectAccountService(
        array $config,
        string $service
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $accountServicesServiceClient = new AccountServicesServiceClient($options);

        // Gets the account ID from the config file.
        $accountId = $config['accountId'];

        // Creates account service name to identify the account service.
        $name = self::getName($accountId, $service);

        // Calls the API and catches and prints any network failures/errors.
        try {
            // The name has the format: accounts/{account}/services/{provider}
            $request = (new RejectAccountServiceRequest())
                ->setName($name);

            print "Sending Reject Account Service request:\n";
            $accountServicesServiceClient->rejectAccountService($request);
            print "Successfully rejected account service\n";
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

        // Update this with the name of the service you want to reject
        // (e.g. from a previous list call).
        $service = '111~222~333';
        self::rejectAccountService($config, $service);
    }
}

// Run the script
$sample = new RejectAccountServiceSample();
$sample->callSample();
// [END merchantapi_reject_account_service]