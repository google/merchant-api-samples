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
package shopping.merchant.samples.accounts.checkoutsettings.v1;

// [START merchantapi_create_checkout_settings]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.CheckoutSettings;
import com.google.shopping.merchant.accounts.v1.CheckoutSettingsName;
import com.google.shopping.merchant.accounts.v1.CheckoutSettingsServiceClient;
import com.google.shopping.merchant.accounts.v1.CheckoutSettingsServiceSettings;
import com.google.shopping.merchant.accounts.v1.CreateCheckoutSettingsRequest;
import com.google.shopping.merchant.accounts.v1.UriSettings;
import com.google.shopping.type.Destination.DestinationEnum;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to create checkout settings for a given Merchant Center account. */
public class CreateCheckoutSettingsSample {
  public static void createCheckoutSettings(Config config) throws Exception {
    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    CheckoutSettingsServiceSettings checkoutSettingsServiceSettings =
        CheckoutSettingsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (CheckoutSettingsServiceClient checkoutSettingsServiceClient =
        CheckoutSettingsServiceClient.create(checkoutSettingsServiceSettings)) {
      String accountId = config.getAccountId().toString();
      // The only valid programId for checkout settings is "checkout"
      String programId = "checkout";
      String parent = String.format("accounts/%s/programs/%s", accountId, programId);
      String name =
          CheckoutSettingsName.newBuilder()
              .setAccount(accountId)
              .setProgram(programId)
              .build()
              .toString();
      // TODO: Replace this with your checkout URL.
      String checkoutUrl = "https://myshopify.com/cart/1234:1";

      // Creates a checkout setting for the given account.
      CreateCheckoutSettingsRequest request =
          CreateCheckoutSettingsRequest.newBuilder()
              .setParent(parent)
              .setCheckoutSettings(
                  CheckoutSettings.newBuilder()
                      .setName(name)
                      .setUriSettings(UriSettings.newBuilder().setCheckoutUriTemplate(checkoutUrl))
                      .addEligibleDestinations(DestinationEnum.SHOPPING_ADS))
              .build();

      System.out.println("Sending create checkout settings request:");
      CheckoutSettings response = checkoutSettingsServiceClient.createCheckoutSettings(request);

      System.out.println("Created Checkout Settings below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occurred: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    createCheckoutSettings(config);
  }
}
// [END merchantapi_create_checkout_settings]
