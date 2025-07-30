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

package shopping.merchant.samples.reviews.v1;

// [START merchantapi_insert_merchant_reviews_async]
import com.google.api.core.ApiFuture;
import com.google.api.core.ApiFutureCallback;
import com.google.api.core.ApiFutures;
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.common.util.concurrent.MoreExecutors;
import com.google.protobuf.Timestamp;
import com.google.shopping.merchant.reviews.v1.InsertMerchantReviewRequest;
import com.google.shopping.merchant.reviews.v1.MerchantReview;
import com.google.shopping.merchant.reviews.v1.MerchantReviewAttributes;
import com.google.shopping.merchant.reviews.v1.MerchantReviewsServiceClient;
import com.google.shopping.merchant.reviews.v1.MerchantReviewsServiceSettings;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.util.stream.Collectors;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to insert multiple merchant reviews asynchronously. */
public class InsertMerchantReviewsAsyncSample {

  private static String generateRandomString() {
    String characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    Random random = new Random();
    StringBuilder sb = new StringBuilder(8);
    for (int i = 0; i < 8; i++) {
      sb.append(characters.charAt(random.nextInt(characters.length())));
    }
    return sb.toString();
  }

  // Returns a merchant review with a random ID.
  private static MerchantReview createMerchantReview(String accountId) {
    String merchantReviewId = generateRandomString();

    MerchantReviewAttributes attributes =
        MerchantReviewAttributes.newBuilder()
            .setTitle("Great Merchant!")
            .setContent("Would buy there again.")
            .setMinRating(1)
            .setMaxRating(5)
            .setRating(4)
            .setReviewTime(Timestamp.newBuilder().setSeconds(1731165684).build())
            .setReviewLanguage("en-US")
            .build();

    return MerchantReview.newBuilder()
        .setMerchantReviewId(merchantReviewId)
        .setAttributes(attributes)
        .build();
  }

  public static void asyncInsertMerchantReviews(String accountId, String dataSourceId)
      throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    MerchantReviewsServiceSettings merchantReviewsServiceSettings =
        MerchantReviewsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (MerchantReviewsServiceClient merchantReviewsServiceClient =
        MerchantReviewsServiceClient.create(merchantReviewsServiceSettings)) {

      // Arbitrarily creates five merchant reviews with random IDs.
      List<InsertMerchantReviewRequest> requests = new ArrayList<>();
      for (int i = 0; i < 5; i++) {
        InsertMerchantReviewRequest request =
            InsertMerchantReviewRequest.newBuilder()
                .setParent(String.format("accounts/%s", accountId))
                .setMerchantReview(createMerchantReview(accountId))
                // Must be a merchant reviews data source. In other words, a data source whose
                // "type"
                // is MerchantReviewDataSource.
                .setDataSource(String.format("accounts/%s/dataSources/%s", accountId, dataSourceId))
                .build();
        requests.add(request);
      }

      // Inserts the merchant reviews.
      List<ApiFuture<MerchantReview>> futures =
          requests.stream()
              .map(
                  request ->
                      merchantReviewsServiceClient
                          .insertMerchantReviewCallable()
                          .futureCall(request))
              .collect(Collectors.toList());

      // Creates callback to handle the responses when all are ready.
      ApiFuture<List<MerchantReview>> responses = ApiFutures.allAsList(futures);
      ApiFutures.addCallback(
          responses,
          new ApiFutureCallback<List<MerchantReview>>() {
            @Override
            public void onSuccess(List<MerchantReview> results) {
              System.out.println("Inserted merchant reviews below:");
              System.out.println(results);
            }

            @Override
            public void onFailure(Throwable throwable) {
              System.out.println(throwable);
            }
          },
          MoreExecutors.directExecutor());
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    asyncInsertMerchantReviews(config.getAccountId().toString(), "YOUR_DATA_SOURCE_ID");
  }
}
// [END merchantapi_insert_merchant_reviews_async]
