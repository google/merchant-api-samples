// Copyright 2025 Google LLC

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

package shopping.merchant.samples.products.v1beta;

// [START merchantapi_get_product]

import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.common.io.BaseEncoding;
import com.google.shopping.merchant.products.v1beta.GetProductRequest;
import com.google.shopping.merchant.products.v1beta.Product;
import com.google.shopping.merchant.products.v1beta.ProductsServiceClient;
import com.google.shopping.merchant.products.v1beta.ProductsServiceSettings;
import java.nio.charset.StandardCharsets;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get a single product for a given Merchant Center account */
public class GetProductSample {

  // Base64Url encoder/decoder without padding
  private static final BaseEncoding BASE64URL_NOPADDING = BaseEncoding.base64Url().omitPadding();

  // Encodes a string to base64url without padding
  public static String encodeProductId(String productId) {
    return BASE64URL_NOPADDING.encode(productId.getBytes(StandardCharsets.UTF_8));
  }

  public static void getProduct(Config config, String accountId, String productId)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    ProductsServiceSettings productsServiceSettings =
        ProductsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (ProductsServiceClient productsServiceClient =
        ProductsServiceClient.create(productsServiceSettings)) {

      // The name has the format: accounts/{account}/products/{productId}
      String name = "accounts/" + accountId + "/products/" + productId;

      // The name has the format: accounts/{account}/products/{productId}
      GetProductRequest request = GetProductRequest.newBuilder().setName(name).build();

      System.out.println("Sending get product request:");
      Product response = productsServiceClient.getProduct(request);

      System.out.println("Retrieved Product below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    String accountId = config.getAccountId().toString();

    // The name of the `product`, returned after a `Product.insert` request. We recommend
    // having stored this value in your database to use for all future requests.
    String productId = "en~US~sku123"; // Replace with your actual product ID

    // Uncomment the following line if the product name contains special characters (such as forward
    // slashes) and needs base64url encoding.
    // productId = encodeProductId(productId);

    getProduct(config, accountId, productId);
  }
}
// [END merchantapi_get_product]
