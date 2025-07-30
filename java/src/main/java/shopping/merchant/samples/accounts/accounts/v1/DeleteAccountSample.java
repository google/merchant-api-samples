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

package shopping.merchant.samples.accounts.accounts.v1;
// [START merchantapi_delete_account]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccountName;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1.DeleteAccountRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to delete a given Merchant Center account. */
public class DeleteAccountSample {

  // This method can delete a standalone, advanced account or sub-account. If you delete an advanced
  // account,
  // all sub-accounts will also be deleted.
  // Admin user access is required to execute this method.

  public static void deleteAccount(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountsServiceSettings accountsServiceSettings =
        AccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Gets the account ID from the config file.
    String accountId = config.getAccountId().toString();

    // Creates account name to identify the account.
    String name =
        AccountName.newBuilder()
            .setAccount(accountId)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountsServiceClient accountsServiceClient =
        AccountsServiceClient.create(accountsServiceSettings)) {
      DeleteAccountRequest request =
          DeleteAccountRequest.newBuilder()
              .setName(name)
              // Optional. If set to true, the account will be deleted even if it has offers or
              // provides services to other accounts. Defaults to 'false'.
              .setForce(true)
              .build();

      System.out.println("Sending Delete Account request");
      accountsServiceClient.deleteAccount(request); // No response returned on success.
      System.out.println("Delete successful.");
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    deleteAccount(config);
  }
}
// [END merchantapi_delete_account]