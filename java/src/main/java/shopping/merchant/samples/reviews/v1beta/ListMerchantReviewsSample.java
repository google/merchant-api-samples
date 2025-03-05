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

// [START merchantapi_list_merchant_reviews]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.reviews.v1beta.ListMerchantReviewsRequest;
import com.google.shopping.merchant.reviews.v1beta.MerchantReview;
import com.google.shopping.merchant.reviews.v1beta.MerchantReviewsServiceClient;
import com.google.shopping.merchant.reviews.v1beta.MerchantReviewsServiceClient.ListMerchantReviewsPagedResponse;
import com.google.shopping.merchant.reviews.v1beta.MerchantReviewsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list all the merchant reviews in a given account. */
public class ListMerchantReviewsSample {

  public static void listMerchantReviews(String accountId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    MerchantReviewsServiceSettings merchantReviewsServiceSettings =
        MerchantReviewsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (MerchantReviewsServiceClient merchantReviewsServiceClient =
        MerchantReviewsServiceClient.create(merchantReviewsServiceSettings)) {

      ListMerchantReviewsRequest request =
          ListMerchantReviewsRequest.newBuilder()
              .setParent(String.format("accounts/%s", accountId))
              .build();

      System.out.println("Sending list merchant reviews request:");
      ListMerchantReviewsPagedResponse response =
          merchantReviewsServiceClient.listMerchantReviews(request);

      int count = 0;

      // Iterates over all rows in all pages and prints all merchant reviews.
      for (MerchantReview element : response.iterateAll()) {
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
    listMerchantReviews(config.getAccountId().toString());
  }
}
// [END merchantapi_list_merchant_reviews]
