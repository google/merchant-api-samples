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

// [START merchantapi_delete_product_input]
using Google.Apis.Auth.OAuth2;
using Google.Shopping.Merchant.Products.V1;
using System;
using static MerchantApi.Authenticator;

namespace MerchantApi
{
    /// <summary>
    /// This class demonstrates how to delete a product input for a given Merchant Center account.
    /// </summary>
    public class DeleteProductInputSample
    {
        // Deletes a product input from a specified data source.
        public void DeleteProductInput(string merchantId, string productId, string dataSource)
        {
            Console.WriteLine("=================================================================");
            Console.WriteLine("Deleting a ProductInput from a data source...");
            Console.WriteLine("=================================================================");

            // Authenticate using either OAuth or a service account.
            ICredential auth = Authenticator.Authenticate(
                MerchantConfig.Load(),
                ProductsServiceClient.DefaultScopes[0]);

            // Create the ProductInputsServiceClient.
            var productInputsServiceClient = new ProductInputsServiceClientBuilder
            {
                Credential = auth
            }.Build();

            // Create the product name to identify the product.
            string name = ProductInputName.FromAccountProductinput(merchantId, productId).ToString();

            // Create the request to delete the product input.
            DeleteProductInputRequest request = new DeleteProductInputRequest
            {
                Name = name,
                DataSource = dataSource
            };

            try
            {
                Console.WriteLine("Sending deleteProductInput request...");
                // The call returns no response on success.
                productInputsServiceClient.DeleteProductInput(request);
                Console.WriteLine(
                    "Delete successful, note that it may take a few minutes for the delete to update in the system. " +
                    "If you make a products.get or products.list request before a few minutes have passed, " +
                    "the old product data may be returned.");
            }
            catch (Exception e)
            {
                Console.WriteLine(e.Message);
            }
        }

        public static void Main(string[] args)
        {
            MerchantConfig config = MerchantConfig.Load();
            string merchantId = config.MerchantId.Value.ToString();
            // An ID assigned to a product by Google. In the format:
            // channel~contentLanguage~feedLabel~offerId
            string productId = "online~en~US~sku123";

            // The name of the dataSource from which to delete the product.
            // If it is a primary feed, this will delete the product completely.
            // If it's a supplemental feed, it will only delete the product information
            // from that feed, but the product will still be available from the primary feed.
            // Replace {INSERT_DATASOURCE_ID} with your actual data source ID.
            string dataSourceId = "{INSERT_DATASOURCE_ID}";
            string dataSource = $"accounts/{config.MerchantId}/dataSources/{dataSourceId}";

            var sample = new DeleteProductInputSample();
            sample.DeleteProductInput(merchantId, productId, dataSource);
        }
    }
}
// [END merchantapi_delete_product_input]