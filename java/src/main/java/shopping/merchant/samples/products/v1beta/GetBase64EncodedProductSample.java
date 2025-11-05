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

// [START merchantapi_get_base64_encoded_product]
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

/**
 * This class demonstrates how to get a single product for a given Merchant Center account using
 * base64url encoded product IDs (v1beta).
 */
public class GetBase64EncodedProductSample {

  // Base64Url encoder/decoder without padding
  private static final BaseEncoding BASE64URL_NOPADDING = BaseEncoding.base64Url().omitPadding();

  // Encodes a string to base64url without padding
  public static String encodeProductId(String productId) {
    return BASE64URL_NOPADDING.encode(productId.getBytes(StandardCharsets.UTF_8));
  }

  public static void getProduct(Config config, String accountId, String rawProductId)
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

      // Encode the raw product ID using unpadded base64url encoding.
      String encodedProductId = encodeProductId(rawProductId);

      // The name has the format: accounts/{account}/products/{encodedProductId}
      String name = "accounts/" + accountId + "/products/" + encodedProductId;

      GetProductRequest request =
          GetProductRequest.newBuilder()
              .setName(name)
              // Indicate that the product ID in the name is base64url encoded.
              .setProductIdBase64UrlEncoded(true)
              .build();

      System.out.println("Sending get product request with base64url encoded ID:");
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

    // The raw, unencoded product ID for v1beta.
    // Format: channel~content_language~feed_label~offer_id
    // Example: online~en~US~sku123/special
    // This ID might contain characters like '/' which need encoding.
    String rawProductId = "online~en~US~sku123/special"; // Replace with your actual product ID

    getProduct(config, accountId, rawProductId);
  }
}
// [END merchantapi_get_base64_encoded_product]
