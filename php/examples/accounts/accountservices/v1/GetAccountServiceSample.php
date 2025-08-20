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

// [START merchantapi_get_account_service]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\AccountServicesServiceClient;
use Google\Shopping\Merchant\Accounts\V1\GetAccountServiceRequest;

/**
 * This class demonstrates how to get an account service.
 */
class GetAccountServiceSample
{
    /**
     * A helper function to create the name of the service.
     *
     * @param string $accountId The account that owns the product.
     * @param string $service The service to be retrieved.
     *
     * @return string The name has the format:
     * `accounts/{account}/services/{service}`
     */
    private static function getName(string $accountId, string $service): string
    {
        return sprintf('accounts/%s/services/%s', $accountId, $service);
    }

    /**
     * Retrieves an account service.
     *
     * @param array $config The configuration data used for authentication and
     *     getting the account ID.
     * @param string $service The service to be retrieved.
     */
    public static function getAccountService(
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
            $request = (new GetAccountServiceRequest())
                ->setName($name);

            print "Sending Get Account Service request:\n";
            $response = $accountServicesServiceClient->getAccountService($request);

            printf("Retrieved Account Service below:%s", PHP_EOL);
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

        // Update this with the name of the service you want to get
        // (e.g. from a previous list call).
        $service = '111~222~333';
        self::getAccountService($config, $service);
    }
}

// Run the script
$sample = new GetAccountServiceSample();
$sample->callSample();
// [END merchantapi_get_account_service]
