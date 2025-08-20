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
// [START merchantapi_find_lfp_providers]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\LfpProvidersServiceClient;
use Google\Shopping\Merchant\Accounts\V1\FindLfpProvidersRequest;
use Google\Shopping\Merchant\Accounts\V1\LfpProvider;

/**
 * This class demonstrates how to get the LFP Providers for a given Merchant
 * Center account.
 */
class FindLfpProvidersSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The Merchant Center account ID.
     * @param string $regionCode The region code for the omnichannel setting.
     *
     * @return string The parent has the format:
     * `accounts/{account}/omnichannelSettings/{omnichannelSetting}`
     */
    private static function getParent(string $accountId, string $regionCode): string
    {
        return sprintf(
            "accounts/%s/omnichannelSettings/%s",
            $accountId,
            $regionCode
        );
    }

    /**
     * Retrieves all LFP providers for a given account and region.
     *
     * @param array $config The configuration data for authentication.
     * @param string $regionCode The CLDR country code of the target country.
     */
    public static function findLfpProviders(
        array $config,
        string $regionCode
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $lfpProvidersServiceClient = new LfpProvidersServiceClient($options);

        // Creates the parent resource name from the account ID and region code.
        $parent = self::getParent($config['accountId'], $regionCode);

        // Creates the request.
        $request = (new FindLfpProvidersRequest())
            ->setParent($parent);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending find LFP providers request:%s", PHP_EOL);
            $response = $lfpProvidersServiceClient->findLfpProviders($request);

            $count = 0;

            // Iterates over all the LFP providers in the response and prints them.
            foreach ($response->iterateAllElements() as $lfpProvider) {
                // The LfpProvider object is a Protobuf message.
                // We are printing it as a JSON string for readability.
                print($lfpProvider->serializeToJsonString(true) . PHP_EOL);
                $count++;
            }
            printf(
                "The following count of elements were returned: %d%s",
                $count,
                PHP_EOL
            );
        } catch (ApiException $e) {
            printf("An error has occured: %s%s", PHP_EOL, $e);
        }
    }

    /**
     * Helper to execute the sample.
     */
    public static function callSample(): void
    {
        $config = Config::generateConfig();

        // The country you're targeting.
        $regionCode = '{REGION_CODE}';

        self::findLfpProviders($config, $regionCode);
    }
}

// Runs the sample.
FindLfpProvidersSample::callSample();
// [END merchantapi_find_lfp_providers]
