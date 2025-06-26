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

// [START merchantapi_link_lfp_provider]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.LfpProvidersServiceClient;
import com.google.shopping.merchant.accounts.v1beta.LfpProvidersServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.LinkLfpProviderRequest;
import shopping.merchant.samples.utils.Authenticator;

/** This class demonstrates how to link the Lfp Providers for a given Merchant Center account */
public class LinkLfpProviderSample {

  public static void linkLfpProvider(String lfpProviderName, String externalAccountId)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    LfpProvidersServiceSettings lfpProvidersServiceSettings =
        LfpProvidersServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (LfpProvidersServiceClient lfpProvidersServiceClient =
        LfpProvidersServiceClient.create(lfpProvidersServiceSettings)) {
      LinkLfpProviderRequest request =
          LinkLfpProviderRequest.newBuilder()
              .setName(lfpProviderName)
              .setExternalAccountId(externalAccountId)
              .build();

      System.out.println("Sending link lfp provider request:");
      // Empty response returned on success.
      lfpProvidersServiceClient.linkLfpProvider(request);
      System.out.println(String.format("Successfully linked to LFP provider: %s", lfpProviderName));
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    // The name of the lfp provider you want to link, returned from `lfpProviders.findLfpProviders`.
    // It's of the form
    // "accounts/{account_id}/omnichannelSettings/{omnichannel_settings}/lfpProviders/{lfp_provider}".
    String lfpProviderName = "{LFP_PROVIDER_NAME}";
    // External account id by which this merchant is known to the LFP provider.
    String externalAccountId = "{EXTERNAL_ACCOUNT_ID}";

    linkLfpProvider(lfpProviderName, externalAccountId);
  }
}
// [END merchantapi_link_lfp_provider]
