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
// [START merchantapi_enable_program]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1beta\Client\ProgramsServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\EnableProgramRequest;

/**
 * This class demonstrates how to enable a shopping program for a Merchant Center account.
 */
class EnableProgramSample
{
    /**
     * Enables a program for the given Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $program The program to enable.
     * @return void
     */
    public static function enableProgram($config, $program): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $programsServiceClient = new ProgramsServiceClient($options);

        // Creates program name to identify the program.
        $name = $parent = "accounts/" . $config['accountId'] . "/programs/" . $program;


        // Calls the API and catches and prints any network failures/errors.
        try {
            // The name has the format: accounts/{account}/programs/{program}
            $request = new EnableProgramRequest(['name' => $name]);

            print "Sending Enable Program request:\n";
            $response = $programsServiceClient->enableProgram($request);

            print "Enabled Program below\n";
            print_r($response);
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

        // Replace this with the name of the program to be enabled.
        $program = "free-listings";
        self::enableProgram($config, $program);
    }
}

// Run the script
$sample = new EnableProgramSample();
$sample->callSample();
// [END merchantapi_enable_program]
