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
// [START merchantapi_create_omnichannel_setting]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\OmnichannelSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\CreateOmnichannelSettingRequest;
use Google\Shopping\Merchant\Accounts\V1\InStock;
use Google\Shopping\Merchant\Accounts\V1\OmnichannelSetting;
use Google\Shopping\Merchant\Accounts\V1\OmnichannelSetting\LsfType;

/**
 * This class demonstrates how to create an omnichannel setting for a given
 * Merchant Center account in a given country.
 */
class CreateOmnichannelSetting
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
     * Creates an omnichannel setting for a given Merchant Center account.
     *
     * @param array $config The configuration file for authentication.
     * @param string $regionCode The country for the omnichannel setting.
     */
    public static function createOmnichannelSettingSample(
        array $config,
        string $regionCode
    ): void {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $omnichannelSettingsServiceClient = new OmnichannelSettingsServiceClient([
            'credentials' => $credentials
        ]);

        // Constructs the parent resource name.
        $parent = self::getParent($config['accountId']);

        // Creates the omnichannel setting with GHLSF type in the given country.
        $omnichannelSetting = new OmnichannelSetting([
            'region_code' => $regionCode,
            'lsf_type' => LsfType::GHLSF,
            'in_stock' => new InStock()
        ]);

        // Creates the request.
        $request = new CreateOmnichannelSettingRequest([
            'parent' => $parent,
            'omnichannel_setting' => $omnichannelSetting
        ]);

        // Calls the API and prints the response.
        try {
            printf("Sending create omnichannel setting request:%s", PHP_EOL);
            $response = $omnichannelSettingsServiceClient->createOmnichannelSetting($request);
            printf("Inserted Omnichannel Setting below:%s", PHP_EOL);
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
        $regionCode = '{REGION_CODE}';
        self::createOmnichannelSettingSample($config, $regionCode);
    }
}

// Runs the script.
$sample = new CreateOmnichannelSetting();
$sample->callSample();
// [END merchantapi_create_omnichannel_setting]
