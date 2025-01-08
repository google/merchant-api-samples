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

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\PrimaryProductDataSource;
use Google\Shopping\Merchant\DataSources\V1beta\PrimaryProductDataSource\Channel;

/**
 * Sample class to create a primary product datasource for the "en" and "GB"
 * `feedLabel` and `contentLanguage` combination.
 */
class CreatePrimaryProductDataSourceSample
{
    // [START CreatePrimaryProductDataSource]
    /**
     * Creates a new primary product data source.
     *
     * @param int    $merchantId The Merchant Center account ID.
     * @param string $displayName The displayed data source name in the Merchant Center UI.
     * @return string The name of the newly created data source.
     */
    function createDataSource(int $merchantId, string $displayName): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // Format: accounts/{merchantId}
        $parent = sprintf('accounts/%s', $merchantId);

        // The type of data that this datasource will receive.
        $primaryProductDataSource = (new PrimaryProductDataSource())
            // Channel can be "ONLINE_PRODUCTS" or "LOCAL_PRODUCTS" or "PRODUCTS" .
            // While accepted, datasources with channel "products" representing unified products
            // currently cannot be used with the Products bundle.
            ->setChannel(Channel::ONLINE_PRODUCTS)
            ->setCountries(['GB'])
            ->setContentLanguage('en')
            ->setFeedLabel('GB');

        // Creates the data source.
        $dataSource = (new DataSource())
            ->setDisplayName($displayName)
            ->setPrimaryProductDataSource($primaryProductDataSource);

        // Creates the request.
        $request = (new CreateDataSourceRequest())
            ->setParent($parent)
            ->setDataSource($dataSource);

        // Calls the API and catches and prints any network failures/errors.
        try {
            print('Sending Create Primary Product DataSource request' . PHP_EOL);
            $response = $dataSourcesServiceClient->createDataSource($request);
            print('Created DataSource Name below' . PHP_EOL);
            print($response->getName() . PHP_EOL);
            return $response->getName();
        } catch (ApiException $ex) {
            print('Call failed with message: ' . $ex->getMessage() . PHP_EOL);
            exit(1);
        }
    }
    // [END CreatePrimaryProductDataSource]

    // Helper to execute the sample.
    function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'British Primary Product Data';

        $this->createDataSource($merchantId, $displayName);
    }
}


$sample = new CreatePrimaryProductDataSourceSample();
$sample->callSample();