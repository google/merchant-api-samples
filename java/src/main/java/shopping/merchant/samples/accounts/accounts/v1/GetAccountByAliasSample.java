// Copyright 2025 Google LLC
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

package shopping.merchant.samples.accounts.accounts.v1beta;

// [START merchantapi_get_account_by_alias]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.Account;
import com.google.shopping.merchant.accounts.v1beta.AccountName;
import com.google.shopping.merchant.accounts.v1beta.AccountsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.GetAccountRequest;
import shopping.merchant.samples.utils.Authenticator;

/** This class demonstrates how to get a single Merchant Center account by its alias. */
public class GetAccountByAliasSample {

  public static void getAccountByAlias(long providerId, String alias) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountsServiceSettings accountsServiceSettings =
        AccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates account name to identify account.
    // The name has the format: accounts/{providerId}~{alias}
    // This format can used whenever an account name is needed. For example it can also be used to
    // get the homepage of an account or approve, get or list its services etc.
    // For more information about aliases see
    // https://developers.google.com/merchant/api/guides/accounts/relationships
    String name = AccountName.newBuilder().setAccount(providerId + "~" + alias).build().toString();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountsServiceClient accountsServiceClient =
        AccountsServiceClient.create(accountsServiceSettings)) {

      GetAccountRequest request = GetAccountRequest.newBuilder().setName(name).build();

      System.out.println("Sending Get Account request:");
      Account response = accountsServiceClient.getAccount(request);

      System.out.println("Retrieved Account below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    // Update this with the provider ID of the account you want to get.
    long providerId = 123L;
    // Update this with the alias of the account you want to get.
    String alias = "alias";
    getAccountByAlias(providerId, alias);
  }
}
// [END merchantapi_get_account_by_alias]
