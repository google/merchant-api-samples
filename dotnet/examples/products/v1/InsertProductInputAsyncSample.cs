// [START merchantapi_insert_product_input_async]
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Google.Apis.Auth.OAuth2;
using Google.Shopping.Merchant.Products.V1;
using Google.Shopping.Type;
using MerchantApi;

namespace Shopping.Merchant.Samples.Products.V1
{
    /// <summary>
    /// This class demonstrates how to insert a product input asynchronously.
    /// </summary>
    public class InsertProductInputAsyncSample
    {
        private static string GenerateRandomString()
        {
            const string characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var random = new Random();
            return new string(Enumerable.Repeat(characters, 8)
                .Select(s => s[random.Next(s.Length)]).ToArray());
        }

        private static ProductInput CreateRandomProduct()
        {
            Price price = new Price { AmountMicros = 33_450_000, CurrencyCode = "USD" };

            Shipping shipping = new Shipping
            {
                Price = price,
                Country = "GB",
                Service = "1st class post"
            };

            Shipping shipping2 = new Shipping
            {
                Price = price,
                Country = "FR",
                Service = "1st class post"
            };

            ProductAttributes attributes = new ProductAttributes
            {
                Title = "A Tale of Two Cities",
                Description = "A classic novel about the French Revolution",
                Link = "https://exampleWebsite.com/tale-of-two-cities.html",
                ImageLink = "https://exampleWebsite.com/tale-of-two-cities.jpg",
                Availability = Availability.InStock,
                Condition = Condition.New,
                GoogleProductCategory = "Media > Books",
                Gtins = { "9780007350896" },
                Shipping = { shipping, shipping2 }
            };

            return new ProductInput
            {
                ContentLanguage = "en",
                FeedLabel = "CH",
                OfferId = GenerateRandomString(),
                ProductAttributes = attributes
            };
        }

        internal static async Task AsyncInsertProductInput(MerchantConfig config, string dataSource)
        {
            // Obtains OAuth token based on the user's configuration.
            ICredential auth = Authenticator.Authenticate(config, ProductInputsServiceClient.DefaultScopes[0]);

            // Creates a client pool to enhance throughput for bulk operations.
            // .NET does not have native channel pooling like Java's InstantiatingGrpcChannelProvider,
            // so we implement the Client Pool pattern by creating multiple client instances.
            // Each individual client manages its own connection pathway.
            // We recommend estimating the number of concurrent requests you'll make, divide by 50 (50%
            // utilization of channel capacity), and set the pool size to that number.
            int poolSize = 30;
            var clientPool = new List<ProductInputsServiceClient>(poolSize);
            for (int i = 0; i < poolSize; i++)
            {
                clientPool.Add(new ProductInputsServiceClientBuilder
                {
                    Credential = auth
                }.Build());
            }

            // Creates parent to identify where to insert the product.
            string parent = $"accounts/{config.MerchantId.Value}";

            // Creates five insert product input requests with random product IDs.
            var requests = new List<InsertProductInputRequest>();
            for (int i = 0; i < 5; i++)
            {
                requests.Add(new InsertProductInputRequest
                {
                    Parent = parent,
                    // You can only insert products into datasource types of Input "API", and of Type
                    // "Primary" or "Supplemental."
                    // This field takes the `name` field of the datasource.
                    DataSource = dataSource,
                    // If this product is already owned by another datasource, when re-inserting, the
                    // new datasource will take ownership of the product.
                    ProductInput = CreateRandomProduct()
                });
            }

            Console.WriteLine("Sending insert product input requests");

            // Distribute requests across the clients in the pool.
            var tasks = new List<Task<ProductInput>>();
            for (int i = 0; i < requests.Count; i++)
            {
                var client = clientPool[i % poolSize];
                tasks.Add(client.InsertProductInputAsync(requests[i]));
            }

            // Awaits all tasks and catches/prints any network failures/errors.
            try            {
                var results = await Task.WhenAll(tasks);
                Console.WriteLine("Inserted products below");
                Console.WriteLine("[" + string.Join(", ", results.Select(r => r.ToString())) + "]");
            }
            catch (Exception e)
            {
                Console.WriteLine(e);
            }
        }

        public static void Main(string[] args)
        {
            MerchantConfig config = MerchantConfig.Load();
            // Identifies the data source that will own the product input.
            string dataSource = $"accounts/{config.MerchantId.Value}/dataSources/12345";

            AsyncInsertProductInput(config, dataSource).GetAwaiter().GetResult();
        }
    }
}
// [END merchantapi_insert_product_input_async]
