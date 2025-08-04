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
// [START merchantapi_get_datasource]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1\DataSource;
use Google\Shopping\Merchant\DataSources\V1\GetDataSourceRequest;

/**
 * Class to demonstrate getting a specific datasource for a given Merchant
 * Center account.
 */
class GetDataSourceSample
{
    // ENSURE you fill in the datasource ID for the sample to work.
    private const DATASOURCE_ID = 'INSERT_DATASOURCE_ID';

    /**
     * Gets a DataSource.
     *
     * @param int $merchantId The Merchant Center Account ID.
     * @param string $dataSourceId The data source ID.
     * @return DataSource The retrieved data source.
     */
    public function getDataSource(int $merchantId, string $dataSourceId): DataSource
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // Creates the data source name.
        $name = sprintf('accounts/%s/dataSources/%s', $merchantId, $dataSourceId);

        // Creates the request.
        $request = (new GetDataSourceRequest())
            ->setName($name);

        print('Sending GET DataSource request:' . PHP_EOL);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $dataSourcesServiceClient->getDataSource($request);
            print('Retrieved DataSource below' . PHP_EOL);
            print($response->serializeToJsonString() . PHP_EOL);
            return $response;
        } catch (ApiException $ex) {
            print('Call failed with message: ' . $ex->getMessage() . PHP_EOL);
            return new DataSource();
        }
    }

    // Helper to execute the sample.
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        self::getDataSource($merchantId, self::DATASOURCE_ID);
    }
}

$sample = new GetDataSourceSample();
$sample->callSample();
// [END merchantapi_get_datasource]