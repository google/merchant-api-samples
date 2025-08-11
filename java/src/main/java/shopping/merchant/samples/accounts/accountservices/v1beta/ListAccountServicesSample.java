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

package shopping.merchant.samples.accounts.accountservices.v1beta;
// [START merchantapi_list_account_services]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountService;
import com.google.shopping.merchant.accounts.v1beta.AccountServicesServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountServicesServiceClient.ListAccountServicesPagedResponse;
import com.google.shopping.merchant.accounts.v1beta.AccountServicesServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.ListAccountServicesRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list all the account services of an account. */
public class ListAccountServicesSample {

  private static String getParent(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  public static void listAccountServices(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountServicesServiceSettings accountsServicesServiceSettings =
        AccountServicesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates parent to identify the account from which to list all account services.
    String parent = getParent(config.getAccountId().toString());

    // Calls the API and catches and prints any network failures/errors.
    try (AccountServicesServiceClient accountServicesServiceClient =
        AccountServicesServiceClient.create(accountsServicesServiceSettings)) {

      ListAccountServicesRequest request =
          ListAccountServicesRequest.newBuilder().setParent(parent).build();

      System.out.println("Sending list account services request:");
      ListAccountServicesPagedResponse response =
          accountServicesServiceClient.listAccountServices(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the service in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (AccountService accountService : response.iterateAll()) {
        System.out.println(accountService);
        count++;
      }
      System.out.print("The following count of account services were returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAccountServices(config);
  }
}
// [END merchantapi_list_account_services]