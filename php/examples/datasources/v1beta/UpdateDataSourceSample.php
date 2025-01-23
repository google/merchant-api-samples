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
// [START merchantapi_update_data_source]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\DataSources\V1beta\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\UpdateDataSourceRequest;

/**
 * Class to demonstrate updating a datasource to change its name in the MC UI.
 */
class UpdateDataSourceSample
{
    // ENSURE you fill in the datasource ID for the sample to work.
    private const DATASOURCE_ID = 'INSERT_DATASOURCE_ID';

    /**
     * Updates a DataSource.
     *
     * @param int $merchantId The Merchant Center Account ID.
     * @param string $displayName The new display name of the data source.
     * @param string $dataSourceId The data source ID.
     * @return string The name of the updated data source.
     */
    public function updateDataSource(int $merchantId, string $displayName, string $dataSourceId): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        // Creates the data source name.
        $name = sprintf('accounts/%s/dataSources/%s', $merchantId, $dataSourceId);

        // Creates the data source.
        $dataSource = (new DataSource())
            ->setDisplayName($displayName)
            ->setName($name);

        // Creates a FieldMask to specify which fields to update.
        $updateMask = new FieldMask([
            'paths' => ['display_name']
        ]);

        // Creates the request.
        $request = (new UpdateDataSourceRequest())
            ->setDataSource($dataSource)
            ->setUpdateMask($updateMask);

        print('Sending Update DataSource request' . PHP_EOL);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $dataSourcesServiceClient->updateDataSource($request);
            print('Updated DataSource Name below' . PHP_EOL);
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

        // The updated displayed datasource name in the Merchant Center UI.
        $displayName = 'new name';

        self::updateDataSource($merchantId, $displayName, self::DATASOURCE_ID);
    }
}

$sample = new UpdateDataSourceSample();
$sample->callSample();
// [END merchantapi_update_data_source]