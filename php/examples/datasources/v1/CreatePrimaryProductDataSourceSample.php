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

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_create_primary_product_data_source]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1\DataSource;
use Google\Shopping\Merchant\DataSources\V1\PrimaryProductDataSource;
use Google\Shopping\Type\Destination\DestinationEnum;

/**
 * This class demonstrates how to create a primary product datasource for the "en" and "GB"
 * `feedLabel` and `contentLanguage` combination.
 */
class CreatePrimaryProductDataSourceSample
{
    /**
     * A helper function to create the parent string for DataSource resources.
     *
     * @param string $accountId The Merchant Center account ID.
     * @return string The parent resource name in the format `accounts/{account_id}`.
     */
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }

    /**
     * Creates a new primary product data source.
     *
     * @param array $config The configuration array containing the account ID.
     * @param string $displayName The display name for the new data source.
     * @return void
     */
    public static function createDataSourceSample(array $config, string $displayName): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a DataSourcesServiceClient.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // Constructs the parent resource name from the account ID.
        $parent = self::getParent($config['accountId']);

        // Defines the primary product data source specific settings.
        $primaryProductDataSource = new PrimaryProductDataSource([
            'countries' => ['GB'],
            'content_language' => 'en',
            'feed_label' => 'GB',
            // The destinations do not necessarily have to be explicitly listed in which case the
            // default enabled destinations will be used.
            'destinations' => [
                new PrimaryProductDataSource\Destination([
                    'destination' => DestinationEnum::SHOPPING_ADS,
                    'state' => PrimaryProductDataSource\Destination\State::ENABLED
                ]),
                new PrimaryProductDataSource\Destination([
                    'destination' => DestinationEnum::FREE_LISTINGS,
                    'state' => PrimaryProductDataSource\Destination\State::DISABLED
                ])
            ]
        ]);

        // Creates the DataSource object.
        $dataSource = new DataSource([
            'display_name' => $displayName,
            'primary_product_data_source' => $primaryProductDataSource
        ]);

        // Prepares the request message to create the data source.
        $request = new CreateDataSourceRequest([
            'parent' => $parent,
            'data_source' => $dataSource
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            print "Sending Create PrimaryProduct DataSource request\n";
            // Issues the create data source request.
            $response = $dataSourcesServiceClient->createDataSource($request);
            print("Created DataSource below\n");
            print($response->serializeToJsonString() . PHP_EOL);
        } catch (ApiException $e) {
            printf("ApiException was thrown: %s\n", $e->getMessage());
        }
    }

    /**
     * Helper to execute the sample.
     *
     * @return void
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The displayed datasource name in the Merchant Center UI.
        $displayName = "British Primary Product Data";

        self::createDataSourceSample($config, $displayName);
    }
}

// Run the script.
$sample = new CreatePrimaryProductDataSourceSample();
$sample->callSample();
// [END merchantapi_create_primary_product_data_source]