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

// [START merchantapi_get_account_limit]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.quota.v1.AccountLimit;
import com.google.shopping.merchant.quota.v1.AccountLimitsServiceClient;
import com.google.shopping.merchant.quota.v1.AccountLimitsServiceSettings;
import com.google.shopping.merchant.quota.v1.GetAccountLimitRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to get a single account limit for a given Merchant Center account.
 */
public class GetAccountLimitSample {

  public static void getAccountLimit(String accountId, String limitId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    AccountLimitsServiceSettings accountLimitsServiceSettings =
        AccountLimitsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (AccountLimitsServiceClient accountLimitsServiceClient =
        AccountLimitsServiceClient.create(accountLimitsServiceSettings)) {

      GetAccountLimitRequest request =
          GetAccountLimitRequest.newBuilder()
              .setName(String.format("accounts/%s/limits/%s", accountId, limitId))
              .build();

      System.out.println("Sending get account limit request:");
      AccountLimit response = accountLimitsServiceClient.getAccountLimit(request);

      System.out.println("Retrieved account limit below");
      System.out.println(response);

    } catch (Exception e) {
      System.out.println("Failed to get account limit.");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // The limit ID is a combination of the limit type and the scope, for example:
    // `products~ADS_NON_EEA`
    getAccountLimit(config.getAccountId().toString(), "products~ADS_EEA");
  }
}
// [END merchantapi_get_account_limit]
