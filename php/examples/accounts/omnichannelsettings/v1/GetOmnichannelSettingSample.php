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
// [START merchantapi_get_omnichannel_setting]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\OmnichannelSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\GetOmnichannelSettingRequest;

/**
 * This class demonstrates how to get the omnichannel settings in a given
 * country for a given Merchant Center account.
 */
class GetOmnichannelSetting
{
    /**
     * Helper to create the name string.
     *
     * @param string $accountId The merchant account ID.
     * @param string $regionCode The region code of the setting.
     * @return string The name string in the format
     *     `accounts/{account}/omnichannelSettings/{omnichannelSetting}`.
     */
    private static function getName(string $accountId, string $regionCode): string
    {
        return sprintf('accounts/%s/omnichannelSettings/%s', $accountId, $regionCode);
    }

    /**
     * Retrieves the omnichannel settings for a given Merchant Center account.
     *
     * @param array $config The configuration file for authentication.
     * @param string $regionCode The country of the omnichannel setting.
     */
    public static function getOmnichannelSettingSample(
        array $config,
        string $regionCode
    ): void {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $omnichannelSettingsServiceClient = new OmnichannelSettingsServiceClient([
            'credentials' => $credentials
        ]);

        // Constructs the resource name.
        $name = self::getName($config['accountId'], $regionCode);

        // Creates the request.
        $request = new GetOmnichannelSettingRequest(['name' => $name]);

        // Calls the API and prints the response.
        try {
            printf("Sending get omnichannel setting request:%s", PHP_EOL);
            $response = $omnichannelSettingsServiceClient->getOmnichannelSetting($request);
            printf("Retrieved Omnichannel Setting below:%s", PHP_EOL);
            print $response->serializeToJsonString(true) . PHP_EOL;
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
        // The country which you're targeting.
        $regionCode = '{REGION_CODE}'; // Replace with your region code
        self::getOmnichannelSettingSample($config, $regionCode);
    }
}

// Runs the script.
$sample = new GetOmnichannelSetting();
$sample->callSample();
// [END merchantapi_get_omnichannel_setting]
