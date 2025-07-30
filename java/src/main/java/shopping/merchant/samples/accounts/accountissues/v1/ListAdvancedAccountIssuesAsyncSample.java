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

package shopping.merchant.samples.accounts.accountissues.v1;

// [START merchantapi_list_account_issues_async]
import static com.google.api.core.ApiFutures.transform;

import com.google.api.core.ApiFuture;
import com.google.api.core.ApiFutureCallback;
import com.google.api.core.ApiFutures;
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.common.util.concurrent.MoreExecutors;
import com.google.shopping.merchant.accounts.v1.AccountIssueServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountIssueServiceSettings;
import com.google.shopping.merchant.accounts.v1.AccountName;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient.ListSubAccountsPagedResponse;
import com.google.shopping.merchant.accounts.v1.AccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1.ListAccountIssuesRequest;
import com.google.shopping.merchant.accounts.v1.ListAccountIssuesResponse;
import com.google.shopping.merchant.accounts.v1.ListSubAccountsRequest;
import java.io.IOException;
import java.util.AbstractMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.Executor;
import java.util.stream.Collectors;
import java.util.stream.StreamSupport;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to list the account issues of all the sub-accounts of an advanced
 * account.
 */
public class ListAdvancedAccountIssuesAsyncSample {

  /** Returns the list of issues for the given account. */
  private static ApiFuture<ListAccountIssuesResponse> getAccountIssues(
      AccountIssueServiceClient accountIssueServiceClient, String account) {
    return accountIssueServiceClient
        .listAccountIssuesCallable()
        .futureCall(ListAccountIssuesRequest.newBuilder().setParent(account).build());
  }

  /**
   * Returns a map of account issues where key is the sub-account resource name and the value is the
   * list of issues for each sub-account. Takes the API clients and the name of the advanced account
   * as input.
   */
  private static ApiFuture<Map<String, ListAccountIssuesResponse>> getSubAccountIssues(
      AccountsServiceClient accountsServiceClient,
      AccountIssueServiceClient accountIssueServiceClient,
      String advancedAccount)
      throws IOException {

    // Creates a direct executor to run the transform functions.
    Executor executor = MoreExecutors.directExecutor();

    // The parent has the format: accounts/{account}
    ListSubAccountsRequest request =
        ListSubAccountsRequest.newBuilder().setProvider(advancedAccount).build();
    System.out.println("Sending list subaccounts request:");

    // Lists all sub-accounts of the advanced account.
    ListSubAccountsPagedResponse listSubAccountsResponse =
        accountsServiceClient.listSubAccounts(request);

    // Iterates over all subAccounts and lists account issues for each.
    // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
    List<ApiFuture<AbstractMap.SimpleEntry<String, ListAccountIssuesResponse>>>
        accountIssuesFutures =
            StreamSupport.stream(listSubAccountsResponse.iterateAll().spliterator(), false)
                .map(
                    account ->
                        transform(
                            getAccountIssues(accountIssueServiceClient, account.getName()),
                            (ListAccountIssuesResponse response) ->
                                new AbstractMap.SimpleEntry<>(account.getName(), response),
                            executor))
                .collect(Collectors.toList());

    // Collects all the responses into a single future.
    ApiFuture<List<AbstractMap.SimpleEntry<String, ListAccountIssuesResponse>>> accountIssuesList =
        ApiFutures.allAsList(accountIssuesFutures);

    // Transforms the list of responses into a map.
    return transform(
        accountIssuesList,
        (List<AbstractMap.SimpleEntry<String, ListAccountIssuesResponse>> list) ->
            list.stream()
                .collect(
                    Collectors.toMap(
                        AbstractMap.SimpleEntry::getKey,
                        AbstractMap.SimpleEntry::getValue,
                        (a, b) -> a)),
        executor);
  }

  public static void listAccountIssues(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Gets the account ID from the config file.
    // Make sure to use the advanced account ID here, otherwise the API will return an error.
    String accountId = config.getAccountId().toString();

    // Creates account name to identify account.
    String parent = AccountName.newBuilder().setAccount(accountId).build().toString();

    // Creates service settings using the credentials retrieved above.
    AccountsServiceSettings accountsServiceSettings =
        AccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates service settings using the credentials retrieved above.
    AccountIssueServiceSettings accountIssueServiceSettings =
        AccountIssueServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountsServiceClient accountsServiceClient =
            AccountsServiceClient.create(accountsServiceSettings);
        AccountIssueServiceClient accountIssueServiceClient =
            AccountIssueServiceClient.create(accountIssueServiceSettings)) {

      ApiFuture<Map<String, ListAccountIssuesResponse>> subAccountIssues =
          getSubAccountIssues(accountsServiceClient, accountIssueServiceClient, parent);

      ApiFutures.addCallback(
          subAccountIssues,
          new ApiFutureCallback<Map<String, ListAccountIssuesResponse>>() {
            @Override
            public void onSuccess(Map<String, ListAccountIssuesResponse> results) {
              System.out.println("Account Issues");
              for (Entry<String, ListAccountIssuesResponse> entry : results.entrySet()) {
                System.out.println("Issues for account " + entry.getKey());
                System.out.println(entry.getValue());
              }
            }

            @Override
            public void onFailure(Throwable throwable) {
              System.out.println(throwable);
            }
          },
          MoreExecutors.directExecutor());
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listAccountIssues(config);
  }
}
// [END merchantapi_list_account_issues_async]
