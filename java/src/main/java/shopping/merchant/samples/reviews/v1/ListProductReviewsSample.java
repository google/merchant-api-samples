// Copyright 2023 Google LLC
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

package shopping.merchant.samples.reviews.v1;

// [START merchantapi_list_product_reviews]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.reviews.v1.ListProductReviewsRequest;
import com.google.shopping.merchant.reviews.v1.ProductReview;
import com.google.shopping.merchant.reviews.v1.ProductReviewsServiceClient;
import com.google.shopping.merchant.reviews.v1.ProductReviewsServiceClient.ListProductReviewsPagedResponse;
import com.google.shopping.merchant.reviews.v1.ProductReviewsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list all the product reviews in a given account. */
public class ListProductReviewsSample {

  public static void listProductReviews(String accountId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    ProductReviewsServiceSettings productReviewsServiceSettings =
        ProductReviewsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (ProductReviewsServiceClient productReviewsServiceClient =
        ProductReviewsServiceClient.create(productReviewsServiceSettings)) {

      ListProductReviewsRequest request =
          ListProductReviewsRequest.newBuilder()
              .setParent(String.format("accounts/%s", accountId))
              .build();

      System.out.println("Sending list product reviews request:");
      ListProductReviewsPagedResponse response =
          productReviewsServiceClient.listProductReviews(request);

      int count = 0;

      // Iterates over all rows in all pages and prints all product reviews.
      for (ProductReview element : response.iterateAll()) {
        System.out.println(element);
        count++;
      }
      System.out.print("The following count of elements were returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listProductReviews(config.getAccountId().toString());
  }
}
// [END merchantapi_list_product_reviews]
