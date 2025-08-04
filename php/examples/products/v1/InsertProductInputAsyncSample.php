<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
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
// [START merchantapi_insert_product_input_async]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Products\V1\ProductAttributes;
use Google\Shopping\Merchant\Products\V1\InsertProductInputRequest;
use Google\Shopping\Merchant\Products\V1\ProductInput;
use Google\Shopping\Merchant\Products\V1\Client\ProductInputsServiceClient;
use Google\Shopping\Merchant\Products\V1\Shipping;
use Google\Shopping\Type\Price;
use React\EventLoop\Loop;
use React\Promise\Promise;
use function React\Promise\all;

/**
 * This class demonstrates how to insert multiple product inputs asynchronously.
 */
class InsertProductInputAsyncSample
{
    /**
     * A helper function to create the parent string for product input operations.
     *
     * @param string $accountId The Merchant Center account ID.
     * @return string The parent resource name format: `accounts/{account_id}`.
     */
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }

    /**
     * Generates a random string of 8 characters.
     *
     * @return string A random alphanumeric string.
     */
    private static function generateRandomString(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Creates a ProductInput object with randomized offer ID and sample attributes.
     *
     * @return ProductInput A new ProductInput object.
     */
    private static function createRandomProduct(): ProductInput
    {
        // Create a price object for shipping. Amount is in micros.
        // e.g., 33,450,000 micros = $33.45 USD
        $price = new Price([
            'amount_micros' => 33450000,
            'currency_code' => 'USD'
        ]);

        // Create shipping details.
        $shipping = new Shipping([
            'price' => $price,
            'country' => 'GB',
            'service' => '1st class post'
        ]);

        $shipping2 = new Shipping([
            'price' => $price,
            'country' => 'FR',
            'service' => '1st class post'
        ]);

        // Create product attributes.
        $attributes = new ProductAttributes([
            'title' => 'A Tale of Two Cities',
            'description' => 'A classic novel about the French Revolution',
            'link' => 'https://exampleWebsite.com/tale-of-two-cities.html',
            'image_link' => 'https://exampleWebsite.com/tale-of-two-cities.jpg',
            'availability' => 'in stock',
            'condition' => 'new',
            'google_product_category' => 'Media > Books',
            'gtins' => ['9780007350896'],
            'shipping' => [$shipping, $shipping2]
        ]);

        // Create the product input object.
        return new ProductInput([
            'content_language' => 'en',
            'feed_label' => 'LABEL',
            'offer_id' => self::generateRandomString(), // Random offer ID for uniqueness
            'product_attributes' => $attributes
        ]);
    }

    /**
     * Inserts multiple product inputs into the specified account and data source asynchronously.
     *
     * @param array $config Authentication and account configuration.
     * @param string $dataSource The target data source name.
     * Format: `accounts/{account}/dataSources/{datasource}`.
     * @return void
     */
    public static function insertProductInputAsyncSample(array $config, string $dataSource): void
    {
        // Fetches OAuth2 credentials for making API calls.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Prepares client options with the fetched credentials.
        $options = ['credentials' => $credentials];

        // Initializes the ProductInputsServiceAsyncClient.
        // This is the key for asynchronous operations.
        $productInputsServiceAsyncClient = new ProductInputsServiceClient($options);

        // Constructs the parent resource string.
        $parent = self::getParent($config['accountId']);

        $promises = [];
        $insertedProductInputs = [];

        print "Sending insert product input requests asynchronously...\n";

        // Create and send 5 insert product input requests asynchronously.
        for ($i = 0; $i < 5; $i++) {
            $productInput = self::createRandomProduct();
            // Create the request object.
            $request = new InsertProductInputRequest([
                'parent' => $parent,
                'data_source' => $dataSource,
                'product_input' => $productInput
            ]);

            // Make the asynchronous API call. This returns a Promise.
            $promise = $productInputsServiceAsyncClient->insertProductInputAsync($request);

            // Attach success and error handlers to the promise.
            $promise->then(
                function (ProductInput $response) use (&$insertedProductInputs) {
                    // This callback is executed when the promise resolves (success).
                    $insertedProductInputs[] = $response;
                    print "Successfully inserted product with offer ID: " . $response->getOfferId() . "\n";
                },
                function (ApiException $e) {
                    // This callback is executed if the promise rejects (failure).
                    echo "ApiException occurred for one of the requests:\n";
                    echo $e;
                }
            );
            $promises[] = $promise;
        }

        // Wait for all promises to settle (either resolve or reject).
        // Reduce::all() creates a single promise that resolves when all input promises resolve.
        // If any promise rejects, the combined promise will reject.
        all($promises)->then(
            function () use (&$insertedProductInputs) {
                print "All asynchronous requests have completed.\n";
                // Print details of all successfully inserted products.
                print "Inserted products below\n";
                foreach ($insertedProductInputs as $p) {
                    print_r($p);
                }
            },
            function ($reason) {
                // This block is executed if any promise in the array rejects.
                echo "One or more asynchronous requests failed.\n";
                if ($reason instanceof ApiException) {
                    echo "API Exception: " . $reason->getMessage() . "\n";
                } else {
                    echo "Error: " . $reason . "\n";
                }
            }
        )->always(function () use ($productInputsServiceAsyncClient) {
            // This 'always' callback ensures the client is closed after all promises settle.
            $productInputsServiceAsyncClient->close();
        });

        // Run the event loop. This is crucial for asynchronous operations to execute.
        // The script will block here until all promises are resolved/rejected or the loop is stopped.
        Loop::run();
    }

    /**
     * Executes the sample code to insert multiple product inputs.
     *
     * @return void
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();

        // Define the data source that will own the product inputs.
        // IMPORTANT: Replace `<DATA_SOURCE_ID>` with your actual data source ID.
        $dataSourceId = '<DATA_SOURCE_ID>';
        $dataSourceName = sprintf(
            "accounts/%s/dataSources/%s",
            $config['accountId'], $dataSourceId
        );

        // Call the method to insert multiple product inputs asynchronously.
        self::insertProductInputAsyncSample($config, $dataSourceName);
    }
}

$sample = new InsertProductInputAsyncSample();
$sample->callSample();
// [END merchantapi_insert_product_input_async]
