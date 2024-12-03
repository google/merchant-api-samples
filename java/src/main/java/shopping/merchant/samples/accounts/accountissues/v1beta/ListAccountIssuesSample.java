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

package shopping.merchant.samples.accounts.accountissues.v1beta;

import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountIssue;
import com.google.shopping.merchant.accounts.v1beta.AccountIssueServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountIssueServiceClient.ListAccountIssuesPagedResponse;
import com.google.shopping.merchant.accounts.v1beta.AccountIssueServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.AccountName;
import com.google.shopping.merchant.accounts.v1beta.ListAccountIssuesRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to list all the account issues of an account. */
public class ListAccountIssuesSample {

  // [START list_account_issues]
  public static void listAccountIssues(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountIssueServiceSettings accountIssueServiceSettings =
        AccountIssueServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountIssueServiceClient accountIssueServiceClient =
        AccountIssueServiceClient.create(accountIssueServiceSettings)) {

      // Gets the account ID from the config file.
      String accountId = config.getAccountId().toString();

      // Creates account name to identify account.
      String name = AccountName.newBuilder().setAccount(accountId).build().toString();
      ListAccountIssuesRequest request =
          ListAccountIssuesRequest.newBuilder().setParent(name).build();

      System.out.println("Sending list account issues request:");
      ListAccountIssuesPagedResponse response =
          accountIssueServiceClient.listAccountIssues(request);

      int count = 0;

      // Iterates over all rows in all pages and prints the issue in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (AccountIssue accountIssue : response.iterateAll()) {
        System.out.println(accountIssue);
        count++;
      }
      System.out.print("The following count of account issues were returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  // [END list_account_issues]

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAccountIssues(config);
  }
}
