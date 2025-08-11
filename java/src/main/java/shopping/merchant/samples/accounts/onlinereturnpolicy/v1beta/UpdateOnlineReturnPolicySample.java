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

package shopping.merchant.samples.accounts.onlinereturnpolicy.v1beta;

// [START merchantapi_update_online_return_policy]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicy;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicy.ItemCondition;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicy.Policy;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicy.Policy.Type;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicy.ReturnMethod;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicyName;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicyServiceClient;
import com.google.shopping.merchant.accounts.v1beta.OnlineReturnPolicyServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.UpdateOnlineReturnPolicyRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to update an OnlineReturnPolicy for a given Merchant Center account.
 */
public class UpdateOnlineReturnPolicySample {

  public static void updateOnlineReturnPolicy(Config config, String returnPolicyId)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    OnlineReturnPolicyServiceSettings onlineReturnPolicyServiceSettings =
        OnlineReturnPolicyServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates name to identify the online return policy to update.
    String name =
        OnlineReturnPolicyName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setReturnPolicy(returnPolicyId)
            .build()
            .toString();

    // Create an online return policy with the updated fields.
    OnlineReturnPolicy onlineReturnPolicy =
        OnlineReturnPolicy.newBuilder()
            .setName(name)
            .setLabel("US Return Policy")
            .setReturnPolicyUri("https://www.google.com/returnpolicy-sample")
            .addCountries("US")
            .setPolicy(Policy.newBuilder().setType(Type.LIFETIME_RETURNS).build())
            .addItemConditions(ItemCondition.NEW)
            .addReturnMethods(ReturnMethod.IN_STORE)
            .setProcessRefundDays(15)
            .build();

    // Only "*" is supported for the field mask. Please specify all mutable fields.
    FieldMask fieldMask = FieldMask.newBuilder().addPaths("*").build();

    // Calls the API and catches and prints any network failures/errors.
    try (OnlineReturnPolicyServiceClient onlineReturnPolicyServiceClient =
        OnlineReturnPolicyServiceClient.create(onlineReturnPolicyServiceSettings)) {

      // The name has the format: accounts/{account}/onlineReturnPolicies/{return_policy}
      UpdateOnlineReturnPolicyRequest request =
          UpdateOnlineReturnPolicyRequest.newBuilder()
              .setOnlineReturnPolicy(onlineReturnPolicy)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending update OnlineReturnPolicy request:");
      OnlineReturnPolicy response =
          onlineReturnPolicyServiceClient.updateOnlineReturnPolicy(request);

      System.out.println("Updated OnlineReturnPolicy below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // Replace with your OnlineReturnPolicy ID.
    String onlineReturnPolicyId = "<ONLINE_RETURN_POLICY_ID>";
    updateOnlineReturnPolicy(config, onlineReturnPolicyId);
  }
}
// [END merchantapi_update_online_return_policy]
