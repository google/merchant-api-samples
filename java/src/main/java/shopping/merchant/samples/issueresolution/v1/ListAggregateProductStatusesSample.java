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

package shopping.merchant.samples.issueresolution.v1beta;

// [START merchantapi_list_aggregate_product_statuses]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountName;
import com.google.shopping.merchant.issueresolution.v1beta.AggregateProductStatus;
import com.google.shopping.merchant.issueresolution.v1beta.AggregateProductStatusesServiceClient;
import com.google.shopping.merchant.issueresolution.v1beta.AggregateProductStatusesServiceClient.ListAggregateProductStatusesPagedResponse;
import com.google.shopping.merchant.issueresolution.v1beta.AggregateProductStatusesServiceSettings;
import com.google.shopping.merchant.issueresolution.v1beta.ListAggregateProductStatusesRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to list all the accounts the user making the request has access to.
 */
public class ListAggregateProductStatusesSample {
  public static void listAggregateProductStatuses(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AggregateProductStatusesServiceSettings aggregateProductStatusesServiceSettings =
        AggregateProductStatusesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (AggregateProductStatusesServiceClient aggregateProductStatusesServiceClient =
        AggregateProductStatusesServiceClient.create(aggregateProductStatusesServiceSettings)) {

      // Gets the account ID from the config file.
      String accountId = config.getAccountId().toString();

      // Creates account name to identify account.
      String accountName = AccountName.newBuilder().setAccount(accountId).build().toString();
      ListAggregateProductStatusesRequest request =
          ListAggregateProductStatusesRequest.newBuilder()
              .setParent(accountName)
              .setFilter("country = \"US\"") // Optionally set a filter.
              .build();

      System.out.println("Sending list aggregate product statuses request");
      ListAggregateProductStatusesPagedResponse response =
          aggregateProductStatusesServiceClient.listAggregateProductStatuses(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the datasource in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (AggregateProductStatus aggregateProductStatus : response.iterateAll()) {
        System.out.println("Printing response:");
        System.out.println(aggregateProductStatus);
        count++;
      }
      System.out.print("The following count of aggregate product statuses were returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAggregateProductStatuses(config);
  }
}
// [END merchantapi_list_aggregate_product_statuses]
