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

package shopping.merchant.samples.accounts.programs.v1beta;
// [START merchantapi_list_programs]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.AccountName;
import com.google.shopping.merchant.accounts.v1beta.ListProgramsRequest;
import com.google.shopping.merchant.accounts.v1beta.Program;
import com.google.shopping.merchant.accounts.v1beta.ProgramsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.ProgramsServiceClient.ListProgramsPagedResponse;
import com.google.shopping.merchant.accounts.v1beta.ProgramsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to list all shopping program resources for a Merchant Center account.
 */
public class ListProgramsSample {

  public static void listPrograms(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    ProgramsServiceSettings programsServiceSettings =
        ProgramsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates parent to identify the account for which to list programs.
    String parent = AccountName.of(config.getAccountId().toString()).toString();

    // Calls the API and catches and prints any network failures/errors.
    try (ProgramsServiceClient programsServiceClient =
        ProgramsServiceClient.create(programsServiceSettings)) {

      ListProgramsRequest request = ListProgramsRequest.newBuilder().setParent(parent).build();

      System.out.println("Sending List Programs request:");
      ListProgramsPagedResponse response = programsServiceClient.listPrograms(request);

      int count = 0;

      // Iterates over all programs in all pages and prints each program.
      // Automatically uses the `nextPageToken`, if returned, to fetch all pages.
      for (Program program : response.iterateAll()) {
        System.out.println(program);
        count++;
      }
      System.out.print("The count of Programs returned: ");
      System.out.println(count);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    listPrograms(config);
  }
}
// [END merchantapi_list_programs]
