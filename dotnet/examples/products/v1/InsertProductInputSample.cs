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

// [START merchantapi_insert_product_input]
using Google.Apis.Auth.OAuth2;
using Google.Shopping.Merchant.Products.V1;
using Google.Shopping.Type;
using System;
using static MerchantApi.Authenticator;
using Price = Google.Shopping.Type.Price;

namespace MerchantApi
{

    public class InsertProductInputSample
    {
        /// Inserts a product input into a specified data source.
        public void InsertProductInput(string merchantId, string dataSource)
        {
            Console.WriteLine("=================================================================");
            Console.WriteLine("Inserting a ProductInput into a data source...");
            Console.WriteLine("=================================================================");

            // Authenticate using either oAuth or service account
            ICredential auth = Authenticator.Authenticate(
                MerchantConfig.Load(),
                ProductsServiceClient.DefaultScopes[0]);

            // Creates the ProductInputsServiceClient.
            var productInputsServiceClient = new ProductInputsServiceClientBuilder
            {
                Credential = auth
            }.Build();

            // The parent account.
            string parent = $"accounts/{merchantId}";

            // The price for shipping.
            Price price = new Price { AmountMicros = 33_450_000, CurrencyCode = "USD" };

            // Shipping information for Great Britain.
            Shipping shipping = new Shipping
            {
                Price = price,
                Country = "GB",
                Service = "1st class post"
            };

            // Shipping information for France.
            Shipping shipping2 = new Shipping
            {
                Price = price,
                Country = "FR",
                Service = "1st class post"
            };

            // The attributes of the product.
            Attributes attributes = new ProductAttributes
            {
                Title = "A Tale of Two Cities",
                Description = "A classic novel about the French Revolution",
                Link = "https://exampleWebsite.com/tale-of-two-cities.html",
                ImageLink = "https://exampleWebsite.com/tale-of-two-cities.jpg",
                Availability = "in stock",
                Condition = "new",
                GoogleProductCategory = "Media > Books",
                Gtin = { "9780007350896" },
                Shipping = { shipping, shipping2 }
            };

            // The product input to insert.
            ProductInput productInput = new ProductInput
            {
                ContentLanguage = "en",
                FeedLabel = "US",
                OfferId = "sku123",
                ProductAttributes = attributes
            };

            // Creates the request to insert the product input.
            // The data source can be a primary or supplemental data source.
            // You can only insert products into data sources of type "API" or "FILE".
            InsertProductInputRequest request = new InsertProductInputRequest
            {
                Parent = parent,
                DataSource = dataSource,
                ProductInput = productInput
            };

            try
            {
                Console.WriteLine("Sending insert ProductInput request...");
                ProductInput response = productInputsServiceClient.InsertProductInput(request);

                Console.WriteLine("Inserted ProductInput Name below:");
                // The last part of the product input name is the product ID assigned by Google.
                // Format: `contentLanguage~feedLabel~offerId`
                Console.WriteLine(response.Name);

                Console.WriteLine("Inserted Product Name below:");
                Console.WriteLine(response.Product);
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
            // The ID of the data source that will own the product input.
            // This field takes the `name` of the data source.
            // Replace {INSERT_DATASOURCE_ID} with your actual data source ID.
            string dataSourceId = "{INSERT_DATASOURCE_ID}";
            string dataSource = $"accounts/{merchantId}/dataSources/{dataSourceId}";

            var sample = new InsertProductInputSample();
            sample.InsertProductInput(merchantId, dataSource);
        }
    }
}
// [END merchantapi_insert_product_input]