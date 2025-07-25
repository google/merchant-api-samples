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

package shopping.merchant.samples.accounts.omnichannelsettings.v1beta;

// [START merchantapi_update_omnichannel_setting]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1beta.InventoryVerification;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSetting;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSettingName;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSettingsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSettingsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.UpdateOmnichannelSettingRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to update an omnichannel setting for a given Merchant Center account
 * in a given country
 */
public class UpdateOmnichannelSettingSample {

  public static void updateOmnichannelSettings(
      Config config, String regionCode, String contact, String email) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    OmnichannelSettingsServiceSettings omnichannelSettingsServiceSettings =
        OmnichannelSettingsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (OmnichannelSettingsServiceClient omnichannelSettingsServiceClient =
        OmnichannelSettingsServiceClient.create(omnichannelSettingsServiceSettings)) {
      String accountId = config.getAccountId().toString();
      String name =
          OmnichannelSettingName.newBuilder()
              .setAccount(accountId)
              .setOmnichannelSetting(regionCode)
              .build()
              .toString();

      OmnichannelSetting omnichannelSetting =
          OmnichannelSetting.newBuilder()
              .setName(name)
              .setInventoryVerification(
                  InventoryVerification.newBuilder()
                      .setContact(contact)
                      .setContactEmail(email)
                      .build())
              .build();
      FieldMask fieldMask = FieldMask.newBuilder().addPaths("inventory_verification").build();
      UpdateOmnichannelSettingRequest request =
          UpdateOmnichannelSettingRequest.newBuilder()
              .setOmnichannelSetting(omnichannelSetting)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending update omnichannel setting request:");
      OmnichannelSetting response =
          omnichannelSettingsServiceClient.updateOmnichannelSetting(request);

      System.out.println("Updated Omnichannel Setting below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // The country which you're targeting at.
    String regionCode = "{REGION_CODE}";
    // The name of the inventory verification contact you want to update.
    String contact = "{NAME}";
    // The address of the inventory verification email you want to update.
    String email = "{EMAIL}";

    updateOmnichannelSettings(config, regionCode, contact, email);
  }
}
// [END merchantapi_update_omnichannel_setting]
