
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
// [START merchantapi_list_promotions]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.promotions.v1beta.ListPromotionsRequest;
import com.google.shopping.merchant.promotions.v1beta.Promotion;
import com.google.shopping.merchant.promotions.v1beta.PromotionsServiceClient;
import com.google.shopping.merchant.promotions.v1beta.PromotionsServiceClient.ListPromotionsPagedResponse;
import com.google.shopping.merchant.promotions.v1beta.PromotionsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list promotions. */
public class ListPromotionsSample {

  public static void listPromotions(String accountId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    PromotionsServiceSettings promotionsServiceSettings =
        PromotionsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (PromotionsServiceClient promotionsServiceClient =
        PromotionsServiceClient.create(promotionsServiceSettings)) {

      ListPromotionsRequest request =
          ListPromotionsRequest.newBuilder()
              .setParent(String.format("accounts/%s", accountId))
              .build();

      System.out.println("Sending list promotions request:");
      ListPromotionsPagedResponse response = promotionsServiceClient.listPromotions(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the datasource in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (Promotion promotion : response.iterateAll()) {
        System.out.println(promotion);
        count++;
      }
      System.out.print("The following count of promotions were returned: ");
      System.out.println(count);

    } catch (Exception e) {
      System.out.println("Failed to list promotions.");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listPromotions(config.getAccountId().toString());
  }
}
// [END merchantapi_list_promotions]