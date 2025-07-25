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

package shopping.merchant.samples.quota.v1beta;

// [START merchantapi_list_quota]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.quota.v1beta.ListQuotaGroupsRequest;
import com.google.shopping.merchant.quota.v1beta.QuotaGroup;
import com.google.shopping.merchant.quota.v1beta.QuotaServiceClient;
import com.google.shopping.merchant.quota.v1beta.QuotaServiceClient.ListQuotaGroupsPagedResponse;
import com.google.shopping.merchant.quota.v1beta.QuotaServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list quota for a given Merchant Center account. */
public class ListQuotaSample {

  public static void listQuotas(String accountId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    QuotaServiceSettings quotasServiceSettings =
        QuotaServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (QuotaServiceClient quotaServiceClient = QuotaServiceClient.create(quotasServiceSettings)) {

      ListQuotaGroupsRequest request =
          ListQuotaGroupsRequest.newBuilder()
              .setParent(String.format("accounts/%s", accountId))
              .build();

      System.out.println("Sending list quotas request:");
      ListQuotaGroupsPagedResponse response = quotaServiceClient.listQuotaGroups(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the quota group in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (QuotaGroup quota : response.iterateAll()) {
        System.out.println(quota);
        count++;
      }
      System.out.print("The following count of quota were returned: ");
      System.out.println(count);

    } catch (Exception e) {
      System.out.println("Failed to list quota.");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listQuotas(config.getAccountId().toString());
  }
}
// [END merchantapi_list_quota]
