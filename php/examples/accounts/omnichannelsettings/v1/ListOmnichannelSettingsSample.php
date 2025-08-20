<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
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
// [START merchantapi_list_omnichannel_settings]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\OmnichannelSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ListOmnichannelSettingsRequest;

/**
 * This class demonstrates how to get the list of omnichannel settings for a
 * given Merchant Center account.
 */
class ListOmnichannelSettings
{
    /**
     * Helper to create the parent string.
     *
     * @param string $accountId The merchant account ID.
     * @return string The parent string in the format `accounts/{account}`.
     */
    private static function getParent(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Lists the omnichannel settings for a given Merchant Center account.
     *
     * @param array $config The configuration file for authentication.
     */
    public static function listOmnichannelSettingsSample(array $config): void
    {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $omnichannelSettingsServiceClient = new OmnichannelSettingsServiceClient([
            'credentials' => $credentials
        ]);

        // Constructs the parent resource name.
        $parent = self::getParent($config['accountId']);

        // Creates the request.
        $request = new ListOmnichannelSettingsRequest(['parent' => $parent]);

        // Calls the API and prints the response.
        try {
            printf("Sending list omnichannel setting request:%s", PHP_EOL);
            $response = $omnichannelSettingsServiceClient->listOmnichannelSettings($request);

            $count = 0;
            // Iterates over all the omnichannel settings and prints them.
            foreach ($response->iterateAllElements() as $omnichannelSetting) {
                print $omnichannelSetting->serializeToJsonString(true) . PHP_EOL;
                $count++;
            }
            printf("The following count of elements were returned: %d%s", $count, PHP_EOL);
        } catch (ApiException $e) {
            printf("An error has occured: %s", $e->getMessage());
        }
    }

    /**
     * Executes the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listOmnichannelSettingsSample($config);
    }
}

// Runs the script.
$sample = new ListOmnichannelSettings();
$sample->callSample();
// [END merchantapi_list_omnichannel_settings]
