// Copyright 2025 Google LLC
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

package shopping.merchant.samples.issueresolution.v1beta;

// [START merchantapi_render_account_issues]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.issueresolution.v1beta.AccountName;
import com.google.shopping.merchant.issueresolution.v1beta.IssueResolutionServiceClient;
import com.google.shopping.merchant.issueresolution.v1beta.IssueResolutionServiceSettings;
import com.google.shopping.merchant.issueresolution.v1beta.RenderAccountIssuesRequest;
import com.google.shopping.merchant.issueresolution.v1beta.RenderAccountIssuesResponse;
import com.google.shopping.merchant.issueresolution.v1beta.RenderIssuesRequestPayload;
import com.google.shopping.merchant.issueresolution.v1beta.RenderedIssue;
import com.google.shopping.merchant.issueresolution.v1beta.UserInputActionRenderingOption;
import java.io.IOException;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to render account issues for a given Merchant Center account */
public class RenderAccountIssuesSample {

  private static void renderAccountIssuesSample(
      Config config,
      String languageCode,
      String timeZone,
      UserInputActionRenderingOption userInputActionOption)
      throws IOException {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    IssueResolutionServiceSettings settings =
        IssueResolutionServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    String accountId = config.getAccountId().toString();
    String name = AccountName.newBuilder().setAccount(accountId).build().toString();

    try (IssueResolutionServiceClient client = IssueResolutionServiceClient.create(settings)) {

      RenderAccountIssuesRequest request =
          RenderAccountIssuesRequest.newBuilder()
              .setName(name)
              .setLanguageCode(languageCode)
              .setTimeZone(timeZone)
              .setPayload(
                  RenderIssuesRequestPayload.newBuilder()
                      .setUserInputActionOption(userInputActionOption)
                      .build())
              .build();

      System.out.println("Sending RenderAccountIssues request");

      RenderAccountIssuesResponse response = client.renderAccountIssues(request);

      System.out.println("The full response:");
      System.out.println(response);

      System.out.println("-----------------------------------------------------------------");
      System.out.println(
          "Summary: " + response.getRenderedIssuesCount() + " issues found for the account");
      System.out.println("-----------------------------------------------------------------");

      for (RenderedIssue issue : response.getRenderedIssuesList()) {
        System.out.println(); // empty line for formatting
        SimpleRenderer.printIssue(issue);
      }
    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    String timeZone = "Europe/Zurich";
    String languageCode = "en_GB";
    // The simple option: request all complex actions to be handled as redirects to
    // the Merchant Center. e.g. send the merchant to MC to request an appeal.
    UserInputActionRenderingOption inputActionOption =
        UserInputActionRenderingOption.REDIRECT_TO_MERCHANT_CENTER;

    renderAccountIssuesSample(config, languageCode, timeZone, inputActionOption);
  }
}
// [END merchantapi_render_account_issues]