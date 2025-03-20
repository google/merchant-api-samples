// Copyright 2024 Google LLC
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

package shopping.merchant.samples.accounts.automaticimprovements.v1beta;
// [START merchantapi_update_automaticimprovements]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImageImprovements;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImageImprovements.ImageImprovementsAccountLevelSettings;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovements;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovementsName;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovementsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovementsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.AutomaticItemUpdates;
import com.google.shopping.merchant.accounts.v1beta.AutomaticItemUpdates.ItemUpdatesAccountLevelSettings;
import com.google.shopping.merchant.accounts.v1beta.AutomaticShippingImprovements;
import com.google.shopping.merchant.accounts.v1beta.UpdateAutomaticImprovementsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to update AutomaticImprovements to be enabled. */
public class UpdateAutomaticImprovementsSample {

  public static void updateAutomaticImprovements(Config config) throws Exception {

    GoogleCredentials credential = new Authenticator().authenticate();

    AutomaticImprovementsServiceSettings automaticImprovementsServiceSettings =
        AutomaticImprovementsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates AutomaticImprovements name to identify AutomaticImprovements.
    String name =
        AutomaticImprovementsName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .build()
            .toString();

    // Create AutomaticImprovements with the updated fields.
    AutomaticImprovements automaticImprovements =
        AutomaticImprovements.newBuilder()
            .setName(name)
            .setItemUpdates(
                AutomaticItemUpdates.newBuilder()
                    .setAccountItemUpdatesSettings(
                        ItemUpdatesAccountLevelSettings.newBuilder()
                            .setAllowPriceUpdates(true)
                            .setAllowAvailabilityUpdates(true)
                            .setAllowStrictAvailabilityUpdates(true)
                            .setAllowConditionUpdates(true)
                            .build())
                    .build())
            .setImageImprovements(
                AutomaticImageImprovements.newBuilder()
                    .setAccountImageImprovementsSettings(
                        ImageImprovementsAccountLevelSettings.newBuilder()
                            .setAllowAutomaticImageImprovements(true)
                            .build())
                    .build())
            .setShippingImprovements(
                AutomaticShippingImprovements.newBuilder()
                    .setAllowShippingImprovements(true)
                    .build())
            .build();

    FieldMask fieldMask = FieldMask.newBuilder().addPaths("*").build();

    try (AutomaticImprovementsServiceClient automaticImprovementsServiceClient =
        AutomaticImprovementsServiceClient.create(automaticImprovementsServiceSettings)) {

      UpdateAutomaticImprovementsRequest request =
          UpdateAutomaticImprovementsRequest.newBuilder()
              .setAutomaticImprovements(automaticImprovements)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending Update AutomaticImprovements request");
      AutomaticImprovements response =
          automaticImprovementsServiceClient.updateAutomaticImprovements(request);
      System.out.println("Updated AutomaticImprovements Name below");
      System.out.println(response.getName());
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    updateAutomaticImprovements(config);
  }
}
// [END merchantapi_update_automaticimprovements]