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

package shopping.merchant.samples.accounts.accounts.v1;

// [START merchantapi_propose_account_service]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccountName;
import com.google.shopping.merchant.accounts.v1.AccountService;
import com.google.shopping.merchant.accounts.v1.AccountServicesServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountServicesServiceSettings;
import com.google.shopping.merchant.accounts.v1.ProductsManagement;
import com.google.shopping.merchant.accounts.v1.ProposeAccountServiceRequest;
import shopping.merchant.samples.utils.Authenticator;

/** This class demonstrates how to propose a service to an existing Merchant Center account. */
public class ProposeServiceSample {

  public static void proposeService(long accountId, long providerId, String externalAccountId)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    // The user that authenticates should have access to the account.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountServicesServiceSettings accountServicesServiceSettings =
        AccountServicesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountServicesServiceClient accountServicesServiceClient =
        AccountServicesServiceClient.create(accountServicesServiceSettings)) {

      // The service to be proposed.
      // This sample shows how to propose product management.
      // For more information about the different services, see:
      // https://developers.google.com/merchant/api/guides/accounts/services
      AccountService accountService =
          AccountService.newBuilder()
              .setProductsManagement(ProductsManagement.newBuilder().build())
              .setExternalAccountId(externalAccountId)
              .build();

      String accountName =
          AccountName.newBuilder().setAccount(String.valueOf(accountId)).build().toString();

      ProposeAccountServiceRequest request =
          ProposeAccountServiceRequest.newBuilder()
              .setParent(accountName)
              .setProvider("accounts/" + providerId)
              .setAccountService(accountService)
              .build();

      System.out.println("Sending Propose Service request:");
      AccountService response = accountServicesServiceClient.proposeAccountService(request);

      System.out.println("Proposed Service below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    // The ID of the account to propose the service to.
    long accountId = 123L;
    // This is the provider ID of the e-commerce platform.
    long providerId = 456L;
    // An external ID that uniquely identifies the account service.
    String externalAccountId = "ext-acc-id-123";
    proposeService(accountId, providerId, externalAccountId);
  }
}
// [END merchantapi_propose_account_service]
