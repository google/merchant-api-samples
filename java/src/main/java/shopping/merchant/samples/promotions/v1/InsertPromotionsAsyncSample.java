
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

package shopping.merchant.samples.promotions.v1beta;
// [START merchantapi_insert_promotions_async]
import com.google.api.core.ApiFuture;
import com.google.api.core.ApiFutureCallback;
import com.google.api.core.ApiFutures;
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.common.util.concurrent.MoreExecutors;
import com.google.protobuf.Timestamp;
import com.google.shopping.merchant.promotions.v1beta.Attributes;
import com.google.shopping.merchant.promotions.v1beta.CouponValueType;
import com.google.shopping.merchant.promotions.v1beta.InsertPromotionRequest;
import com.google.shopping.merchant.promotions.v1beta.OfferType;
import com.google.shopping.merchant.promotions.v1beta.ProductApplicability;
import com.google.shopping.merchant.promotions.v1beta.Promotion;
import com.google.shopping.merchant.promotions.v1beta.PromotionsServiceClient;
import com.google.shopping.merchant.promotions.v1beta.PromotionsServiceSettings;
import com.google.shopping.merchant.promotions.v1beta.RedemptionChannel;
import com.google.shopping.type.CustomAttribute;
import com.google.shopping.type.Destination.DestinationEnum;
import com.google.type.Interval;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.util.stream.Collectors;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to insert multiple promotions asynchronously. */
public class InsertPromotionsAsyncSample {

  private static String generateRandomString() {
    String characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    Random random = new Random();
    StringBuilder sb = new StringBuilder(8);
    for (int i = 0; i < 8; i++) {
      sb.append(characters.charAt(random.nextInt(characters.length())));
    }
    return sb.toString();
  }

  private static Promotion createPromotion(String accountId) {
    String merchantPromotionId = generateRandomString();

    Attributes attributes =
        Attributes.newBuilder()
            .setProductApplicability(ProductApplicability.ALL_PRODUCTS)
            .setOfferType(OfferType.GENERIC_CODE)
            .setGenericRedemptionCode("ABCD1234")
            .setLongTitle("My promotion")
            .setCouponValueType(CouponValueType.PERCENT_OFF)
            .addPromotionDestinations(DestinationEnum.SHOPPING_ADS)
            .setPercentOff(10)
            // Note that promotions have a 6-month limit.
            // For more information, read here: https://support.google.com/merchants/answer/2906014
            // Also note that only promotions valid within the past 365 days are shown in the UI.
            .setPromotionEffectiveTimePeriod(
                Interval.newBuilder()
                    .setStartTime(Timestamp.newBuilder().setSeconds(1726842472))
                    .setEndTime(Timestamp.newBuilder().setSeconds(1726842473))
                    .build())
            .build();

    return Promotion.newBuilder()
        .setName(String.format("accounts/%s/merchantPromotions/%s", accountId, merchantPromotionId))
        .setPromotionId(merchantPromotionId)
        .setContentLanguage("fr")
        .setTargetCountry("CH")
        .addRedemptionChannel(RedemptionChannel.ONLINE)
        .setAttributes(attributes)
        // Custom attributes allow you to add additional information which is not available in
        // Attributes. For example, you might want to pilot experimental functionality.
        .addCustomAttributes(
            CustomAttribute.newBuilder()
                .setName("another example name")
                .setValue("another example value")
                .build())
        .build();
  }

  public static void asyncInsertPromotions(String accountId, String dataSourceId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    PromotionsServiceSettings merchantPromotionsServiceSettings =
        PromotionsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (PromotionsServiceClient merchantPromotionsServiceClient =
        PromotionsServiceClient.create(merchantPromotionsServiceSettings)) {

      // Arbitrarily creates five merchant promotions with random IDs.
      List<InsertPromotionRequest> requests = new ArrayList<>();
      for (int i = 0; i < 5; i++) {
        InsertPromotionRequest request =
            InsertPromotionRequest.newBuilder()
                .setParent(String.format("accounts/%s", accountId))
                .setPromotion(createPromotion(accountId))
                .setDataSource(String.format("accounts/%s/dataSources/%s", accountId, dataSourceId))
                .build();
        requests.add(request);
      }

      // Inserts the merchant promotions.
      List<ApiFuture<Promotion>> futures =
          requests.stream()
              .map(
                  request ->
                      merchantPromotionsServiceClient.insertPromotionCallable().futureCall(request))
              .collect(Collectors.toList());

      // Creates callback to handle the responses when all are ready.
      ApiFuture<List<Promotion>> responses = ApiFutures.allAsList(futures);
      ApiFutures.addCallback(
          responses,
          new ApiFutureCallback<List<Promotion>>() {
            @Override
            public void onSuccess(List<Promotion> results) {
              System.out.println("Inserted merchant promotions below:");
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
    asyncInsertPromotions(config.getAccountId().toString(), "<YOUR_DATA_SOURCE_ID>");
  }
}
// [END merchantapi_insert_promotions_async]