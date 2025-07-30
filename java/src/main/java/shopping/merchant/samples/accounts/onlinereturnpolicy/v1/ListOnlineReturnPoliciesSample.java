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

package shopping.merchant.samples.accounts.onlinereturnpolicy.v1;

// [START merchantapi_list_online_return_policies]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.ListOnlineReturnPoliciesRequest;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicy;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicyServiceClient;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicyServiceClient.ListOnlineReturnPoliciesPagedResponse;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicyServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to list the OnlineReturnPolicy for a given Merchant Center account.
 */
public class ListOnlineReturnPoliciesSample {

  public static void listOnlineReturnPolicies(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    OnlineReturnPolicyServiceSettings onlineReturnPolicyServiceSettings =
        OnlineReturnPolicyServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates parent to identify where to list the online return policies.
    // The parent has the format: accounts/{account}
    String parent = "accounts/" + config.getAccountId().toString();

    // Calls the API and catches and prints any network failures/errors.
    try (OnlineReturnPolicyServiceClient onlineReturnPolicyServiceClient =
        OnlineReturnPolicyServiceClient.create(onlineReturnPolicyServiceSettings)) {

      // The name has the format: accounts/{account}/onlineReturnPolicies
      ListOnlineReturnPoliciesRequest request =
          ListOnlineReturnPoliciesRequest.newBuilder().setParent(parent).setPageSize(20).build();

      System.out.println("Sending List OnlineReturnPolicies request:");
      ListOnlineReturnPoliciesPagedResponse onlineReturnPolicyPagedResponse =
          onlineReturnPolicyServiceClient.listOnlineReturnPolicies(request);

      int count = 0;

      // Iterates through the OnlineReturnPolicy and prints each OnlineReturnPolicy.
      for (OnlineReturnPolicy onlineReturnPolicy : onlineReturnPolicyPagedResponse.iterateAll()) {
        System.out.println(onlineReturnPolicy);
        count++;
      }

      System.out.println("The following count of OnlineReturnPolicies is returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    listOnlineReturnPolicies(config);
  }
}
// [END merchantapi_list_online_return_policies]
