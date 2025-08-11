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

package shopping.merchant.samples.accounts.accountrelationships.v1beta;

// [START merchantapi_update_account_relationship]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1beta.AccountRelationship;
import com.google.shopping.merchant.accounts.v1beta.AccountRelationshipName;
import com.google.shopping.merchant.accounts.v1beta.AccountRelationshipsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountRelationshipsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.UpdateAccountRelationshipRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to update a business relationship. */
public class UpdateAccountRelationshipSample {

  public static void updateAccountRelationship(Config config, long providerId) throws Exception {

    GoogleCredentials credential = new Authenticator().authenticate();

    AccountRelationshipsServiceSettings accountRelationshipServiceSettings =
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

    // Create a AccountRelationship with the updated fields.
    AccountRelationship accountRelationship =
        AccountRelationship.newBuilder().setName(name).setAccountIdAlias("alias").build();

    FieldMask fieldMask = FieldMask.newBuilder().addPaths("account_id_alias").build();

    try (AccountRelationshipsServiceClient accountRelationshipServiceClient =
        AccountRelationshipsServiceClient.create(accountRelationshipServiceSettings)) {

      UpdateAccountRelationshipRequest request =
          UpdateAccountRelationshipRequest.newBuilder()
              .setAccountRelationship(accountRelationship)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending Update AccountRelationship request");
      AccountRelationship response =
          accountRelationshipServiceClient.updateAccountRelationship(request);
      System.out.println("Updated AccountRelationship below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    updateAccountRelationship(config, 111L);
  }
}
// [END merchantapi_update_account_relationship]