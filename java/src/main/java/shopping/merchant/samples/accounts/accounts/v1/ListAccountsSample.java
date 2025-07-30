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

// [START merchantapi_list_accounts]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.Account;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient.ListAccountsPagedResponse;
import com.google.shopping.merchant.accounts.v1.AccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1.ListAccountsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to list all the accounts the user making the request has access to.
 * Please note that "listAccounts" method charge API quota on behalf of each specific user running the
 * request. "listSubAccounts" method is more suitable to list large number of sub-accounts.
 */
public class ListAccountsSample {

  public static void listAccounts(Config config) throws Exception {

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

      ListAccountsRequest request = ListAccountsRequest.newBuilder().build();

      System.out.println("Sending list accounts request:");
      ListAccountsPagedResponse response = accountsServiceClient.listAccounts(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the datasource in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (Account account : response.iterateAll()) {
        System.out.println(account);
        count++;
      }
      System.out.print("The following count of accounts were returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAccounts(config);
  }
}
// [END merchantapi_list_accounts]