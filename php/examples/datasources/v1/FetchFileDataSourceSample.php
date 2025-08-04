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

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_fetch_file_data_source]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1\FetchDataSourceRequest;

/**
 * This class demonstrates how to fetch a specific FileInput DataSource for a given Merchant Center
 * account.
 */
class FetchFileDataSourceSample
{
    /**
     * Helper function to construct the full data source resource name.
     *
     * @param string $accountId The Merchant Center account ID.
     * @param string $dataSourceId The data source ID.
     * @return string The formatted data source name.
     */
    private static function buildDataSourceName(string $accountId, string $dataSourceId): string
    {
        return sprintf("accounts/%s/dataSources/%s", $accountId, $dataSourceId);
    }

    /**
     * Fetches a specific FileInput DataSource.
     *
     * @param array $config The configuration array containing the account ID.
     * @param string $dataSourceId The ID of the FileInput DataSource to fetch.
     */
    public static function fetchDataSource(array $config, string $dataSourceId): void
    {
        // Obtains OAuth token based on the user's configuration.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates service client configuration object using the credentials.
        $options = ['credentials' => $credentials];

        // Creates a DataSourcesServiceClient with the specified options.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // Constructs the full resource name for the data source.
        // The name has the format: accounts/{account}/datasources/{datasource}
        $name = self::buildDataSourceName($config['accountId'], $dataSourceId);

        // Creates the request to fetch the data source.
        $request = new FetchDataSourceRequest(['name' => $name]);

        // Calls the API and handles potential errors.
        try {
            print "Sending FETCH DataSource request:\n";
            // The fetchDataSource method does not return a response body on success.
            // It will throw an ApiException if the fetch fails or the data source is not found.
            // This operation works ONLY for FileInput DataSource types.
            $dataSourcesServiceClient->fetchDataSource($request);

            print "Successfully fetched DataSource.\n";

        } catch (ApiException $e) {
            printf("ApiException was thrown: %s\n", $e->getMessage());
        }
    }

    /**
     * Executes the sample code.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // ENSURE you fill in the datasource ID for the sample to work.
        // This ID should correspond to a FileInput DataSource.
        $dataSourceId = '10432397780';

        // Calls the method to fetch the data source.
        self::fetchDataSource($config, $dataSourceId);
    }
}

$sample = new FetchFileDataSourceSample();
$sample->callSample();
// [END merchantapi_fetch_file_data_source]
