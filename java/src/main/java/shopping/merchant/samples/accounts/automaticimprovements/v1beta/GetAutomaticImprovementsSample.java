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

package shopping.merchant.samples.accounts.automaticimprovements.v1beta;
// [START merchantapi_get_automatic_improvements]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovements;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovementsName;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovementsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AutomaticImprovementsServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.GetAutomaticImprovementsRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to get the automatic improvements of a Merchant Center account. */
public class GetAutomaticImprovementsSample {

  public static void getAutomaticImprovements(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    AutomaticImprovementsServiceSettings automaticImprovementsServiceSettings =
        AutomaticImprovementsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates AutomaticImprovements name to identify the AutomaticImprovements.
    String name =
        AutomaticImprovementsName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (AutomaticImprovementsServiceClient automaticImprovementsServiceClient =
        AutomaticImprovementsServiceClient.create(automaticImprovementsServiceSettings)) {

      // The name has the format: accounts/{account}/automaticImprovements
      GetAutomaticImprovementsRequest request =
          GetAutomaticImprovementsRequest.newBuilder().setName(name).build();

      System.out.println("Sending get AutomaticImprovements request:");
      AutomaticImprovements response =
          automaticImprovementsServiceClient.getAutomaticImprovements(request);

      System.out.println("Retrieved AutomaticImprovements below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    getAutomaticImprovements(config);
  }
}
// [END merchantapi_get_automatic_improvements]