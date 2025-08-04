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
// [START merchantapi_list_regions]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\RegionsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\ListRegionsRequest;

/**
 * This class demonstrates how to list all the regions for a given Merchant Center account.
 */
class ListRegionsSample
{

    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }

    public static function listRegionsSample(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $regionsServiceClient = new RegionsServiceClient($options);

        // Creates parent to identify the account from which to list all regions.
        $parent = self::getParent($config['accountId']);

        try {
            $request = new ListRegionsRequest([
                'parent' => $parent
            ]);

            print "Sending list regions request:\n";
            $response = $regionsServiceClient->listRegions($request);

            $count = 0;
            foreach ($response->iterateAllElements() as $element) {
                print $element->getName() . PHP_EOL;
                $count++;
            }
            print "The following count of elements were returned: ";
            print $count . PHP_EOL;

        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }

    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listRegionsSample($config);
    }
}

$sample = new ListRegionsSample();
$sample->callSample();
// [END merchantapi_list_regions]
