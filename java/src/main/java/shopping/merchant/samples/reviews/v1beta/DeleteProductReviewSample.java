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

package shopping.merchant.samples.reviews.v1beta;

import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.reviews.v1beta.DeleteProductReviewRequest;
import com.google.shopping.merchant.reviews.v1beta.ProductReviewsServiceClient;
import com.google.shopping.merchant.reviews.v1beta.ProductReviewsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to delete a product review. */
public class DeleteProductReviewSample {

  // [START delete_product_review]
  public static void deleteProductReview(String accountId, String productReviewId)
      throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    ProductReviewsServiceSettings productReviewsServiceSettings =
        ProductReviewsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (ProductReviewsServiceClient productReviewsServiceClient =
        ProductReviewsServiceClient.create(productReviewsServiceSettings)) {

      DeleteProductReviewRequest request =
          DeleteProductReviewRequest.newBuilder()
              .setName(String.format("accounts/%s/productReviews/%s", accountId, productReviewId))
              .build();

      System.out.println("Sending delete product review request:");
      productReviewsServiceClient.deleteProductReview(request);
      System.out.println("Product review deleted successfully");
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  // [END delete_product_review]

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    String productReviewId = "YOUR_PRODUCT_REVIEW_ID";
    deleteProductReview(config.getAccountId().toString(), productReviewId);
  }
}
