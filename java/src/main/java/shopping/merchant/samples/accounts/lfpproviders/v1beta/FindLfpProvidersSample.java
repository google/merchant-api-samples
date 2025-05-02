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

package shopping.merchant.samples.accounts.v1beta;

// [START merchantapi_find_lfp_providers]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.FindLfpProvidersRequest;
import com.google.shopping.merchant.accounts.v1beta.LfpProvider;
import com.google.shopping.merchant.accounts.v1beta.LfpProvidersServiceClient;
import com.google.shopping.merchant.accounts.v1beta.LfpProvidersServiceClient.FindLfpProvidersPagedResponse;
import com.google.shopping.merchant.accounts.v1beta.LfpProvidersServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.OmnichannelSettingName;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get the Lfp Providers for a given Merchant Center account */
public class FindLfpProvidersSample {

  public static void findLfpProviders(Config config, String regionCode)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    LfpProvidersServiceSettings lfpProvidersServiceSettings =
        LfpProvidersServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Gets the account ID from the config file.
    String accountId = config.getAccountId().toString();
    // Creates parent to identify the omnichannelSetting from which to list all Lfp Providers.
    String parent =
        OmnichannelSettingName.newBuilder()
            .setAccount(accountId)
            .setOmnichannelSetting(regionCode)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (LfpProvidersServiceClient lfpProvidersServiceClient =
        LfpProvidersServiceClient.create(lfpProvidersServiceSettings)) {
      FindLfpProvidersRequest request =
          FindLfpProvidersRequest.newBuilder().setParent(parent).build();

      System.out.println("Sending find LFP providers request:");
      FindLfpProvidersPagedResponse response = lfpProvidersServiceClient.findLfpProviders(request);

      int count = 0;

      // Iterates over all the entries in the response.
      for (LfpProvider lfpProvider : response.iterateAll()) {
        System.out.println(lfpProvider);
        count++;
      }
      System.out.println(String.format("The following count of elements were returned: %d", count));
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // The country you're targeting at.
    String regionCode = "{REGION_CODE}";

    findLfpProviders(config, regionCode);
  }
}
// [END merchantapi_find_lfp_providers]
