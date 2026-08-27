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

package shopping.merchant.samples.quota.v1;

// [START merchantapi_list_account_limits]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.quota.v1.AccountLimit;
import com.google.shopping.merchant.quota.v1.AccountLimitsServiceClient;
import com.google.shopping.merchant.quota.v1.AccountLimitsServiceClient.ListAccountLimitsPagedResponse;
import com.google.shopping.merchant.quota.v1.AccountLimitsServiceSettings;
import com.google.shopping.merchant.quota.v1.ListAccountLimitsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list account limits for a given Merchant Center account. */
public class ListAccountLimitsSample {

  public static void listAccountLimits(String accountId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    AccountLimitsServiceSettings accountLimitsServiceSettings =
        AccountLimitsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (AccountLimitsServiceClient accountLimitsServiceClient =
        AccountLimitsServiceClient.create(accountLimitsServiceSettings)) {

      ListAccountLimitsRequest request =
          ListAccountLimitsRequest.newBuilder()
              .setParent(String.format("accounts/%s", accountId))
              .setFilter("type = \"products\"")
              .build();

      System.out.println("Sending list account limits request:");
      ListAccountLimitsPagedResponse response =
          accountLimitsServiceClient.listAccountLimits(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the account limit in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (AccountLimit accountLimit : response.iterateAll()) {
        System.out.println(accountLimit);
        count++;
      }
      System.out.print("The following count of account limits were returned: ");
      System.out.println(count);

    } catch (Exception e) {
      System.out.println("Failed to list account limits.");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAccountLimits(config.getAccountId().toString());
  }
}
// [END merchantapi_list_account_limits]
