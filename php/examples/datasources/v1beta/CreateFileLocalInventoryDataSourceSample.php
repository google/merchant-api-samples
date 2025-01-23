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
// [START merchantapi_create_file_local_inventory_data_source]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\FileInput;
use Google\Shopping\Merchant\DataSources\V1beta\LocalInventoryDataSource;

/**
 * This class demonstrates how to create a local inventory datasource with a 
 * file input.
 */
class CreateFileLocalInventoryDataSourceSample
{

    private static function getFileInput(): FileInput
    {
        // If FetchSettings is not set, then this will be an `UPLOAD` file type
        // that you must manually upload via the Merchant Center UI.
        return (new FileInput())
            // FileName is required for `UPLOAD` fileInput type.
            ->setFileName('British T-shirts Local Inventory Data');
    }

    public function createDataSource(string $merchantId, string $displayName, FileInput $fileInput): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        $parent = sprintf('accounts/%s', $merchantId);

        // As LocalInventoryDataSources are a type of file feed, therefore wildcards are not available.
        // LocalInventoryDataSources can only be created for a specific `feedLabel` and
        // `contentLanguage` combination.
        $localInventoryDataSource =
            (new LocalInventoryDataSource())
                ->setContentLanguage('en')
                ->setFeedLabel('GB');

        try {
            // Prepare the request message.
            $request = (new CreateDataSourceRequest())
                ->setParent($parent)
                ->setDataSource(
                    (new DataSource())
                        ->setDisplayName($displayName)
                        ->setLocalInventoryDataSource($localInventoryDataSource)
                        ->setFileInput($fileInput)
                );

            print('Sending Create Local Inventory DataSource request' . PHP_EOL);
            $response = $dataSourcesServiceClient->createDataSource($request);
            print('Inserted DataSource Name below' . PHP_EOL);
            print($response->getName() . PHP_EOL);
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
        }
    }

    // Helper to execute the sample.
    function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'British Primary Inventory File';

        $fileInput = self::getFileInput();

        $this->createDataSource($merchantId, $displayName, $fileInput);
    }

}

$sample = new CreateFileLocalInventoryDataSourceSample();
$sample->callSample();
// [END merchantapi_create_file_local_inventory_data_source]