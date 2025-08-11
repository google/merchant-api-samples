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

package shopping.merchant.samples.accounts.accountservices.v1;
// [START merchantapi_propose_account_service]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccountAggregation;
import com.google.shopping.merchant.accounts.v1.AccountService;
import com.google.shopping.merchant.accounts.v1.AccountServicesServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountServicesServiceSettings;
import com.google.shopping.merchant.accounts.v1.ProposeAccountServiceRequest;
import java.math.BigInteger;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to propose an account service. */
public class ProposeAccountServiceSample {

  private static String toAccountName(BigInteger accountId) {
    return String.format("accounts/%d", accountId);
  }

  public static void proposeAccountService(Config config, BigInteger providerId) throws Exception {

    GoogleCredentials credential = new Authenticator().authenticate();

    AccountServicesServiceSettings accountServiceServiceSettings =
        AccountServicesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (AccountServicesServiceClient accountServiceServiceClient =
        AccountServicesServiceClient.create(accountServiceServiceSettings)) {

      ProposeAccountServiceRequest request =
          ProposeAccountServiceRequest.newBuilder()
              .setParent(toAccountName(config.getAccountId()))
              .setProvider(toAccountName(providerId))
              .setAccountService(
                  AccountService.newBuilder()
                      .setAccountAggregation(AccountAggregation.getDefaultInstance())
                      .build())
              .build();

      System.out.println("Sending Propose AccountService request");
      AccountService response = accountServiceServiceClient.proposeAccountService(request);
      System.out.println("Proposed AccountService below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // Update this with the Merchant Center provider ID you want to get the relationship for.
    BigInteger providerId = BigInteger.valueOf(111);
    proposeAccountService(config, providerId);
  }
}
// [END merchantapi_propose_account_service]