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

// [START merchantapi_get_product]
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Products\V1\Client\ProductsServiceClient;
use Google\Shopping\Merchant\Products\V1\GetProductRequest;

/**
 * This class demonstrates how to get a single product for a given Merchant
 * Center account.
 */
class GetProductSample
{
    /**
     * A helper function to create the product name string.
     *
     * @param string $accountId
     *      The account that owns the product.
     * @param string $productId
     *      The ID of the product.
     *
     * @return string The name has the format: `accounts/{account}/products/{product}`
     */
    private static function getName(string $accountId, string $productId): string
    {
        return sprintf("accounts/%s/products/%s", $accountId, $productId);
    }

    /**
     * Encodes a string to base64url without padding. This is needed if the
     * product ID contains special characters (such as forward slashes) and
     * needs base64url encoding.
     *
     * @param string $productId
     *      The ID of the product.
     *
     * @return string The encoded product ID.
     */
    public static function encodeProductId(string $productId): string
    {
        return rtrim(strtr(base64_encode($productId), '+/', '-_'), '=');
    }

    /**
     * Retrieves a product from your Merchant Center account.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the
     *      account ID.
     * @param string $productId
     *      The ID of the product, in the form of
     *      `contentLanguage:feedLabel:offerId`.
     *
     * @return void
     */
    public static function getProduct(array $config, string $productId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $productsServiceClient = new ProductsServiceClient($options);

        // The name has the format: accounts/{account}/products/{productId}
        $name = self::getName($config['accountId'], $productId);

        // Creates the request.
        $request = new GetProductRequest([
            'name' => $name
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            print "Sending get product request:\n";
            $response = $productsServiceClient->getProduct($request);
            print "Retrieved Product below\n";
            // Pretty-prints the JSON representation of the response.
            print $response->serializeToJsonString(true) . "\n";
        } catch (ApiException $e) {
            printf("Call failed with message: %s\n", $e->getMessage());
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

        // The name of the `product`, returned after a `Product.insert` request.
        // We recommend having stored this value in your database to use for all
        // future requests.
        $productId = 'en~US~sku123'; // Replace with your actual product ID

        // Uncomment the following line if the product name contains special
        // characters (such as forward slashes) and needs base64url encoding.
        // $productId = self::encodeProductId($productId);

        self::getProduct($config, $productId);
    }
}

// Runs the sample.
$sample = new GetProductSample();
$sample->callSample();
// [END merchantapi_get_product]
```