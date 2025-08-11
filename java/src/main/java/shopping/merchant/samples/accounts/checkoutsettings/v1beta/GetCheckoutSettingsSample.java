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
package shopping.merchant.samples.accounts.checkoutsettings.v1beta;

// [START merchantapi_get_checkout_settings]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.CheckoutSettings;
import com.google.shopping.merchant.accounts.v1beta.CheckoutSettingsName;
import com.google.shopping.merchant.accounts.v1beta.CheckoutSettingsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.CheckoutSettingsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.GetCheckoutSettingsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get the checkout settings for a given Merchant Center account */
public class GetCheckoutSettingsSample {

  public static void getCheckoutSettings(Config config) throws Exception {

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
      String name = CheckoutSettingsName.newBuilder().setAccount(accountId).build().toString();

      GetCheckoutSettingsRequest request =
          GetCheckoutSettingsRequest.newBuilder().setName(name).build();

      System.out.println("Sending get checkout settings request:");
      CheckoutSettings response = checkoutSettingsServiceClient.getCheckoutSettings(request);

      System.out.println("Retrieved Checkout Settings below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occurred: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    getCheckoutSettings(config);
  }
}
// [END merchantapi_get_checkout_settings]
