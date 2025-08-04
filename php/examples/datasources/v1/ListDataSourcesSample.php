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
// [START merchantapi_list_data_sources]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\DataSources\V1\DataSource;
use Google\Shopping\Merchant\DataSources\V1\ListDataSourcesRequest;

/**
 * Class to demonstrate listing all the datasources for a given Merchant Center
 * account.
 */
class ListDataSourcesSample
{
    /**
     * Lists all DataSources for the given Merchant Center account.
     *
     * @param int $merchantId The Merchant Center Account ID.
     * @return array An array of DataSources.
     */
    public function listDataSources(int $merchantId): array
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        $parent = sprintf('accounts/%s', $merchantId);

        // Creates the request.
        $request = (new ListDataSourcesRequest())
            ->setParent($parent);

        print('Sending list datasources request:' . PHP_EOL);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $dataSourcesServiceClient->listDataSources($request);

            $dataSources = [];
            $justPrimaryDataSources = [];

            /** @var DataSource $element */
            foreach ($response as $element) {
                print($element->serializeToJsonString() . PHP_EOL);
                $dataSources[] = $element;
                // The below lines show how to filter datasources based on type.
                // `element.hasSupplementalProductDataSource()` would give you supplemental
                // datasources, etc.
                if ($element->hasPrimaryProductDataSource()) {
                    $justPrimaryDataSources[] = $element;
                }
            }
            print('The following count of datasources were returned: ' . count($dataSources) . PHP_EOL);
            print('... of which are primary datasources: ' . count($justPrimaryDataSources) . PHP_EOL);
            return $dataSources;
        } catch (ApiException $ex) {
            print('Call failed with message: ' . $ex->getMessage() . PHP_EOL);
            return [];
        }
    }

    // Helper to execute the sample.
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        self::listDataSources($merchantId);
    }
}

$sample = new ListDataSourcesSample();
$sample->callSample();
// [END merchantapi_list_data_sources]