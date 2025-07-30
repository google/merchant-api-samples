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

package shopping.merchant.samples.accounts.businessidentities.v1;
// [START merchantapi_update_business_identity]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1.BusinessIdentity;
import com.google.shopping.merchant.accounts.v1.BusinessIdentityName;
import com.google.shopping.merchant.accounts.v1.BusinessIdentityServiceClient;
import com.google.shopping.merchant.accounts.v1.BusinessIdentityServiceSettings;
import com.google.shopping.merchant.accounts.v1.UpdateBusinessIdentityRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to update a business identity. */
public class UpdateBusinessIdentitySample {

  public static void updateBusinessIdentity(Config config) throws Exception {

    GoogleCredentials credential = new Authenticator().authenticate();

    BusinessIdentityServiceSettings businessIdentityServiceSettings =
        BusinessIdentityServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates BusinessIdentity name to identify BusinessIdentity.
    String name =
        BusinessIdentityName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .build()
            .toString();

    // Create a BusinessIdentity with the updated fields.
    BusinessIdentity businessIdentity =
        BusinessIdentity.newBuilder()
            .setName(name)
            .setSmallBusiness(
                BusinessIdentity.IdentityAttribute.newBuilder()
                    .setIdentityDeclaration(
                        BusinessIdentity.IdentityAttribute.IdentityDeclaration.SELF_IDENTIFIES_AS)
                    .build())
            .build();

    FieldMask fieldMask = FieldMask.newBuilder().addPaths("small_business").build();

    try (BusinessIdentityServiceClient businessIdentityServiceClient =
        BusinessIdentityServiceClient.create(businessIdentityServiceSettings)) {

      UpdateBusinessIdentityRequest request =
          UpdateBusinessIdentityRequest.newBuilder()
              .setBusinessIdentity(businessIdentity)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending Update BusinessIdentity request");
      BusinessIdentity response = businessIdentityServiceClient.updateBusinessIdentity(request);
      System.out.println("Updated BusinessIdentity below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    updateBusinessIdentity(config);
  }
}
// [END merchantapi_update_business_identity]