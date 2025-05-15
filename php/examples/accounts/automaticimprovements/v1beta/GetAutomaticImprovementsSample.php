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

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

// [START merchantapi_get_automatic_improvements]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\AutomaticImprovementsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\GetAutomaticImprovementsRequest;

/**
 * This class demonstrates how to get the automatic improvements of a Merchant Center account.
 */
class GetAutomaticImprovementsSample
{
    /**
     * Helper function to construct the resource name for AutomaticImprovements.
     *
     * @param string $accountId The Merchant Center account ID.
     * @return string The resource name in the format: accounts/{account}/automaticImprovements
     */
    private static function getAutomaticImprovementsName(string $accountId): string
    {
        return sprintf("accounts/%s/automaticImprovements", $accountId);
    }

    /**
     * Retrieves the automatic improvements settings for a given Merchant Center account.
     *
     * @param array $config The configuration array containing the account ID.
     * @return void
     */
    public static function getAutomaticImprovementsSample(array $config): void
    {
        // Obtains OAuth credentials for authentication.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Contructs an options array for the client.
        $options = ['credentials' => $credentials];

        // Creates a new AutomaticImprovementsServiceClient.
        $automaticImprovementsServiceClient = new AutomaticImprovementsServiceClient($options);

        // Constructs the full resource name for the automatic improvements settings.
        $name = self::getAutomaticImprovementsName($config['accountId']);

        // Creates the GetAutomaticImprovementsRequest.
        $request = new GetAutomaticImprovementsRequest(['name' => $name]);

        printf("Sending get AutomaticImprovements request:%s", PHP_EOL);

        try {
            // Makes the API call to retrieve automatic improvements settings.
            $response = $automaticImprovementsServiceClient->getAutomaticImprovements($request);

            printf("Retrieved AutomaticImprovements below%s", PHP_EOL);
            // Prints the response in JSON format for readability.
            print_r($response);
        } catch (ApiException $e) {
            printf("ApiException was thrown: %s%s", $e->getMessage(), PHP_EOL);
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
        self::getAutomaticImprovementsSample($config);
    }
}

// Runs the script.
$sample = new GetAutomaticImprovementsSample();
$sample->callSample();
// [END merchantapi_get_automatic_improvements]

