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
use Google\Shopping\Merchant\Accounts\V1beta\AcceptTermsOfServiceRequest;
use Google\Shopping\Merchant\Accounts\V1beta\Client\TermsOfServiceServiceClient;

/**
 * Demonstrates how to accept the TermsOfService agreement in a given account.
 */
class AcceptTermsOfService
{
    // [START acceptTermsOfService]

    /**
     * Accepts the Terms of Service agreement.
     *
     * @param string $accountId The account ID.
     * @param string $tosVersion The Terms of Service version.
     * @param string $regionCode The region code.
     * @return void
     */
    public static function acceptTermsOfService($accountId, $tosVersion, $regionCode): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Create client options.
        $options = ['credentials' => $credentials];

        // Create a TermsOfServiceServiceClient.
        $tosServiceClient = new TermsOfServiceServiceClient($options);

        try {
            // Prepare the request.
            $request = new AcceptTermsOfServiceRequest([
                'name' => sprintf("termsOfService/%s", $tosVersion),
                'account' => sprintf("accounts/%s", $accountId),
                'region_code' => $regionCode,
            ]);

            print "Sending request to accept terms of service...\n";
            $tosServiceClient->acceptTermsOfService($request);

            print "Successfully accepted terms of service.\n";
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

        // Replace with actual values.
        $tosVersion = "132";
        $regionCode = "US";

        self::acceptTermsOfService($config['accountId'], $tosVersion, $regionCode);
    }
    // [END acceptTermsOfService]
}

// Run the script
$sample = new AcceptTermsOfService();
$sample->callSample();


