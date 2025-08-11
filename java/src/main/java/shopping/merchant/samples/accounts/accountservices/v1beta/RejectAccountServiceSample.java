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

package shopping.merchant.samples.accounts.accountservices.v1beta;
// [START merchantapi_reject_account_service]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountServiceName;
import com.google.shopping.merchant.accounts.v1beta.AccountServicesServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountServicesServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.RejectAccountServiceRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to reject an account service. */
public class RejectAccountServiceSample {

  public static void rejectAccountService(Config config, String service) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountServicesServiceSettings accountServicesServiceSettings =
        AccountServicesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Gets the account ID from the config file.
    String accountId = config.getAccountId().toString();

    // Creates account service name to identify the account service.
    String name =
        AccountServiceName.newBuilder()
            .setAccount(accountId)
            .setService(service)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountServicesServiceClient accountServicesServiceClient =
        AccountServicesServiceClient.create(accountServicesServiceSettings)) {

      // The name has the format: accounts/{account}/services/{provider}
      RejectAccountServiceRequest request =
          RejectAccountServiceRequest.newBuilder().setName(name).build();

      System.out.println("Sending Reject Account Service request:");
      accountServicesServiceClient.rejectAccountService(request);
      System.out.println("Successfully rejected account service");
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // Update this with the name of the service you want to reject (e.g. from a previous list call).
    String service = "111~222~333";
    rejectAccountService(config, service);
  }
}
// [END merchantapi_reject_account_service]