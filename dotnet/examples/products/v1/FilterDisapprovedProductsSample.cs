// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.


// [START merchantapi_filter_disapproved_products]
using System;
using Google.Api.Gax;
using Google.Apis.Auth.OAuth2;
using Google.Shopping.Merchant.Products.V1;
using Google.Shopping.Merchant.Reports.V1;
using Newtonsoft.Json;
using static MerchantApi.Authenticator;

namespace MerchantApi
{
    public class FilterDisapprovedProductsSample
    {
        // Filters disapproved products using the Reporting API and then fetches
        // full product details for each.
        public void FilterDisapprovedProducts(string merchantId)
        {
            Console.WriteLine("=================================================================");
            Console.WriteLine("Filtering for Disapproved Products");
            Console.WriteLine("=================================================================");

            // Authenticate using either oAuth or service account
            ICredential auth = Authenticator.Authenticate(
                MerchantConfig.Load(),
                ProductsServiceClient.DefaultScopes[0]);

            // Create a client for the Reports API.
            ReportServiceClient reportClient = new ReportServiceClientBuilder
            {
                Credential = auth
            }.Build();

            // Create a client for the Products API.
            ProductsServiceClient productsClient = new ProductsServiceClientBuilder
            {
                Credential = auth
            }.Build();

            // The parent has the format: accounts/{accountId}
            string parent = $"accounts/{merchantId}";

            // The query to select disapproved products from the product_view.
            string query =
                "SELECT offer_id, id, title, price " +
                "FROM product_view " +
                "WHERE aggregated_reporting_context_status = 'NOT_ELIGIBLE_OR_DISAPPROVED'";

            // Create the search request for the Reports API.
            SearchRequest request = new SearchRequest
            {
                Parent = parent,
                Query = query,
                PageSize = 1000 // Optional: specify the page size.
            };

            try
            {
                Console.WriteLine("Sending search report request for disapproved products...");
                // Call the Reports.Search API method.
                PagedEnumerable<SearchResponse, ReportRow> response = reportClient.Search(request);
                Console.WriteLine("Received search reports response.");

                // Iterate over all report rows. This handles pagination automatically.
                foreach (ReportRow row in response)
                {
                    Console.WriteLine("-------------------------------------------------");
                    Console.WriteLine("Found disapproved product from Product View:");
                    Console.WriteLine(JsonConvert.SerializeObject(row.ProductView, Formatting.Indented));

                    // Construct the full product name for the GetProduct request.
                    // Format: accounts/{account}/products/{product}
                    string productName = ProductName.FromAccountProduct(merchantId, row.ProductView.Id).ToString();

                    Console.WriteLine("\nFetching full product details with GetProduct method...");

                    // Get the full product details by calling the GetProduct method.
                    GetProductRequest getRequest = new GetProductRequest { Name = productName };
                    Product product = productsClient.GetProduct(getRequest);

                    Console.WriteLine(JsonConvert.SerializeObject(product, Formatting.Indented));
                }
                Console.WriteLine("-------------------------------------------------");
            }
            catch (Exception e)
            {
                Console.WriteLine($"Failed to search reports for disapproved products: {e.Message}");
            }
        }

        public static void Main(string[] args)
        {
            // Load configuration settings.
            var config = MerchantConfig.Load();
            string merchantId = config.MerchantId.Value.ToString();

            if (string.IsNullOrEmpty(merchantId))
            {
                Console.WriteLine("MerchantId is not set in the MerchantConfig.json file.");
                return;
            }

            // Create an instance of the sample and run it.
            var sample = new FilterDisapprovedProductsSample();
            sample.FilterDisapprovedProducts(merchantId);
        }
    }
}
// [END merchantapi_filter_disapproved_products]