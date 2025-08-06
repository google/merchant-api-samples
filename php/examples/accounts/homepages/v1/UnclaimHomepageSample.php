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
// [START merchantapi_unclaim_homepage]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\HomepageServiceClient;
use Google\Shopping\Merchant\Accounts\V1\UnclaimHomepageRequest;

/**
 * This class demonstrates how to unclaim the homepage for a given Merchant Center account.
 */
class UnclaimHomepage
{
    /**
     * Unclaims the homepage for a given Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @return void
     * @throws ApiException if the API call fails.
     */
    public static function unclaimHomepageSample(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $homepageServiceClient = new HomepageServiceClient($options);

        // Creates Homepage name to identify Homepage.
        // The name has the format: accounts/{account}/homepage
        $name = "accounts/" . $config['accountId'] . "/homepage";

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = new UnclaimHomepageRequest(['name' => $name]);

            print "Sending Unclaim Homepage request:\n";

            $response = $homepageServiceClient->unclaimHomepage($request);

            print "Retrieved Homepage below\n";
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

        // Makes the call to unclaim the homepage.
        self::unclaimHomepageSample($config);
    }
}


// Run the script
$sample = new UnclaimHomepage();
$sample->callSample();
// [END merchantapi_unclaim_homepage]
