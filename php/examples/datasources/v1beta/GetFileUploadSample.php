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
// [START merchantapi_get_file_upload]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\DataSources\V1beta\Client\FileUploadsServiceClient;
use Google\Shopping\Merchant\DataSources\V1beta\GetFileUploadRequest;

/**
 * This class demonstrates how to get the latest data source file upload.
 */
class GetFileUploadSample
{
    /**
     * Builds the full resource name for a file upload.
     *
     * @param string $accountId The Merchant Center account ID.
     * @param string $datasourceId The ID of the data source.
     * @return string The file upload name in the format:
     *      `accounts/{account}/datasources/{datasource}/fileUploads/latest`
     */
    private static function buildFileUploadName(string $accountId, string $datasourceId): string
    {
        // For this sample, we are always fetching the "latest" file upload.
        return sprintf(
            "accounts/%s/dataSources/%s/fileUploads/latest",
            $accountId,
            $datasourceId
        );
    }

    /**
     * Retrieves the latest file upload information for a specific data source.
     *
     * @param array $config Configuration array containing the Merchant Center account ID.
     * @param string $datasourceId The ID of the data source for which to retrieve
     *     the file upload.
     * @return void
     */
    public static function getFileUploadSample(array $config, string $datasourceId): void
    {
        // Obtains OAuth credentials from the service account or token file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Configures the client options with the credentials.
        $options = ['credentials' => $credentials];

        // Creates a new FileUploadsServiceClient.
        $fileUploadsServiceClient = new FileUploadsServiceClient($options);

        // Constructs the full resource name for the file upload.
        // The name identifies the specific file upload to retrieve ("latest").
        $name = self::buildFileUploadName(
            $config['accountId'],
            $datasourceId
        );

        // Creates the GetFileUploadRequest.
        $request = new GetFileUploadRequest(['name' => $name]);

        // Calls the API to get the file upload information.
        try {
            print "Sending get FileUpload request:\n";
            $response = $fileUploadsServiceClient->getFileUpload($request);

            print "Retrieved FileUpload below\n";
            // Prints the retrieved FileUpload object as a JSON string.
            // This provides a comprehensive view of the response data.
            print $response->serializeToJsonString() . "\n";
        } catch (ApiException $e) {
            // Catches and prints any API errors.
            printf("ApiException was thrown: %s\n", $e->getMessage());
        }
    }

    /**
     * Helper function to execute the sample.
     *
     * @return void
     */
    public function callSample(): void
    {
        // Generates configuration details, including the account ID.
        $config = Config::generateConfig();

        // The ID of the data source, assigned by Google.
        // Replace with a valid datasource ID.
        $datasourceId = "<INSERT_DATA_SOURCE_ID>";

        // Calls the main sample function with the configuration and datasource ID.
        self::getFileUploadSample($config, $datasourceId);
    }
}

$sample = new GetFileUploadSample();
$sample->callSample();
// [END merchantapi_get_file_upload]