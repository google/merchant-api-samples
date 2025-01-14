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
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';


use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\ShippingSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\GetShippingSettingsRequest;

/**
 * This class demonstrates how to get the ShippingSettings for a given Merchant Center account.
 */
class GetShippingSettings
{
    // [START getShippingSettings]

    /**
     * Retrieves the shipping settings for the specified Merchant Center account.
     *
     * @param array $config The configuration data containing the account ID.
     * @return void
     */
    public static function getShippingSettings($config)
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $shippingSettingsServiceClient = new ShippingSettingsServiceClient($options);

        // Creates ShippingSettings name to identify ShippingSettings.
        // The name has the format: accounts/{account}/shippingSettings
        $name = "accounts/" . $config['accountId'] . "/shippingSettings";


        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = (new GetShippingSettingsRequest())
                ->setName($name);

            print "Sending Get ShippingSettings request:" . PHP_EOL;
            $response = $shippingSettingsServiceClient->getShippingSettings($request);

            print "Retrieved ShippingSettings below" . PHP_EOL;
            print_r($response);
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Helper to execute the sample.
     *
     * @return void
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // Makes the call to get shipping settings for the MC account.
        self::getShippingSettings($config);
    }
    // [END getShippingSettings]
}

// Run the script
$sample = new GetShippingSettings();
$sample->callSample();

