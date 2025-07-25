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
// [START merchantapi_get_autofeed_settings]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AutofeedSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\GetAutofeedSettingsRequest;

/**
 * This class demonstrates how to get the autofeed settings of a Merchant Center account.
 */
class GetAutofeedSettingsSample
{
    /**
     * Get the autofeed settings of a Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @return void
     */
    public static function getAutofeedSettingsSample(array $config): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Create options for the client.
        $options = ['credentials' => $credentials];

        // Create a client.
        $autofeedSettingsServiceClient = new AutofeedSettingsServiceClient($options);

        // Create the AutofeedSettings name.
        $name = "accounts/" . $config['accountId'] . "/autofeedSettings";

        // Call the API.
        try {
            // Prepare the request.
            $request = (new GetAutofeedSettingsRequest())
                ->setName($name);

            print "Sending get AutofeedSettings request:\n";
            $response = $autofeedSettingsServiceClient->getAutofeedSettings($request);

            print "Retrieved AutofeedSettings below\n";
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
        self::getAutofeedSettingsSample($config);
    }
}


// Run the script
$sample = new GetAutofeedSettingsSample();
$sample->callSample();
// [END merchantapi_get_autofeed_settings]
