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

package shopping.merchant.samples.reviews.v1beta;

// [START merchantapi_get_merchant_review]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.reviews.v1beta.GetMerchantReviewRequest;
import com.google.shopping.merchant.reviews.v1beta.MerchantReview;
import com.google.shopping.merchant.reviews.v1beta.MerchantReviewsServiceClient;
import com.google.shopping.merchant.reviews.v1beta.MerchantReviewsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get a Merchant review. */
public class GetMerchantReviewSample {

  public static void getMerchantReview(String accountId, String merchantReviewId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    MerchantReviewsServiceSettings merchantReviewsServiceSettings =
        MerchantReviewsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (MerchantReviewsServiceClient merchantReviewsServiceClient =
        MerchantReviewsServiceClient.create(merchantReviewsServiceSettings)) {

      GetMerchantReviewRequest request =
          GetMerchantReviewRequest.newBuilder()
              .setName(String.format("accounts/%s/merchantReviews/%s", accountId, merchantReviewId))
              .build();

      System.out.println("Sending get merchant review request:");
      MerchantReview response = merchantReviewsServiceClient.getMerchantReview(request);
      System.out.println("Merchant review retrieved successfully:");
      System.out.println(response.getName());
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    String merchantReviewId = "YOUR_MERCHANT_REVIEW_ID";
    getMerchantReview(config.getAccountId().toString(), merchantReviewId);
  }
}
// [END merchantapi_get_merchant_review]
