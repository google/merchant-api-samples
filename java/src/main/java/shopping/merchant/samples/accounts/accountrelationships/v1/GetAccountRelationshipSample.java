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

package shopping.merchant.samples.accounts.accountrelationships.v1;

// [START merchantapi_get_account_relationship]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AccountRelationship;
import com.google.shopping.merchant.accounts.v1.AccountRelationshipName;
import com.google.shopping.merchant.accounts.v1.AccountRelationshipsServiceClient;
import com.google.shopping.merchant.accounts.v1.AccountRelationshipsServiceSettings;
import com.google.shopping.merchant.accounts.v1.GetAccountRelationshipRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get an account relationship. */
public class GetAccountRelationshipSample {

  public static void getAccountRelationship(Config config, long providerId) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AccountRelationshipsServiceSettings accountRelationshipsServiceSettings =
        AccountRelationshipsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Gets the account ID from the config file.
    String accountId = config.getAccountId().toString();

    // Creates account relationship name to identify the account relationship.
    String name =
        AccountRelationshipName.newBuilder()
            .setAccount(accountId)
            .setRelationship(String.valueOf(providerId))
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (AccountRelationshipsServiceClient accountRelationshipsServiceClient =
        AccountRelationshipsServiceClient.create(accountRelationshipsServiceSettings)) {

      // The name has the format: accounts/{account}/relationships/{provider}
      GetAccountRelationshipRequest request =
          GetAccountRelationshipRequest.newBuilder().setName(name).build();

      System.out.println("Sending Get Account Relationship request:");
      AccountRelationship response =
          accountRelationshipsServiceClient.getAccountRelationship(request);

      System.out.println("Retrieved Account Relationship below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // Update this with the provider ID you want to get the relationship for.
    long providerId = 111L;
    getAccountRelationship(config, providerId);
  }
}
// [END merchantapi_get_account_relationship]