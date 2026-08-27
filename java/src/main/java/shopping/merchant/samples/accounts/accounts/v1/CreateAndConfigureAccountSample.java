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

// [START merchantapi_create_and_configure_account]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccessRight;
import com.google.shopping.merchant.accounts.v1.Account;
import com.google.shopping.merchant.accounts.v1.AccountManagement;
import com.google.shopping.merchant.accounts.v1.AccountsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountsServiceSettings;
import com.google.shopping.merchant.accounts.v1.CreateAndConfigureAccountRequest;
import com.google.shopping.merchant.accounts.v1.CreateAndConfigureAccountRequest.AddAccountService;
import com.google.shopping.merchant.accounts.v1.CreateAndConfigureAccountRequest.AddUser;
import com.google.shopping.merchant.accounts.v1.ProductsManagement;
import com.google.shopping.merchant.accounts.v1.User;
import com.google.type.TimeZone;
import java.util.List;
import shopping.merchant.samples.utils.Authenticator;

/**
 * This class demonstrates how to create a new Merchant Center account and configure it with various
 * services.
 */
public class CreateAndConfigureAccountSample {

  public static void createAndConfigureAccount(
      long providerId, String newAccountName, String userEmail) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountsServiceSettings accountsServiceSettings =
        AccountsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountsServiceClient accountsServiceClient =
        AccountsServiceClient.create(accountsServiceSettings)) {

      // The account to be created.
      Account account =
          Account.newBuilder()
              .setAccountName(newAccountName)
              .setTimeZone(TimeZone.newBuilder().setId("Europe/Zurich").build())
              .setLanguageCode("en-US")
              .build();

      // The services to be added to the new account.
      // This sample shows how to configure a new account with account management and
      // product management.
      // For more information about the different services, see:
      // https://developers.google.com/merchant/api/guides/accounts/services
      AddAccountService accountManagementService =
          AddAccountService.newBuilder()
              .setProvider("accounts/" + providerId)
              .setExternalAccountId("external_account_id")
              .setAccountManagement(AccountManagement.newBuilder().build())
              .build();
      // This part is optional. You can also omit this and link the account later.
      AddAccountService productsManagementService =
          AddAccountService.newBuilder()
              .setProvider("accounts/" + providerId)
              .setExternalAccountId("external_account_id")
              .setProductsManagement(ProductsManagement.newBuilder().build())
              .build();

      // For sub-account creation, use the AccountAggregation service. For an example on how to do
      // this, see the `CreateSubAccount` sample.

      // The user to be added to the new account.
      // This part is optional.
      AddUser user =
          AddUser.newBuilder()
              .setUserId(userEmail)
              .setUser(User.newBuilder().addAccessRights(AccessRight.STANDARD).build())
              .build();

      CreateAndConfigureAccountRequest request =
          CreateAndConfigureAccountRequest.newBuilder()
              .setAccount(account)
              .addAllService(List.of(accountManagementService, productsManagementService))
              .addUser(user)
              .build();

      System.out.println("Sending Create and Configure Account request:");
      Account response = accountsServiceClient.createAndConfigureAccount(request);

      System.out.println("Created Account below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    // This is the provider ID of the e-commerce platform.
    long providerId = 123L;
    // This is the name of the new account to be created.
    String newAccountName = "MyNewShop";
    // This is the email of a user to be added to the new account.
    String userEmail = "test-user@gmail.com";
    createAndConfigureAccount(providerId, newAccountName, userEmail);
  }
}
// [END merchantapi_create_and_configure_account]
