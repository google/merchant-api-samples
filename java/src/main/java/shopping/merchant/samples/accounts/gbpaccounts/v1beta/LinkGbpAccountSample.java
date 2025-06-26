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

package shopping.merchant.samples.accounts.v1beta;

// [START merchantapi_link_gbp_account]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountName;
import com.google.shopping.merchant.accounts.v1beta.GbpAccountsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.GbpAccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.LinkGbpAccountRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to link the specified merchant to a GBP account */
public class LinkGbpAccountSample {

  public static void linkGbpAccount(Config config, String gbpEmail) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    GbpAccountsServiceSettings gbpAccountsServiceSettings =
        GbpAccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (GbpAccountsServiceClient gbpAccountsServiceClient =
        GbpAccountsServiceClient.create(gbpAccountsServiceSettings)) {
      String accountId = config.getAccountId().toString();
      // Creates parent to identify the omnichannelSetting from which to list all Lfp Providers.
      String parent = AccountName.newBuilder().setAccount(accountId).build().toString();

      LinkGbpAccountRequest request =
          LinkGbpAccountRequest.newBuilder().setParent(parent).setGbpEmail(gbpEmail).build();

      System.out.println("Sending link GBP account request:");
      // Empty response returned on success.
      gbpAccountsServiceClient.linkGbpAccount(request);
      System.out.println(String.format("Successfully linked to GBP account: %s", gbpEmail));
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // The email address of the Business Profile account.
    String gbpEmail = "{GBP_EMAIL}";

    linkGbpAccount(config, gbpEmail);
  }
}
// [END merchantapi_link_gbp_account]
