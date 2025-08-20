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

// [START merchantapi_create_checkout_settings]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\CheckoutSettings;
use Google\Shopping\Merchant\Accounts\V1\Client\CheckoutSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\CreateCheckoutSettingsRequest;
use Google\Shopping\Merchant\Accounts\V1\UriSettings;
use Google\Shopping\Type\Destination\DestinationEnum;

/**
 * This class demonstrates how to create checkout settings for a given Merchant
 * Center account.
 */
class CreateCheckoutSettingsSample
{
    /**
     * Creates the checkout settings for a given Merchant Center account.
     *
     * @param array $config The configuration file for the Merchant Center account.
     *
     * @return void
     */
    public static function createCheckoutSettings(array $config): void
    {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $checkoutSettingsServiceClient = new CheckoutSettingsServiceClient([
            'credentials' => $credentials
        ]);

        // The only valid programId for checkout settings is "checkout".
        $programId = 'checkout';

        // Constructs the parent resource name format:
        // `accounts/{account}/programs/{program}`.
        $parent = sprintf(
            'accounts/%s/programs/%s',
            $config['accountId'],
            $programId
        );

        // TODO: Replace this with your checkout URL.
        $checkoutUrl = 'https://myshopify.com/cart/1234:1';

        // Creates the URI settings with the checkout URL.
        $uriSettings = new UriSettings([
            'checkout_uri_template' => $checkoutUrl
        ]);

        // Creates the checkout settings with the URI settings and eligible
        // destinations.
        $checkoutSettings = new CheckoutSettings([
            'uri_settings' => $uriSettings,
            'eligible_destinations' => [DestinationEnum::SHOPPING_ADS]
        ]);

        // Creates the request object.
        $request = (new CreateCheckoutSettingsRequest())
            ->setParent($parent)
            ->setCheckoutSettings($checkoutSettings);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending create checkout settings request:%s", PHP_EOL);
            $response = $checkoutSettingsServiceClient->createCheckoutSettings($request);
            printf("Created Checkout Settings below:%s", PHP_EOL);
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
        self::createCheckoutSettings($config);
    }
}

// Runs the sample.
$sample = new CreateCheckoutSettingsSample();
$sample->callSample();
// [END merchantapi_create_checkout_settings]
