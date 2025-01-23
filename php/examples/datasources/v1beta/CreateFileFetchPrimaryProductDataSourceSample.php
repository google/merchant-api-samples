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
// [START merchantapi_create_file_fetch_primary_product_data_source]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\FileInput;
use Google\Shopping\Merchant\DataSources\V1beta\FileInput\FetchSettings;
use Google\Shopping\Merchant\DataSources\V1beta\PrimaryProductDataSource;
use Google\Type\TimeOfDay;

/**
 * This class demonstrates how to insert a primary product datasource with a
 * file input that is fetched from a URL.
 */
class CreateFileFetchPrimaryProductDataSourceSample
{

    private static function getFileInput(): FileInput
    {
        // If FetchSettings were not set, then this would be an `UPLOAD` file
        // type that you must manually upload via the Merchant Center UI.
        $fetchSettings =
            (new FetchSettings())
                ->setEnabled(true)
                // Note that the system only respects hours for the fetch schedule.
                ->setTimeOfDay((new TimeOfDay())->setHours(22))
                ->setTimeZone('Europe/London')
                ->setFrequency(FetchSettings\Frequency::FREQUENCY_DAILY)
                ->setFetchUri('https://example.file.com/products');

        // Creates the file input with the fetch settings
        return (new FileInput())->setFetchSettings($fetchSettings);
    }

    /**
     * Creates a new data source
     *
     * @param string $merchantId The Merchant Center Account ID
     * @param string $displayName The displayed data source name in the Merchant Center UI
     * @param FileInput $fileInput The file input data that this datasource will receive
     * @return string The name of the newly created data source
     */
    public static function createDataSource(string $merchantId, string $displayName, FileInput $fileInput): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // The account to create the data source for
        $parent = sprintf('accounts/%s', $merchantId);

        // The type of data that this datasource will receive.
        $primaryProductDataSource =
            (new PrimaryProductDataSource())
                // Channel can be "ONLINE_PRODUCTS" or "LOCAL_PRODUCTS" or "PRODUCTS" .
                // While accepted, datasources with channel "products" currently cannot be used
                // with the Products bundle.
                ->setChannel(PrimaryProductDataSource\Channel::ONLINE_PRODUCTS)
                ->setCountries(['GB'])
                // Wildcard feeds are not possible for file feeds, so `contentLanguage` and `feedLabel`
                // must be set.
                ->setContentLanguage('en')
                ->setFeedLabel('GB');

        // Creates the data source
        try {
            // Creates the request
            $request =
                (new CreateDataSourceRequest())
                    ->setParent($parent)
                    ->setDataSource(
                        (new DataSource())
                            ->setDisplayName($displayName)
                            ->setPrimaryProductDataSource($primaryProductDataSource)
                            ->setFileInput($fileInput)
                    );

            print('Sending Create File Fetch PrimaryProduct DataSource request' . PHP_EOL);
            // Makes the request
            $response = $dataSourcesServiceClient->createDataSource($request);
            print('Created DataSource Name below' . PHP_EOL);
            print($response->getName() . PHP_EOL);
            return $response->getName();
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
            exit(1);
        }
    }

    public static function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'British File Fetch Primary Product Data';

        // The file input data that this datasource will receive.
        $fileInput = self::getFileInput();

        self::createDataSource($merchantId, $displayName, $fileInput);
    }
}

$sample = new CreateFileFetchPrimaryProductDataSourceSample();
$sample->callSample();
// [END merchantapi_create_file_fetch_primary_product_data_source]