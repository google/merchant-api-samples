// Copyright 2026 Google LLC
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

package shopping.merchant.samples.accounts.accounts.v1;

// [START merchantapi_create_test_account]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.Account;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1.CreateTestAccountRequest;
import com.google.type.TimeZone;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to create a new Merchant Center test account.
 *
 * <p>For more information refer to:
 * https://developers.google.com/merchant/api/guides/accounts/test-accounts
 */
public class CreateTestAccountSample {

  // Method to create a test account.
  public static void createTestAccount(Config config, String newAccountName) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountsServiceSettings accountsServiceSettings =
        AccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountsServiceClient accountsServiceClient =
        AccountsServiceClient.create(accountsServiceSettings)) {

      // The test account to be created.
      Account account =
          Account.newBuilder()
              .setAccountName(newAccountName)
              .setTimeZone(TimeZone.newBuilder().setId("Europe/Zurich"))
              .setLanguageCode("en-US")
              .build();

      // Creates parent to identify where to insert the account.
      String parent = String.format("accounts/%s", config.getAccountId());

      // Create the request message.
      CreateTestAccountRequest request =
          CreateTestAccountRequest.newBuilder().setParent(parent).setAccount(account).build();

      System.out.println("Sending Create Test Account request:");
      Account response = accountsServiceClient.createTestAccount(request);

      System.out.println("Created Test Account below:");
      System.out.println(response);
    } catch (Exception e) {
      System.err.println("Error during test account creation:");
      e.printStackTrace();
    }
  }

  // Main method to run the sample.
  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // This is the name of the new test account to be created.
    String newAccountName = "MyNewTestShop";

    createTestAccount(config, newAccountName);
  }
}
// [END merchantapi_create_test_account]
