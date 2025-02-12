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
// [START merchantapi_get_business_info]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\BusinessInfoServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\GetBusinessInfoRequest;

/**
 * This class demonstrates how to get the business info of a Merchant Center account.
 */
class GetBusinessInfoSample
{
    /**
     * Gets the business info of a Merchant Center account.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the account ID.
     *
     * @return void
     */
    public static function getBusinessInfoSample(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $businessInfoServiceClient = new BusinessInfoServiceClient($options);

        // Creates BusinessInfo name to identify the BusinessInfo.
        // The name has the format: accounts/{account}/businessInfo
        $name = "accounts/" . $config['accountId'] . "/businessInfo";

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = new GetBusinessInfoRequest(['name' => $name]);
            print "Sending get BusinessInfo request:\n";
            $response = $businessInfoServiceClient->getBusinessInfo($request);

            print "Retrieved BusinessInfo below\n";
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
        self::getBusinessInfoSample($config);
    }
}

// Run the script
$sample = new GetBusinessInfoSample();
$sample->callSample();
// [END merchantapi_get_business_info]
