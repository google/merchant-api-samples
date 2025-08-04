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
// [START merchantapi_retrieve_for_application_termsofservice_agreementstate]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\TermsOfServiceAgreementStateServiceClient;
use Google\Shopping\Merchant\Accounts\V1\RetrieveForApplicationTermsOfServiceAgreementStateRequest;

/**
 * Demonstrates how to retrieve the latest TermsOfService agreement state for the account.
 */
class RetrieveForApplicationTermsOfServiceAgreementState
{
    private static function getParent($accountId)
    {
        return sprintf("accounts/%s", $accountId);
    }
    /**
     * Retrieves the latest TermsOfService agreement state for the account.
     *
     * @param array $config The configuration data.
     * @return void
     */
    public static function retrieveForApplicationTermsOfServiceAgreementState($config): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Create client options.
        $options = ['credentials' => $credentials];

        // Create a TermsOfServiceAgreementStateServiceClient.
        $termsOfServiceAgreementStateServiceClient = new TermsOfServiceAgreementStateServiceClient($options);

        // Create parent.
        $parent = self::getParent($config['accountId']);

        try {
            // Prepare the request.
            $request = new RetrieveForApplicationTermsOfServiceAgreementStateRequest([
                'parent' => $parent,
            ]);

            print "Sending RetrieveForApplication TermsOfService Agreement request:" . PHP_EOL;
            $response = $termsOfServiceAgreementStateServiceClient->retrieveForApplicationTermsOfServiceAgreementState($request);

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
        self::retrieveForApplicationTermsOfServiceAgreementState($config);
    }

}

// Run the script
$sample = new RetrieveForApplicationTermsOfServiceAgreementState();
$sample->callSample();
// [END merchantapi_retrieve_for_application_termsofservice_agreementstate]