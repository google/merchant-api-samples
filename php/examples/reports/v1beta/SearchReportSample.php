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
// [START merchantapi_search_report]
use Google\Shopping\Merchant\Reports\V1beta\Client\ReportServiceClient;
use Google\Shopping\Merchant\Reports\V1beta\SearchRequest;

/**
 * This class demonstrates how to search reports for a given Merchant Center account.
 */
class SearchReportSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId
     *      The account that owns the report.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent($accountId)
    {
        return sprintf("accounts/%s", $accountId);
    }

    /**
     * Searches reports for a given Merchant Center account.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the account ID.
     * @throws \Google\ApiCore\ApiException
     */
    public static function searchReports($config)
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $reportServiceClient = new ReportServiceClient($options);

        // The parent has the format: accounts/{accountId}
        $parent = self::getParent($config['accountId']);

        // Change the query below. Documentation can be found at
        // https://developers.google.com/merchant/api/guides/reports/query-language
        $productPerformanceViewQuery =
            "SELECT offer_id, clicks, impressions"
            . " FROM product_performance_view"
            . " WHERE date BETWEEN '2025-03-03' AND '2025-03-10'";

        // $productPerformanceViewQuery = "SELECT offer_id, clicks FROM product_performance_view
        // WHERE clicks > 100 AND marketing_method = 'ADS' AND date DURING LAST_30_DAYS";

        // $bestSellersProductsViewQuery = "SELECT title, brand, category_l1 FROM
        // best_sellers_product_cluster_view WHERE date BETWEEN '2024-03-10' AND '2025-03-10'";

        // Create the search report request.
        $request = new SearchRequest(
            [
                'parent' => $parent,
                'query' => $productPerformanceViewQuery,
            ]
        );

        print "Sending search reports request.\n";
        // Calls the API and catches and prints any network failures/errors.
        try {
            $response = $reportServiceClient->search($request);
            print "Received search reports response: \n";
            // Iterates over all report rows in all pages and prints the report row in each row.
            // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
            foreach ($response->iterateAllElements() as $row) {
                print_r($row);
            }
        } catch (\Google\ApiCore\ApiException $e) {
            print "Failed to search reports.\n";
            print $e->getMessage() . "\n";
        }
    }

    /**
     * Helper to execute the sample.
     *
     * @return void
     * @throws \Google\ApiCore\ApiException
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::searchReports($config);
    }
}

// Run the script
$sample = new SearchReportSample();
$sample->callSample();
// [END merchantapi_search_report]
