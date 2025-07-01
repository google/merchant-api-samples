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

package shopping.merchant.samples.accounts.accounttax.v1beta;
// [START merchantapi_get_account_tax]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountTax;
import com.google.shopping.merchant.accounts.v1beta.AccountTaxName;
import com.google.shopping.merchant.accounts.v1beta.AccountTaxServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountTaxServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.GetAccountTaxRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get the account tax settings of a Merchant Center account. */
public class GetAccountTaxSample {

  public static void getAccountTax(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountTaxServiceSettings accountTaxServiceSettings =
        AccountTaxServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates AccountTax name to identify the AccountTax.
    String name =
        AccountTaxName.newBuilder().setAccount(config.getAccountId().toString()).build().toString();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountTaxServiceClient accountTaxServiceClient =
        AccountTaxServiceClient.create(accountTaxServiceSettings)) {

      // The name has the format: accounts/{account}/accountTax
      GetAccountTaxRequest request = GetAccountTaxRequest.newBuilder().setName(name).build();

      System.out.println("Sending get AccountTax request:");
      AccountTax response = accountTaxServiceClient.getAccountTax(request);

      System.out.println("Retrieved AccountTax below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    getAccountTax(config);
  }
}
// [END merchantapi_get_account_tax]