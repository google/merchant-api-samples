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
// [START merchantapi_get_termsofservice_agreementstate]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\TermsOfServiceAgreementStateServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\GetTermsOfServiceAgreementStateRequest;

/**
 * Demonstrates how to get a TermsOfServiceAgreementState.
 */
class GetTermsOfServiceAgreementState
{

    /**
     * Gets a TermsOfServiceAgreementState.
     *
     * @param array $config The configuration data.
     * @return void
     */
    public static function getTermsOfServiceAgreementState($config): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Create client options.
        $options = ['credentials' => $credentials];

        // Create a TermsOfServiceAgreementStateServiceClient.
        $termsOfServiceAgreementStateServiceClient = new TermsOfServiceAgreementStateServiceClient($options);

        // Service agreeement identifier
        $identifier = "MERCHANT_CENTER-US";

        // Create TermsOfServiceAgreementState name.
        $name = "accounts/" . $config['accountId'] . "/termsOfServiceAgreementStates/" . $identifier;

        print $name . PHP_EOL;

        try {
            // Prepare the request.
            $request = new GetTermsOfServiceAgreementStateRequest([
                'name' => $name,
            ]);

            print "Sending Get TermsOfServiceAgreementState request:" . PHP_EOL;
            $response = $termsOfServiceAgreementStateServiceClient->getTermsOfServiceAgreementState($request);

            print "Retrieved TermsOfServiceAgreementState below\n";
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

        self::getTermsOfServiceAgreementState($config);
    }

}

// Run the script
$sample = new GetTermsOfServiceAgreementState();
$sample->callSample();
// [END merchantapi_get_termsofservice_agreementstate]