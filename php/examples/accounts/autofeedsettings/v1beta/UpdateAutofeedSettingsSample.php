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
// [START merchantapi_update_autofeedsettings]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1beta\AutofeedSettings;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AutofeedSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\UpdateAutofeedSettingsRequest;

/**
 * This class demonstrates how to update AutofeedSettings to be enabled.
 */
class UpdateAutofeedSettingsSample
{

    /**
     * Update AutofeedSettings to be enabled.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @return void
     */
    public static function updateAutofeedSettingsSample(array $config): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Create options for the client.
        $options = ['credentials' => $credentials];

        // Create a client.
        $autofeedSettingsServiceClient = new AutofeedSettingsServiceClient($options);

        // Create the AutofeedSettings name.
        $name = "accounts/" . $config['accountId'] . "/autofeedSettings";

        // Create AutofeedSettings object.
        $autofeedSettings = (new AutofeedSettings())
            ->setName($name);

        // Create FieldMask.
        $fieldMask = (new FieldMask())
            ->setPaths(["*"]);

        // Call the API.
        try {
            // Prepare the request.
            $request = (new UpdateAutofeedSettingsRequest())
                ->setAutofeedSettings($autofeedSettings)
                ->setUpdateMask($fieldMask);

            print "Sending Update AutofeedSettings request\n";
            $response = $autofeedSettingsServiceClient->updateAutofeedSettings($request);
            print "Updated AutofeedSettings Name below\n";
            print $response->getName() . "\n";
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

        self::updateAutofeedSettingsSample($config);
    }
}

// Run the script
$sample = new UpdateAutofeedSettingsSample();
$sample->callSample();
// [END merchantapi_update_autofeedsettings]
