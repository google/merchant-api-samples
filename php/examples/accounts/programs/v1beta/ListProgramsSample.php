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
// [START merchantapi_list_programs]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\ProgramsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\ListProgramsRequest;

/**
 * This class demonstrates how to list all shopping program resources for a Merchant Center account.
 */
class ListProgramsSample
{
    /**
     * Lists all programs for the given Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @return void
     */
    public static function listPrograms($config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $programsServiceClient = new ProgramsServiceClient($options);

        // Creates parent to identify the account for which to list programs.
        $parent = "accounts/" . $config['accountId'];

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = new ListProgramsRequest(['parent' => $parent]);

            print "Sending List Programs request:\n";
            $response = $programsServiceClient->listPrograms($request);

            $count = 0;

            // Iterates over all programs in all pages and prints each program.
            // Automatically uses the `nextPageToken`, if returned, to fetch all pages.
            foreach ($response->iterateAllElements() as $program) {
                print_r($program);
                $count++;
            }
            print "The count of Programs returned: ";
            print $count . "\n";
        } catch (ApiException $e) {
            print $e->getMessage();
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
        self::listPrograms($config);
    }
}

// Run the script
$sample = new ListProgramsSample();
$sample->callSample();
// [END merchantapi_list_programs]
