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

// [START merchantapi_delete_online_return_policy]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.DeleteOnlineReturnPolicyRequest;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicyName;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicyServiceClient;
import com.google.shopping.merchant.accounts.v1.OnlineReturnPolicyServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to delete the OnlineReturnPolicy for a given Merchant Center account.
 */
public class DeleteOnlineReturnPolicySample {

  public static void deleteOnlineReturnPolicy(Config config, String returnPolicyId)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    OnlineReturnPolicyServiceSettings onlineReturnPolicyServiceSettings =
        OnlineReturnPolicyServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates OnlineReturnPolicy name to identify OnlineReturnPolicy.
    String name =
        OnlineReturnPolicyName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setReturnPolicy(returnPolicyId)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (OnlineReturnPolicyServiceClient onlineReturnPolicyServiceClient =
        OnlineReturnPolicyServiceClient.create(onlineReturnPolicyServiceSettings)) {

      // The name has the format: accounts/{account}/onlineReturnPolicies/{return_policy}
      DeleteOnlineReturnPolicyRequest request =
          DeleteOnlineReturnPolicyRequest.newBuilder().setName(name).build();

      System.out.println("Sending Delete OnlineReturnPolicy request:");
      onlineReturnPolicyServiceClient.deleteOnlineReturnPolicy(request);

      System.out.println("Deleted OnlineReturnPolicy successfully.");
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // Replace with the actual return policy ID you want to delete.
    String returnPolicyId = "<SET_RETURN_POLICY_ID>";
    deleteOnlineReturnPolicy(config, returnPolicyId);
  }
}
// [END merchantapi_delete_online_return_policy]
