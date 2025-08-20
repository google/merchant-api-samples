
<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// [START merchantapi_get_checkout_settings]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\CheckoutSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\GetCheckoutSettingsRequest;

/**
 * This class demonstrates how to get the checkout settings for a given
 * Merchant Center account.
 */
class GetCheckoutSettingsSample
{
    /**
     * Gets the checkout settings for a given Merchant Center account.
     *
     * @param array $config The configuration file for the Merchant Center account.
     *
     * @return void
     */
    public static function getCheckoutSettings(array $config): void
    {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $checkoutSettingsServiceClient = new CheckoutSettingsServiceClient([
            'credentials' => $credentials
        ]);

        // The only valid programId for checkout settings is "checkout".
        $programId = 'checkout';

        // Constructs the resource name format:
        // `accounts/{account}/programs/{program}/checkoutSettings`.
        $name = sprintf(
            'accounts/%s/programs/%s/checkoutSettings',
            $config['accountId'],
            $programId
        );

        // Creates the request object.
        $request = (new GetCheckoutSettingsRequest())
            ->setName($name);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending get checkout settings request:%s", PHP_EOL);
            $response = $checkoutSettingsServiceClient->getCheckoutSettings($request);
            printf("Retrieved Checkout Settings below:%s", PHP_EOL);
            print $response->serializeToJsonString(true) . PHP_EOL;
        } catch (ApiException $e) {
            printf("An error has occurred: %s", PHP_EOL);
            print $e->getMessage();
        }
    }

    /**
     * Executes the sample.
     *
     * @return void
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::getCheckoutSettings($config);
    }
}

// Runs the sample.
$sample = new GetCheckoutSettingsSample();
$sample->callSample();
// [END merchantapi_get_checkout_settings]
