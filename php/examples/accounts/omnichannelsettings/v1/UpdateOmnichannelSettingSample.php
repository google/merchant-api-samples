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
// [START merchantapi_update_omnichannel_setting]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1\Client\OmnichannelSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\InventoryVerification;
use Google\Shopping\Merchant\Accounts\V1\OmnichannelSetting;
use Google\Shopping\Merchant\Accounts\V1\UpdateOmnichannelSettingRequest;

/**
 * This class demonstrates how to update an omnichannel setting for a given
 * Merchant Center account in a given country.
 */
class UpdateOmnichannelSetting
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
     * Updates an omnichannel setting for a given Merchant Center account.
     *
     * @param array $config The configuration file for authentication.
     * @param string $regionCode The country of the omnichannel setting.
     * @param string $contact The name of the inventory verification contact.
     * @param string $email The email of the inventory verification contact.
     */
    public static function updateOmnichannelSettingSample(
        array $config,
        string $regionCode,
        string $contact,
        string $email
    ): void {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $omnichannelSettingsServiceClient = new OmnichannelSettingsServiceClient([
            'credentials' => $credentials
        ]);

        // Constructs the resource name.
        $name = self::getName($config['accountId'], $regionCode);

        // Creates the omnichannel setting with the updated fields.
        $omnichannelSetting = new OmnichannelSetting([
            'name' => $name,
            'inventory_verification' => new InventoryVerification([
                'contact' => $contact,
                'contact_email' => $email
            ])
        ]);

        // Creates the field mask to specify which fields to update.
        $fieldMask = new FieldMask([
            'paths' => ['inventory_verification']
        ]);

        // Creates the request.
        $request = new UpdateOmnichannelSettingRequest([
            'omnichannel_setting' => $omnichannelSetting,
            'update_mask' => $fieldMask
        ]);

        // Calls the API and prints the response.
        try {
            printf("Sending update omnichannel setting request:%s", PHP_EOL);
            $response = $omnichannelSettingsServiceClient->updateOmnichannelSetting($request);
            printf("Updated Omnichannel Setting below:%s", PHP_EOL);
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
        // The name of the inventory verification contact you want to update.
        $contact = '{NAME}';
        // The address of the inventory verification email you want to update.
        $email = '{EMAIL}';
        self::updateOmnichannelSettingSample($config, $regionCode, $contact, $email);
    }
}

// Runs the script.
$sample = new UpdateOmnichannelSetting();
$sample->callSample();
// [END merchantapi_update_omnichannel_setting]