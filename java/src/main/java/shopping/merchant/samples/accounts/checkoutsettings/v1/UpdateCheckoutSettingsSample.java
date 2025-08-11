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

// [START merchantapi_update_checkout_settings]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.CheckoutSettings;
import com.google.shopping.merchant.accounts.v1.CheckoutSettingsName;
import com.google.shopping.merchant.accounts.v1.CheckoutSettingsServiceClient;
import com.google.shopping.merchant.accounts.v1.CheckoutSettingsServiceSettings;
import com.google.shopping.merchant.accounts.v1.UpdateCheckoutSettingsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to update the checkout settings for a given Merchant Center account
 */
public class UpdateCheckoutSettingsSample {
  public static void updateBusinessInfo(Config config) throws Exception {
    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    CheckoutSettingsServiceSettings checkoutSettingsServiceSettings =
        CheckoutSettingsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();
    try (CheckoutSettingsServiceClient checkoutSettingsServiceClient =
        CheckoutSettingsServiceClient.create(checkoutSettingsServiceSettings)) {
      String accountId = config.getAccountId().toString();
      String name = CheckoutSettingsName.newBuilder().setAccount(accountId).build().toString();

      CheckoutSettings checkoutSettings =
          CheckoutSettings.newBuilder()
              .setName(name)
              .setCheckoutSettings(
                  CheckoutSettings.newBuilder()
                      .setName(name)
                      .setUriSettings(UriSettings.newBuilder().setCheckoutUriTemplate(checkoutUrl))
                      .addEligibleDestinations(DestinationEnum.SHOPPING_ADS))
              .build();
      FieldMask fieldMask =
          FieldMask.newBuilder().addPaths("uri_settings").addPaths("eligible_destinations").build();
      UpdateCheckoutSettingsRequest request =
          UpdateCheckoutSettingsRequest.newBuilder()
              .setCheckoutSettings(checkoutSettings)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending update checkoutSettings request:");
      CheckoutSettings response = checkoutSettingsServiceClient.updateCheckoutSettings(request);

      System.out.println("Updated Checkout Settings below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occurred: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    updateBusinessInfo(config);
  }
}
// [END merchantapi_update_checkout_settings]
