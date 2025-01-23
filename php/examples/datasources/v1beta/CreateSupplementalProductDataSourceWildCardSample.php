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
// [START merchantapi_create_supplemental_product_data_source_wildcard]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\SupplementalProductDataSource;

/**
 * Class to demonstrate creating a Supplemental product datasource for all
 * `feedLabel` and `contentLanguage` combinations. This works only for API
 * supplemental feeds.
 */
class CreateSupplementalProductDataSourceWildCardSample
{
    /**
     * Creates a DataSource.
     *
     * @param int $merchantId The Merchant Center Account ID.
     * @param string $displayName The display name of the data source.
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

        // Creates the data source.
        $dataSource = (new DataSource())
            ->setDisplayName($displayName)
            ->setSupplementalProductDataSource(new SupplementalProductDataSource());

        // Creates the request.
        $request = (new CreateDataSourceRequest())
            ->setParent($parent)
            ->setDataSource($dataSource);

        print('Sending create SupplementalProduct DataSource request' . PHP_EOL);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $dataSourcesServiceClient->createDataSource($request);
            print('Created DataSource Name below' . PHP_EOL);
            print($response->getName() . PHP_EOL);
            return $response->getName();
        } catch (ApiException $ex) {
            print('Call failed with message: ' . $ex->getMessage() . PHP_EOL);
            return '';
        }
    }

    // Helper to execute the sample.
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'Supplemental API Product Data Wildcard';

        self::createDataSource($merchantId, $displayName);
    }
}


$sample = new CreateSupplementalProductDataSourceWildCardSample();
$sample->callSample();
// [END merchantapi_create_supplemental_product_data_source_wildcard]