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

// [START merchantapi_update_automaticimprovements]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1beta\AutomaticImageImprovements;
use Google\Shopping\Merchant\Accounts\V1beta\AutomaticImageImprovements\ImageImprovementsAccountLevelSettings;
use Google\Shopping\Merchant\Accounts\V1beta\AutomaticImprovements;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AutomaticImprovementsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\AutomaticItemUpdates;
use Google\Shopping\Merchant\Accounts\V1beta\AutomaticItemUpdates\ItemUpdatesAccountLevelSettings;
use Google\Shopping\Merchant\Accounts\V1beta\AutomaticShippingImprovements;
use Google\Shopping\Merchant\Accounts\V1beta\UpdateAutomaticImprovementsRequest;

/**
 * This class demonstrates how to update AutomaticImprovements to be enabled.
 */
class UpdateAutomaticImprovementsSample
{
    /**
     * Helper function to construct the resource name for AutomaticImprovements.
     *
     * @param string $accountId The Merchant Center account ID.
     * @return string The resource name in the format: accounts/{account}/automaticImprovements
     */
    private static function getAutomaticImprovementsName(string $accountId): string
    {
        return sprintf("accounts/%s/automaticImprovements", $accountId);
    }

    /**
     * Updates the automatic improvements settings for a Merchant Center account.
     * This sample enables all automatic improvements.
     *
     * @param array $config The configuration array containing the account ID.
     * @return void
     */
    public static function updateAutomaticImprovementsSample(array $config): void
    {
        // Obtains OAuth credentials for authentication.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Contructs an options array for the client.
        $options = ['credentials' => $credentials];

        // Creates a new AutomaticImprovementsServiceClient.
        $automaticImprovementsServiceClient = new AutomaticImprovementsServiceClient($options);

        // Constructs the full resource name for the automatic improvements settings.
        $name = self::getAutomaticImprovementsName($config['accountId']);

        // Prepares the AutomaticImprovements object with all settings enabled.
        $automaticImprovements = new AutomaticImprovements([
            'name' => $name,
            'item_updates' => new AutomaticItemUpdates([
                'account_item_updates_settings' => new ItemUpdatesAccountLevelSettings([
                    'allow_price_updates' => true,
                    'allow_availability_updates' => true,
                    'allow_strict_availability_updates' => true,
                    'allow_condition_updates' => true
                ])
            ]),
            'image_improvements' => new AutomaticImageImprovements([
                'account_image_improvements_settings' => new ImageImprovementsAccountLevelSettings([
                    'allow_automatic_image_improvements' => true
                ])
            ]),
            'shipping_improvements' => new AutomaticShippingImprovements([
                'allow_shipping_improvements' => true
            ])
        ]);

        // Creates a FieldMask to indicate that all paths provided in $automaticImprovements
        // should be updated. The "*" path means to replace all updatable fields.
        $fieldMask = new FieldMask(['paths' => ['*']]);

        // Creates the UpdateAutomaticImprovementsRequest.
        $request = new UpdateAutomaticImprovementsRequest([
            'automatic_improvements' => $automaticImprovements,
            'update_mask' => $fieldMask
        ]);

        printf("Sending Update AutomaticImprovements request%s", PHP_EOL);

        try {
            // Makes the API call to update automatic improvements settings.
            $response = $automaticImprovementsServiceClient->updateAutomaticImprovements($request);

            printf("Updated AutomaticImprovements Name below%s", PHP_EOL);
            printf("%s%s", $response->getName(), PHP_EOL);
        } catch (ApiException $e) {
            printf("ApiException was thrown: %s%s", $e->getMessage(), PHP_EOL);
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
        self::updateAutomaticImprovementsSample($config);
    }
}

// Runs the script.
$sample = new UpdateAutomaticImprovementsSample();
$sample->callSample();
// [END merchantapi_update_automaticimprovements]
