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
// [START merchantapi_update_business_identity]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1beta\BusinessIdentity;
use Google\Shopping\Merchant\Accounts\V1beta\Client\BusinessIdentityServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\UpdateBusinessIdentityRequest;

/**
 * This class demonstrates how to update a business identity.
 */
class UpdateBusinessIdentitySample
{

    /**
     * Updates the business identity for the given Merchant Center account.
     *
     * @param array $config The configuration data containing the account ID.
     * @return void
     */
    public static function updateBusinessIdentity($config)
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $businessIdentityServiceClient = new BusinessIdentityServiceClient($options);

        // Creates BusinessIdentity name to identify the BusinessIdentity.
        // The name has the format: accounts/{account}/businessIdentity
        $name = "accounts/" . $config['accountId'] . "/businessIdentity";

        // Create a BusinessIdentity with the updated fields.
        $businessIdentity = (new BusinessIdentity())
            ->setName($name)
            ->setSmallBusiness(
                (new BusinessIdentity\IdentityAttribute())
                    ->setIdentityDeclaration(
                        BusinessIdentity\IdentityAttribute\IdentityDeclaration::SELF_IDENTIFIES_AS
                    )
            );

        $fieldMask = (new FieldMask())
            ->setPaths(['small_business']);

        try {
            $request = (new UpdateBusinessIdentityRequest())
                ->setBusinessIdentity($businessIdentity)
                ->setUpdateMask($fieldMask);

            print "Sending Update BusinessIdentity request\n";
            $response = $businessIdentityServiceClient->updateBusinessIdentity($request);
            print "Updated BusinessIdentity below\n";
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
        self::updateBusinessIdentity($config);
    }
}

// Run the script
$sample = new UpdateBusinessIdentitySample();
$sample->callSample();
// [END merchantapi_update_business_identity]
