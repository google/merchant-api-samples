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
// [START merchantapi_disable_program]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1beta.DisableProgramRequest;
import com.google.shopping.merchant.accounts.v1beta.Program;
import com.google.shopping.merchant.accounts.v1beta.ProgramName;
import com.google.shopping.merchant.accounts.v1beta.ProgramsServiceClient;
import com.google.shopping.merchant.accounts.v1beta.ProgramsServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to disable a shopping program for a Merchant Center account. */
public class DisableProgramSample {

  public static void disableProgram(Config config, String program) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    ProgramsServiceSettings programsServiceSettings =
        ProgramsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates program name to identify the program.
    String name =
        ProgramName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setProgram(program)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (ProgramsServiceClient programsServiceClient =
        ProgramsServiceClient.create(programsServiceSettings)) {

      // The name has the format: accounts/{account}/programs/{program}
      DisableProgramRequest request = DisableProgramRequest.newBuilder().setName(name).build();

      System.out.println("Sending Disable Program request:");
      Program response = programsServiceClient.disableProgram(request);

      System.out.println("Disabled Program below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    // Replace this with the name of the program to be disabled.
    String program = "free-listings";

    disableProgram(config, program);
  }
}
// [END merchantapi_disable_program]
