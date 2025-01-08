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
use Google\Shopping\Merchant\DataSources\V1beta\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1beta\DataSource;
use Google\Shopping\Merchant\DataSources\V1beta\PromotionDataSource;

/**
 * This class demonstrates how to create a promotion datasource.
 */

class CreatePromotionDataSource
{
    // [START CreatePromotionDataSource]
    /**
     * Creates a new PromotionDataSource.
     *
     * @param int    $merchantId The Merchant Center account ID.
     * @param string $displayName The displayed datasource name in the Merchant Center UI.
     * @return string The name of the newly created PromotionDataSource.
     */
    function createPromotionDataSourceSample(int $merchantId, string $displayName): string
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $dataSourcesServiceClient = new DataSourcesServiceClient($options);

        $parent = sprintf('accounts/%s', $merchantId);

        // Creates the data source.
        $dataSource = (new DataSource())
            ->setDisplayName($displayName)
            ->setPromotionDataSource(
                (new PromotionDataSource())
                    ->setContentLanguage('en')
                    ->setTargetCountry('GB')
            );

        // Creates the request.
        $request = (new CreateDataSourceRequest())
            ->setParent($parent)
            ->setDataSource($dataSource);

        // Sends the request to the API.
        try {
            print('Sending Create Promotion DataSource request' . PHP_EOL);
            $response = $dataSourcesServiceClient->createDataSource($request);
            print('Inserted DataSource Name below' . PHP_EOL);
            print($response->getName() . PHP_EOL);
            return $response->getName();
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
            return '';
        }
    }
    // [END CreatePromotionDataSource]

    // Helper to execute the sample.
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The Merchant Center Account ID.
        $merchantId = $config['accountId'];

        // The displayed datasource name in the Merchant Center UI.
        $displayName = 'British Promotions';

        $this->createPromotionDataSourceSample($merchantId, $displayName);
    }
}

$sample = new CreatePromotionDataSource();
$sample->callSample();
