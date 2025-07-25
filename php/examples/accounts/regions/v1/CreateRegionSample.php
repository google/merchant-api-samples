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
// [START merchantapi_create_region]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\RegionsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\CreateRegionRequest;
use Google\Shopping\Merchant\Accounts\V1beta\Region;
use Google\Shopping\Merchant\Accounts\V1beta\Region\PostalCodeArea;
use Google\Shopping\Merchant\Accounts\V1beta\Region\PostalCodeArea\PostalCodeRange;


/**
 * This class demonstrates how to create a region for a Merchant Center account.
 */
class CreateRegionSample
{
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }


    public static function createRegionSample(array $config, string $regionId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $regionsServiceClient = new RegionsServiceClient($options);

        // Creates parent to identify where to insert the region.
        $parent = self::getParent($config['accountId']);

        try {
            // Creates the request.
            $request = new CreateRegionRequest([
                'parent' => $parent,
                'region_id' => $regionId,
                'region' => (new Region([
                    'display_name' => 'New York',
                    'postal_code_area' => (new PostalCodeArea([
                        'region_code' => 'US',
                        'postal_codes' => [
                            (new PostalCodeRange([
                                'begin' => '10001',
                                'end' => '10282'
                            ]))
                        ]
                    ]))
                ]))
            ]);

            print "Sending Create Region request\n";
            $response = $regionsServiceClient->createRegion($request);
            print "Inserted Region Name below\n";
            print $response->getName() . "\n";

        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }

    public function callSample(): void
    {
        $config = Config::generateConfig();
        // Replace this with the ID of the region to be created.
        $regionId = "[INSERT REGION ID HERE]";
        self::createRegionSample($config, $regionId);
    }
}

$sample = new CreateRegionSample();
$sample->callSample();
// [END merchantapi_create_region]
