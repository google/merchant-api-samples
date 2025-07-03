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

// [START merchantapi_list_products]
using System;
using static MerchantApi.Authenticator;
using Google.Api.Gax;
using Google.Apis.Auth.OAuth2;
using Google.Apis.Auth.OAuth2.Flows;
using Google.Apis.Auth.OAuth2.Responses;
using Google.Apis.Http;
using Newtonsoft.Json;
using Google.Shopping.Merchant.Products.V1Beta;


namespace MerchantApi
{
    public class ListProductsSample
    {
        public void ListProducts(string merchantId)
        {
            Console.WriteLine("=================================================================");
            Console.WriteLine("Listing all Products");
            Console.WriteLine("=================================================================");

            // Authenticate using either oAuth or service account
            ICredential auth = Authenticator.Authenticate(
                MerchantConfig.Load(),
                ProductsServiceClient.DefaultScopes[0]);

            // Create client
            ProductsServiceSettings productsServiceSettings = ProductsServiceSettings.GetDefault();

            // Create the ProductsServiceClient with the credentials
            ProductsServiceClientBuilder productsServiceClientBuilder = new ProductsServiceClientBuilder
            {
                Credential = auth
            };
            ProductsServiceClient client = productsServiceClientBuilder.Build();

            // Initialize the parent
            // The parent has the format: accounts/{account}
            AccountName parent = AccountName.FromAccount(merchantId);
            Console.WriteLine($"Listing products for account: {parent}");

            // Initialize request argument(s)
            ListProductsRequest request = new ListProductsRequest
            {
                Parent = parent.ToString(),
                PageSize = 1000 // Optional: specify the maximum number of products to return per page
            };

            // List all products in the account
            // Note: The API may return fewer than 1000 products
            PagedEnumerable<ListProductsResponse, Product> response = client.ListProducts(request);
            // Print the paginated results. This automatically handles pagination
            // and retrieves all products in the account.
            foreach (ListProductsResponse page in response.AsRawResponses())
            {
                Console.WriteLine("A page of results:");
                foreach (Product item in page)
                {
                    // Pretty print the products
                    Console.WriteLine(JsonConvert.SerializeObject(item, Formatting.Indented));

                }
            }
        }


        internal static void Main(string[] args)
        {
            var config = MerchantConfig.Load();
            string merchantId = config.MerchantId.Value.ToString();
            var sample = new ListProductsSample();
            sample.ListProducts(merchantId);
        }
    }
}
// [END merchantapi_list_products]