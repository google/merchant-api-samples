// Copyright 2025 Google LLC

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

package shopping.merchant.samples.lfp.v1;

// [START merchantapi_get_lfp_merchant_state]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.lfp.v1.GetLfpMerchantStateRequest;
import com.google.shopping.merchant.lfp.v1.LfpMerchantState;
import com.google.shopping.merchant.lfp.v1.LfpMerchantStateName;
import com.google.shopping.merchant.lfp.v1.LfpMerchantStateServiceClient;
import com.google.shopping.merchant.lfp.v1.LfpMerchantStateServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get the LFP state for a given Merchant Center account */
public class GetLfpMerchantStateSample {

  public static void getLfpMerchantState(Config config, String targetMerchantId) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    LfpMerchantStateServiceSettings lfpMerchantStateServiceSettings =
        LfpMerchantStateServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Gets the LFP account ID from the user's configuration.
    String lfpAccountId = config.getAccountId().toString();
    // Calls the API and catches and prints any network failures/errors.
    try (LfpMerchantStateServiceClient lfpMerchantStateServiceClient =
        LfpMerchantStateServiceClient.create(lfpMerchantStateServiceSettings)) {

      GetLfpMerchantStateRequest request =
          GetLfpMerchantStateRequest.newBuilder()
              .setName(LfpMerchantStateName.of(lfpAccountId, targetMerchantId).toString())
              .build();

      System.out.println("Sending get LFP merchant state request:");
      LfpMerchantState response = lfpMerchantStateServiceClient.getLfpMerchantState(request);

      System.out.println("Retrieved LFP merchant state below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // The Merchant Center account ID.
    String targetMerchantId = "{target_merchant}";

    getLfpMerchantState(config, targetMerchantId);
  }
}
// [END merchantapi_get_lfp_merchant_state]
