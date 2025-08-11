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

package shopping.merchant.samples.accounts.accountrelationships.v1;

// [START merchantapi_list_account_relationships]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccountRelationship;
import com.google.shopping.merchant.accounts.v1.AccountRelationshipsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountRelationshipsServiceClient.ListAccountRelationshipsPagedResponse;
import com.google.shopping.merchant.accounts.v1.AccountRelationshipsServiceSettings;
import com.google.shopping.merchant.accounts.v1.ListAccountRelationshipsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list all the account relationships of an account. */
public class ListAccountRelationshipsSample {

  private static String getParent(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  public static void listAccountRelationships(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountRelationshipsServiceSettings accountsRelationshipsServiceSettings =
        AccountRelationshipsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates parent to identify the account from which to list all account relationships.
    String parent = getParent(config.getAccountId().toString());

    // Calls the API and catches and prints any network failures/errors.
    try (AccountRelationshipsServiceClient accountRelationshipsServiceClient =
        AccountRelationshipsServiceClient.create(accountsRelationshipsServiceSettings)) {

      ListAccountRelationshipsRequest request =
          ListAccountRelationshipsRequest.newBuilder().setParent(parent).build();

      System.out.println("Sending list account relationships request:");
      ListAccountRelationshipsPagedResponse response =
          accountRelationshipsServiceClient.listAccountRelationships(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the relationship in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (AccountRelationship accountRelationship : response.iterateAll()) {
        System.out.println(accountRelationship);
        count++;
      }
      System.out.print("The following count of account relationships were returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAccountRelationships(config);
  }
}
// [END merchantapi_list_account_relationships]