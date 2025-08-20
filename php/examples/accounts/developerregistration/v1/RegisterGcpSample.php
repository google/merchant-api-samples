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
// [START merchantapi_register_gcp]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\DeveloperRegistrationServiceClient;
use Google\Shopping\Merchant\Accounts\V1\RegisterGcpRequest;

/**
 * This class demonstrates how to register the GCP project used to call the
 * Merchant API with a developer email.
 */
class RegisterGcpSample
{
    /**
     * A helper function to create the name string for the
     * DeveloperRegistration.
     *
     * @param string $accountId The merchant account ID.
     * @return string The name, which has the format:
     *     `accounts/{account}/developerRegistration`
     */
    private static function getName(string $accountId): string
    {
        return sprintf("accounts/%s/developerRegistration", $accountId);
    }

    /**
     * Registers the GCP project with a developer email.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $developerEmail The email of the developer to register.
     */
    public static function registerGcpSample(array $config, string $developerEmail): void
    {
        // Obtains OAuth credentials from the configuration file.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a configuration object for the client.
        $options = ['credentials' => $credentials];

        // Creates the DeveloperRegistrationServiceClient.
        $developerRegistrationServiceClient = new DeveloperRegistrationServiceClient($options);

        // Creates the name of the developer registration to identify it.
        $name = self::getName($config['accountId']);

        // Calls the API and handles any network failures.
        try {
            // Creates a request to register the GCP project with the developer email.
            $request = new RegisterGcpRequest([
                'name' => $name,
                'developer_email' => $developerEmail
            ]);

            printf("Sending RegisterGcp request:%s", PHP_EOL);
            // The `registerGcp` method returns a `DeveloperRegistration` object
            // upon success.
            $response = $developerRegistrationServiceClient->registerGcp($request);
            print "Successfully registered developer email '$developerEmail' for account {$config['accountId']}.\n";
            print_r($response);
        } catch (ApiException $e) {
            printf("An error occurred: %s%s", $e->getMessage(), PHP_EOL);
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // An email address for a developer to register for the API.
        $developerEmail = 'YOUR_EMAIL_HERE'; // Replace with your email
        self::registerGcpSample($config, $developerEmail);
    }
}

// Executes the sample.
$sample = new RegisterGcpSample();
$sample->callSample();
// [END merchantapi_register_gcp]
