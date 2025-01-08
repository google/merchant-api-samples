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
use Google\Shopping\Merchant\DataSources\V1beta\DeleteDataSourceRequest;

/**
 * Class to demonstrate deleting a datasource.
 */
class DeleteDataSourceSample
{
    // ENSURE you fill in the datasource ID for the sample to work.
    private const DATASOURCE_ID = 'INSERT_DATASOURCE_ID';

    // [START DeleteDataSource]
    /**
     * Deletes a DataSource.
     *
     * @param int $merchantId The Merchant Center Account ID.
     * @param string $dataSourceId The data source ID.
     */
    public function deleteDataSource(int $merchantId, string $dataSourceId): void
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
        $request = (new DeleteDataSourceRequest())
            ->setName($name);

        print('Sending deleteDataSource request' . PHP_EOL);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $dataSourcesServiceClient->deleteDataSource($request);
            print('Delete successful, note that it may take a few minutes for the delete to update in the system.' . PHP_EOL);
        } catch (ApiException $ex) {
            print('Call failed with message: ' . $ex->getMessage() . PHP_EOL);
        }
    }
    // [END DeleteDataSource]

    // Helper to execute the sample.
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        self::deleteDataSource($merchantId, self::DATASOURCE_ID);
    }
}

$sample = new DeleteDataSourceSample();
$sample->callSample();