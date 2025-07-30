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

package shopping.merchant.samples.accounts.termsofservices.v1;
// [START merchantapi_accept_termsofservice]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.AcceptTermsOfServiceRequest;
import com.google.shopping.merchant.accounts.v1.TermsOfServiceServiceClient;
import com.google.shopping.merchant.accounts.v1.TermsOfServiceServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to accept the TermsOfService agreement in a given account. */
public class AcceptTermsOfServiceSample {

  public static void acceptTermsOfService(String accountId, String tosVersion, String regionCode)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    TermsOfServiceServiceSettings tosServiceSettings =
        TermsOfServiceServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (TermsOfServiceServiceClient tosServiceClient =
        TermsOfServiceServiceClient.create(tosServiceSettings)) {

      // The parent has the format: accounts/{account}
      AcceptTermsOfServiceRequest request =
          AcceptTermsOfServiceRequest.newBuilder()
              .setName(String.format("termsOfService/%s", tosVersion))
              .setAccount(String.format("accounts/%s", accountId))
              .setRegionCode(regionCode)
              .build();

      System.out.println("Sending request to accept terms of service...");
      tosServiceClient.acceptTermsOfService(request);

      System.out.println("Successfully accepted terms of service.");
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // See GetTermsOfServiceAgreementStateSample to understand how to check which version of the
    // terms of service needs to be accepted, if any.
    // Likewise, if you know that the terms of service needs to be accepted, you can also simply
    // call RetrieveLatestTermsOfService to get the latest version of the terms of service.
    // Region code is either a country when the ToS applies specifically to that country or 001 when
    // it applies globally.
    acceptTermsOfService(config.getAccountId().toString(), "VERSION_HERE", "REGION_CODE_HERE");
  }
}
// [END merchantapi_accept_termsofservice]