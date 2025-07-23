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
// [START merchantapi_create_file_supplemental_product_data_source]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\FileInput;
use Google\Shopping\Merchant\DataSources\V1beta\SupplementalProductDataSource;

/**
 * This class demonstrates how to create a File Supplemental product datasource for the "en" and
 * "GB" `feedLabel` and `contentLanguage` combination. This supplemental feed is eligible to be
 * linked to both a multiple-label primary feed and/or a primary feed with the same `feedLabel` and
 * `contentLanguage` combination.
 */
class CreateFileSupplementalProductDataSourceSample
{
    private static function getFileInput(): FileInput
    {
        // If FetchSettings is not set, then this will be an `UPLOAD` file type
        // that you must manually upload via the Merchant Center UI.
        return (new FileInput())
            // FileName is required for `UPLOAD` fileInput type.
            ->setFileName('British T-shirts Supplemental Data');
    }

    /**
     * Creates a new supplemental product data source.
     *
     * @param string $merchantId The Merchant Center account ID.
     * @param string $displayName The displayed data source name in the Merchant Center UI.
     * @param FileInput $fileInput The file input data that this data source will receive.
     * @return string The name of the newly created data source.
     */
    public static function createDataSource(string $merchantId, string $displayName, FileInput $fileInput): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // The parent account of the data source.
        $parent = sprintf('accounts/%s', $merchantId);

        // Creates the create data source request.
        $request = (new CreateDataSourceRequest())
            ->setParent($parent)
            ->setDataSource(
                (new DataSource())
                    ->setDisplayName($displayName)
                    ->setSupplementalProductDataSource(
                        (new SupplementalProductDataSource())
                            ->setContentLanguage('en')
                            ->setFeedLabel('GB')
                    )
                    ->setFileInput($fileInput)
            );

        print('Sending create Supplemental Product DataSource request' . PHP_EOL);
        try {
            // Makes the API call.
            $response = $dataSourcesServiceClient->createDataSource($request);
            print('Created DataSource Name below' . PHP_EOL);
            print($response->getName() . PHP_EOL);
            return $response->getName();
        } catch (ApiException $ex) {
            print('Call failed with message: ' . $ex->getMessage() . PHP_EOL);
            exit(1);
        }
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

$sample = new CreateFileSupplementalProductDataSourceSample();
$sample->callSample();
// [END merchantapi_create_file_supplemental_product_data_source]