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

// [START merchantapi_create_omnichannel_setting]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountName;
import com.google.shopping.merchant.accounts.v1beta.CreateOmnichannelSettingRequest;
import com.google.shopping.merchant.accounts.v1beta.InStock;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSetting;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSetting.LsfType;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSettingsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSettingsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to create an omnichannel setting for a given Merchant Center account
 * in a given country
 */
public class CreateOmnichannelSettingSample {

  public static void createOmnichannelSetting(Config config, String regionCode) throws Exception {

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
      String parent = AccountName.newBuilder().setAccount(accountId).build().toString();

      // Creates an omnichannel setting with GHLSF type in the given country.
      CreateOmnichannelSettingRequest request =
          CreateOmnichannelSettingRequest.newBuilder()
              .setParent(parent)
              .setOmnichannelSetting(
                  OmnichannelSetting.newBuilder()
                      .setRegionCode(regionCode)
                      .setLsfType(LsfType.GHLSF)
                      .setInStock(InStock.getDefaultInstance())
                      .build())
              .build();

      System.out.println("Sending create omnichannel setting request:");
      OmnichannelSetting response =
          omnichannelSettingsServiceClient.createOmnichannelSetting(request);

      System.out.println("Inserted Omnichannel Setting below:");
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

    createOmnichannelSetting(config, regionCode);
  }
}
// [END merchantapi_create_omnichannel_setting]
