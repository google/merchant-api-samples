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
// [START merchantapi_link_lfp_provider]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\LfpProvidersServiceClient;
use Google\Shopping\Merchant\Accounts\V1\LinkLfpProviderRequest;

/**
 * This class demonstrates how to link an LFP Provider for a given Merchant
 * Center account.
 */
class LinkLfpProviderSample
{
    /**
     * A helper function to create the LFP provider name string.
     *
     * @param string $accountId The Merchant Center account ID.
     * @param string $regionCode The region code for the omnichannel setting.
     * @param string $lfpProviderId The ID of the LFP provider.
     *
     * @return string The name has the format:
     * `accounts/{account}/omnichannelSettings/{omnichannelSetting}/lfpProviders/{lfpProvider}`
     */
    private static function getName(
        string $accountId,
        string $regionCode,
        string $lfpProviderId
    ): string {
        return sprintf(
            "accounts/%s/omnichannelSettings/%s/lfpProviders/%s",
            $accountId,
            $regionCode,
            $lfpProviderId
        );
    }

    /**
     * Links an LFP provider to a Merchant Center account.
     *
     * @param string $lfpProviderName The resource name of the LFP provider.
     * @param string $externalAccountId The external account ID with the provider.
     */
    public static function linkLfpProvider(
        string $lfpProviderName,
        string $externalAccountId
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $lfpProvidersServiceClient = new LfpProvidersServiceClient($options);

        // Creates the request with the LFP provider name and external account ID.
        $request = (new LinkLfpProviderRequest())
            ->setName($lfpProviderName)
            ->setExternalAccountId($externalAccountId);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending link lfp provider request:%s", PHP_EOL);
            // The call returns an empty response on success.
            $lfpProvidersServiceClient->linkLfpProvider($request);
            printf(
                "Successfully linked to LFP provider: %s%s",
                $lfpProviderName,
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

        // Replace with the actual region code you want to use.
        $regionCode = 'REGION_CODE'; // e.g., "US"
        $lfpProviderId = 'LFP_PROVIDER_ID';

        // The name of the lfp provider you want to link, returned from
        // `lfpProviders.findLfpProviders`.
        $lfpProviderName = self::getName(
            $config['accountId'],
            $regionCode,
            $lfpProviderId
        );

        // External account id by which this merchant is known to the LFP provider.
        $externalAccountId = $config['accountId'];

        self::linkLfpProvider($lfpProviderName, $externalAccountId);
    }
}

// Runs the sample.
LinkLfpProviderSample::callSample();
// [END merchantapi_link_lfp_provider]