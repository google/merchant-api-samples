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
// [START merchantapi_delete_region]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\RegionsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\DeleteRegionRequest;

/**
 * This class demonstrates how to delete a given region from a Merchant Center account.
 */
class DeleteRegionSample
{
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }
    public static function deleteRegionSample(array $config, string $regionId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        $parent = self::getParent($config['accountId']);

        // Creates region name to identify the region.
        $name = $parent . "/regions/" . $regionId;

        // Creates a client.
        $regionsServiceClient = new RegionsServiceClient($options);

        try {
            $request = new DeleteRegionRequest([
                'name' => $name
            ]);

            print "Sending Delete Region request\n";
            $regionsServiceClient->deleteRegion($request);
            print "Delete successful.\n";
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }

    public function callSample(): void
    {
        $config = Config::generateConfig();

        // Replace this with the ID of the region to be deleted.
        $regionId = "[INSERT REGION ID HERE]";
        self::deleteRegionSample($config, $regionId);
    }
}


$sample = new DeleteRegionSample();
$sample->callSample();
// [END merchantapi_delete_region]
