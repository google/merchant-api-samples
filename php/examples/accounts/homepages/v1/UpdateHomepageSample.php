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
// [START merchantapi_update_homepage]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1\Homepage;
use Google\Shopping\Merchant\Accounts\V1\Client\HomepageServiceClient;
use Google\Shopping\Merchant\Accounts\V1\UpdateHomepageRequest;

/**
 * This class demonstrates how to update a homepage to a new URL.
 */
class UpdateHomepage
{
    /**
     * Updates a homepage to a new URL.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $uri The new URI for the homepage.
     * @return void
     * @throws ApiException if the API call fails.
     */
    public static function updateHomepageSample(array $config, string $uri): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $homepageServiceClient = new HomepageServiceClient($options);

        // Creates Homepage name to identify Homepage.
        // The name has the format: accounts/{account}/homepage
        $name = "accounts/" . $config['accountId'] . "/homepage";

        // Create a homepage with the updated fields.
        $homepage = new Homepage(['name' => $name, 'uri' => $uri]);

        // Create field mask to specify which fields to update.
        $fieldMask = new FieldMask(['paths' => ['uri']]);

        try {
            $request = new UpdateHomepageRequest([
                'homepage' => $homepage,
                'update_mask' => $fieldMask
            ]);

            print "Sending Update Homepage request\n";
            $response = $homepageServiceClient->updateHomepage($request);
            print "Updated Homepage Name below\n";
            print $response->getName() . "\n";
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

        // The URI (a URL) of the store's homepage.
        $uri = "https://example.com";

        // Makes the call to update the homepage.
        self::updateHomepageSample($config, $uri);
    }
}

// Run the script
$sample = new UpdateHomepage();
$sample->callSample();
// [END merchantapi_update_homepage]
