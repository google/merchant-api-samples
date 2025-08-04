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
// [START merchantapi_update_businessinfo]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1\BusinessInfo;
use Google\Shopping\Merchant\Accounts\V1\Client\BusinessInfoServiceClient;
use Google\Shopping\Merchant\Accounts\V1\UpdateBusinessInfoRequest;
use Google\Type\PostalAddress;

/**
 * This class demonstrates how to update a BusinessInfo to a new address.
 */
class UpdateBusinessInfoSample
{

    /**
     * Updates a BusinessInfo to a new address.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the account ID.
     *
     * @return void
     */
    public static function updateBusinessInfoSample(array $config): void
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

        // Create a BusinessInfo with the updated fields.
        // Note that you cannot update the RegionCode or the Phone of the business
        $businessInfo = new BusinessInfo([
            'name' => $name,
            'address' => new PostalAddress([
                'language_code' => 'en',
                'postal_code' => 'C1107',
                'address_lines' => [
                    'Av. Alicia Moreau de Justo 350, Cdad. Autónoma de Buenos Aires, Argentina'
                ]
            ])
        ]);

        $fieldMask = (new FieldMask())->setPaths(['address']);

        try {
            $request = new UpdateBusinessInfoRequest([
                'business_info' => $businessInfo,
                'update_mask' => $fieldMask
            ]);

            print "Sending Update BusinessInfo request\n";
            $response = $businessInfoServiceClient->updateBusinessInfo($request);
            print "Updated BusinessInfo Name below\n";
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
        self::updateBusinessInfoSample($config);
    }
}

// Run the script
$sample = new UpdateBusinessInfoSample();
$sample->callSample();
// [END merchantapi_update_businessinfo]
