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
// [START merchantapi_create_file_regional_inventory_data_source]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\FileInput;
use Google\Shopping\Merchant\DataSources\V1beta\RegionalInventoryDataSource;

/**
 * This class demonstrates how to create a regional inventory datasource
 * with a file input.
 */
class CreateFileRegionalInventoryDataSourceSample
{
    private static function getFileInput(): FileInput
    {
        // If FetchSettings is not set, then this will be an `UPLOAD` file type
        // that you must manually upload via the Merchant Center UI.
        return (new FileInput())
            // FileName is required for `UPLOAD` fileInput type.
            ->setFileName('British T-shirts Regional Inventory Data');
    }

    /**
     * Creates a regional inventory data source.
     *
     * @param string $merchantId The Merchant Center account ID.
     * @param string $displayName The displayed datasource name in the Merchant Center UI.
     * @param FileInput $fileInput The file input data that this datasource will receive.
     * @return string The name of the newly created data source.
     * @throws ApiException If the API call fails.
     */
    public static function createDataSource(string $merchantId, string $displayName, FileInput $fileInput): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // The parent resource where this data source will be created.
        // Format: accounts/{merchantId}
        $parent = sprintf('accounts/%s', $merchantId);

        // RegionalInventoryDataSources can only be created for a specific `feedLabel` and
        // `contentLanguage` combination.
        $regionalInventoryDataSource = (new RegionalInventoryDataSource())
            ->setContentLanguage('en')
            ->setFeedLabel('GB');

        // Creates the data source object.
        $dataSource = (new DataSource())
            ->setDisplayName($displayName)
            ->setRegionalInventoryDataSource($regionalInventoryDataSource)
            ->setFileInput($fileInput);

        // Creates the request.
        $request = (new CreateDataSourceRequest())
            ->setParent($parent)
            ->setDataSource($dataSource);

        // Sends the request to the API.
        print('Sending Create Regional Inventory DataSource request' . PHP_EOL);
        $response = $dataSourcesServiceClient->createDataSource($request);
        print('Inserted DataSource Name below' . PHP_EOL);
        print($response->getName() . PHP_EOL);
        return $response->getName();
    }

    // Helper to execute the sample.
    function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'British Regional Inventory File';

        $fileInput = self::getFileInput();

        $this->createDataSource($merchantId, $displayName, $fileInput);
    }
}

$sample = new CreateFileRegionalInventoryDataSourceSample();
$sample->callSample();
// [END merchantapi_create_file_regional_inventory_data_source]