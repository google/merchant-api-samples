<?php
/**
 * Copyright 2024 Google LLC
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

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_create_primary_product_data_source_multiple_languages]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\PrimaryProductDataSource;
use Google\Shopping\Merchant\DataSources\V1beta\PrimaryProductDataSource\Channel;

/**
 * This class demonstrates how to create a primary product datasource for all `feedLabel` and
 * `contentLanguage` combinations.
 */

class CreatePrimaryProductDataSourceMultipleLanguagesSample
{
    /**
     * Creates a primary product data source.
     *
     * @param int    $merchantId The Merchant Center account ID.
     * @param string $displayName The displayed data source name in the Merchant Center UI.
     *
     * @return string The name of the newly created data source.
     */
    public function createDataSource(int $merchantId, string $displayName): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        $parent = sprintf('accounts/%s', $merchantId);

        // The type of data that this datasource will receive.
        $primaryProductDataSource = (new PrimaryProductDataSource())
            // Channel can be "ONLINE_PRODUCTS" or "LOCAL_PRODUCTS" or "PRODUCTS" .
            // While accepted, datasources with channel "products" representing unified products
            // currently cannot be used with the Products sub-API.
            ->setChannel(Channel::ONLINE_PRODUCTS)
            ->setCountries(['GB']);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $dataSourcesServiceClient->createDataSource(
                (new CreateDataSourceRequest())
                    ->setParent($parent)
                    ->setDataSource(
                        (new DataSource())
                            ->setDisplayName($displayName)
                            ->setPrimaryProductDataSource($primaryProductDataSource)
                    )
            );
            printf('Created DataSource Name below:' . PHP_EOL);
            printf('%s' . PHP_EOL, $response->getName());
            return $response->getName();
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
            exit(1);
        }
    }

    // Helper to execute the sample.
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'Primary Product Data Multiple Languages';

        self::createDataSource($merchantId, $displayName);
    }
}


$sample = new CreatePrimaryProductDataSourceMultipleLanguagesSample();
$sample->callSample();
// [END merchantapi_create_primary_product_data_source_multiple_languages]
