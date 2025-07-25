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

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_filter_disapproved_products]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Products\V1beta\Client\ProductsServiceClient;
use Google\Shopping\Merchant\Products\V1beta\GetProductRequest;
use Google\Shopping\Merchant\Reports\V1beta\Client\ReportServiceClient;
use Google\Shopping\Merchant\Reports\V1beta\SearchRequest;

/**
 * This class demonstrates how to get the list of all the disapproved products for a given merchant
 * center account.
 */
class FilterDisapprovedProducts
{
    /**
     * Gets the product details for a given product using the GetProduct method.
     *
     * @param mixed $credentials The OAuth credentials.
     * @param array $config The configuration data, including 'accountId'.
     * @param string $productName The full resource name of the product to retrieve.
     *      Format: accounts/{account}/products/{product}
     */
    private static function getProduct(
        $credentials,
        array $config,
        string $productName
    ): void {
        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a ProductsServiceClient.
        $productsServiceClient = new ProductsServiceClient($options);

        // Calls the API and catches and prints any network failures/errors.
        try {
            // Construct the GetProductRequest.
            // The name has the format: accounts/{account}/products/{productId}
            $request = new GetProductRequest(['name' => $productName]);

            // Make the API call.
            $response = $productsServiceClient->getProduct($request);

            // Prints the retrieved product.
            // Protobuf messages are printed as JSON strings for readability.
            print $response->serializeToJsonString() . "\n";
        } catch (ApiException $e) {
            // Prints any errors that occur during the API call.
            printf("ApiException was thrown: %s\n", $e->getMessage());
        }
    }

    /**
     * Filters disapproved products for a given Merchant Center account using the Reporting API,
     * then prints the details for each disapproved product.
     *
     * @param array $config The configuration data used for authentication and
     *      getting the account ID.
     */
    public static function filterDisapprovedProductsSample(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the Report client to use.
        $reportClientOptions = ['credentials' => $credentials];

        // Creates a ReportServiceClient.
        $reportServiceClient = new ReportServiceClient($reportClientOptions);

        // The parent resource name for the report.
        // Format: accounts/{accountId}
        $parent = sprintf("accounts/%s", $config['accountId']);

        // The query to select disapproved products from the product_view.
        // This query retrieves offer_id, id, title, and price for products
        // that are 'NOT_ELIGIBLE_OR_DISAPPROVED'.
        $query = "SELECT offer_id, id, title, price FROM product_view"
            . " WHERE aggregated_reporting_context_status = 'NOT_ELIGIBLE_OR_DISAPPROVED'";

        // Create the search report request.
        $request = new SearchRequest([
            'parent' => $parent,
            'query' => $query
        ]);

        print "Sending search report request for Product View.\n";
        // Calls the Reports.search API method.
        try {
            $response = $reportServiceClient->search($request);
            print "Received search reports response: \n";

            // Iterates over all report rows in all pages.
            // The client library automatically handles pagination.
            foreach ($response->iterateAllElements() as $row) {
                print "Printing data from Product View:\n";
                // Prints the ReportRow object as a JSON string.
                print $row->serializeToJsonString() . "\n";

                // Get the full product resource name from the report row.
                // The `id` field in ProductView is the product's full resource name.
                // Format: `accounts/{account}/products/{product}`
                $productName = $parent . "/products/" . $row->getProductView()->getId();
                // OPTIONAL: Call getProduct to retrieve and print full product details.
                // Pass the original credentials and config.
                print "Getting full product details by calling GetProduct method:\n";
                self::getProduct($credentials, $config, $productName);
            }
        } catch (ApiException $e) {
            // Prints any errors that occur during the API call.
            printf("ApiException was thrown: %s\n", $e->getMessage());
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::filterDisapprovedProductsSample($config);
    }
}

// Run the script
$sample = new FilterDisapprovedProducts();
$sample->callSample();
// [END merchantapi_filter_disapproved_products]
