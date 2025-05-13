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

package shopping.merchant.samples.productstudio.v1alpha;

// [START merchantapi_generate_product_text_suggestions]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.productstudio.v1alpha.GenerateProductTextSuggestionsRequest;
import com.google.shopping.merchant.productstudio.v1alpha.GenerateProductTextSuggestionsResponse;
import com.google.shopping.merchant.productstudio.v1alpha.OutputSpec;
import com.google.shopping.merchant.productstudio.v1alpha.ProductInfo;
import com.google.shopping.merchant.productstudio.v1alpha.TextSuggestionsServiceClient;
import com.google.shopping.merchant.productstudio.v1alpha.TextSuggestionsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to generate product text suggestions. */
public class GenerateProductTextSuggestionsSample {

  private static String getName(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  public static void generateProductTextSuggestions(Config config) throws Exception {
    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    TextSuggestionsServiceSettings textSuggestionsServiceSettings =
        TextSuggestionsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    String name = getName(config.getAccountId().toString());

    // Calls the API and catches and prints any network failures/errors.
    try (TextSuggestionsServiceClient textSuggestionsServiceClient =
        TextSuggestionsServiceClient.create(textSuggestionsServiceSettings)) {

      ProductInfo productInfo =
          ProductInfo.newBuilder()
              .putProductAttributes("title", "Mens shirt")
              .putProductAttributes("description", "A blue shirt for men in size S")
              .build();

      OutputSpec outputSpec = OutputSpec.newBuilder().setWorkflowId("title").build();

      GenerateProductTextSuggestionsRequest request =
          GenerateProductTextSuggestionsRequest.newBuilder()
              .setName(name)
              .setProductInfo(productInfo)
              .setOutputSpec(outputSpec)
              .build();

      System.out.println("Sending GenerateProductTextSuggestions request: " + name);
      GenerateProductTextSuggestionsResponse response =
          textSuggestionsServiceClient.generateProductTextSuggestions(request);
      System.out.println("Generated product text suggestions response below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    generateProductTextSuggestions(config);
  }
}

// [END merchantapi_generate_product_text_suggestions]
