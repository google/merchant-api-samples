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
// [START merchantapi_retrieve_latest_termsofservice]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\TermsOfServiceServiceClient;
use Google\Shopping\Merchant\Accounts\V1\RetrieveLatestTermsOfServiceRequest;
use Google\Shopping\Merchant\Accounts\V1\TermsOfServiceKind;

/**
 * Demonstrates how to retrieve the latest TermsOfService.
 */
class RetrieveLatestTermsOfService
{
    /**
     * Retrieves the latest TermsOfService.
     *
     * @param array $config The configuration data.
     * @return void
     */
    public static function retrieveLatestTermsOfService($config): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Create client options.
        $options = ['credentials' => $credentials];

        // Create a TermsOfServiceServiceClient.
        $termsOfServiceServiceClient = new TermsOfServiceServiceClient($options);

        try {
            // Prepare the request.
            $request = new RetrieveLatestTermsOfServiceRequest([
                'region_code' => "US",
                'kind' => TermsOfServiceKind::MERCHANT_CENTER,
            ]);

            print "Sending Retrieve Latest TermsOfService request:\n";
            $response = $termsOfServiceServiceClient->retrieveLatestTermsOfService($request);

            print "Retrieved TermsOfService below\n";
            print $response->serializeToJsonString() . PHP_EOL;
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

        self::retrieveLatestTermsOfService($config);
    }
}

// Run the script
$sample = new RetrieveLatestTermsOfService();
$sample->callSample();
// [END merchantapi_retrieve_latest_termsofservice]