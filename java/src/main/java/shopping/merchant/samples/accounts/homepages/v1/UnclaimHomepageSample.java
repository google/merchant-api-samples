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

package shopping.merchant.samples.accounts.homepages.v1;
// [START merchantapi_unclaim_homepage]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.Homepage;
import com.google.shopping.merchant.accounts.v1.HomepageName;
import com.google.shopping.merchant.accounts.v1.HomepageServiceClient;
import com.google.shopping.merchant.accounts.v1.HomepageServiceSettings;
import com.google.shopping.merchant.accounts.v1.UnclaimHomepageRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to unclaim the homepage for a given Merchant Center account. */
public class UnclaimHomepageSample {

  // Executing this method requires admin access.
  public static void unclaimHomepage(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    HomepageServiceSettings homepageServiceSettings =
        HomepageServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates Homepage name to identify Homepage.
    // The name has the format: accounts/{account}/homepage
    String name =
        HomepageName.newBuilder().setAccount(config.getAccountId().toString()).build().toString();

    // Calls the API and catches and prints any network failures/errors.
    try (HomepageServiceClient homepageServiceClient =
        HomepageServiceClient.create(homepageServiceSettings)) {

      UnclaimHomepageRequest request = UnclaimHomepageRequest.newBuilder().setName(name).build();

      System.out.println("Sending Unclaim Homepage request:");

      Homepage response = homepageServiceClient.unclaimHomepage(request);

      System.out.println("Retrieved Homepage below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    unclaimHomepage(config);
  }
}
// [END merchantapi_unclaim_homepage]