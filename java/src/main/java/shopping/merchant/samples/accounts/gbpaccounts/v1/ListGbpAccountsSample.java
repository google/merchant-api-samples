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

package shopping.merchant.samples.accounts.gbpaccounts.v1;

// [START merchantapi_list_gbp_accounts]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccountName;
import com.google.shopping.merchant.accounts.v1.GbpAccount;
import com.google.shopping.merchant.accounts.v1.GbpAccountsServiceClient;
import com.google.shopping.merchant.accounts.v1.GbpAccountsServiceClient.ListGbpAccountsPagedResponse;
import com.google.shopping.merchant.accounts.v1.GbpAccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1.ListGbpAccountsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to get the list of GBP accounts for a given Merchant Center account
 */
public class ListGbpAccountsSample {

  public static void listGbpAccounts(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    GbpAccountsServiceSettings gbpAccountsServiceSettings =
        GbpAccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    String accountId = config.getAccountId().toString();
    // Creates parent to identify the omnichannelSetting from which to list all Lfp Providers.
    String parent = AccountName.newBuilder().setAccount(accountId).build().toString();

    // Calls the API and catches and prints any network failures/errors.
    try (GbpAccountsServiceClient gbpAccountsServiceClient =
        GbpAccountsServiceClient.create(gbpAccountsServiceSettings)) {
      ListGbpAccountsRequest request =
          ListGbpAccountsRequest.newBuilder().setParent(parent).build();

      System.out.println("Sending list GBP accounts request:");
      ListGbpAccountsPagedResponse response = gbpAccountsServiceClient.listGbpAccounts(request);

      int count = 0;

      // Iterates over all the entries in the response.
      for (GbpAccount gbpAccount : response.iterateAll()) {
        System.out.println(gbpAccount);
        count++;
      }
      System.out.println(String.format("The following count of elements were returned: %d", count));
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    listGbpAccounts(config);
  }
}
// [END merchantapi_list_gbp_accounts]
