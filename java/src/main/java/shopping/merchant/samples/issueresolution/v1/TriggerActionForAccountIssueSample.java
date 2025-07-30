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
package shopping.merchant.samples.issueresolution.v1;

// [START merchantapi_trigger_action_for_account_issue]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.issueresolution.v1.AccountName;
import com.google.shopping.merchant.issueresolution.v1.Action;
import com.google.shopping.merchant.issueresolution.v1.ActionFlow;
import com.google.shopping.merchant.issueresolution.v1.ActionInput;
import com.google.shopping.merchant.issueresolution.v1.BuiltInUserInputAction;
import com.google.shopping.merchant.issueresolution.v1.InputField;
import com.google.shopping.merchant.issueresolution.v1.InputField.ChoiceInput;
import com.google.shopping.merchant.issueresolution.v1.InputField.ChoiceInput.ChoiceInputOption;
import com.google.shopping.merchant.issueresolution.v1.InputValue;
import com.google.shopping.merchant.issueresolution.v1.InputValue.CheckboxInputValue;
import com.google.shopping.merchant.issueresolution.v1.InputValue.ChoiceInputValue;
import com.google.shopping.merchant.issueresolution.v1.InputValue.TextInputValue;
import com.google.shopping.merchant.issueresolution.v1.IssueResolutionServiceClient;
import com.google.shopping.merchant.issueresolution.v1.IssueResolutionServiceSettings;
import com.google.shopping.merchant.issueresolution.v1.RenderAccountIssuesRequest;
import com.google.shopping.merchant.issueresolution.v1.RenderAccountIssuesResponse;
import com.google.shopping.merchant.issueresolution.v1.RenderIssuesRequestPayload;
import com.google.shopping.merchant.issueresolution.v1.RenderedIssue;
import com.google.shopping.merchant.issueresolution.v1.TriggerActionPayload;
import com.google.shopping.merchant.issueresolution.v1.TriggerActionRequest;
import com.google.shopping.merchant.issueresolution.v1.TriggerActionResponse;
import com.google.shopping.merchant.issueresolution.v1.UserInputActionRenderingOption;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.stream.Collectors;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to trigger an action on account-level issues for a given Merchant
 * Center account.
 *
 * <p>The example first calls the `renderAccountIssues` to obtain live issues for the account. The
 * user can select the issue, they want to trigger an action for. The program will ask them to
 * provide all required user input, specific for the selected action. Once the input is collected,
 * the program build the request and calls the `triggerAction`
 *
 * <p>NOTE: the access to `triggerAction` is currently limited. To obtain access follow steps in the
 * development guide.
 */
public class TriggerActionForAccountIssueSample {

  private static void renderAccountIssuesAndTriggerAction(
      Config config,
      String languageCode,
      String timeZone,
      UserInputActionRenderingOption userInputActionOption,
      Scanner scanner)
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

      // First, we call the `renderAccountIssues` to obtain live issues for the account. The
      // `userInputActionOption` specifies how the complex Built-In actions should be rendered. For
      // this example, the option is set to `REDIRECT_TO_MERCHANT_CENTER`, so the
      // complex actions will be rendered as `BuiltInUserInputAction` that can be started by calling
      // the `triggerAction` method.
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
      System.out.println("Summary: ");
      System.out.println(response.getRenderedIssuesCount() + " issues found for the account");

      // Filter all issues that have at least one action that is available and has a built-in user
      // input action.
      List<RenderedIssue> issuesWithTriggerableAction =
          response.getRenderedIssuesList().stream()
              .filter(
                  issue ->
                      issue.getActionsList().stream()
                          .anyMatch(
                              action ->
                                  action.getIsAvailable() && action.hasBuiltinUserInputAction()))
              .collect(Collectors.toList());

      if (issuesWithTriggerableAction.isEmpty()) {
        System.out.println("There is currently no issue that has a triggerable action");
        return; // Early exit as there is no action to be triggered.
      }

      System.out.println(
          issuesWithTriggerableAction.size()
              + " issues have an action that could be started with the `triggerAction` method:");

      // The user should select the issue for which they will trigger an action
      for (int i = 0; i < issuesWithTriggerableAction.size(); i++) {
        System.out.println("  [" + i + "] " + issuesWithTriggerableAction.get(i).getTitle());
      }
      int selectedIssueId =
          requestUserInputAsNumber(0, issuesWithTriggerableAction.size() - 1, "issue", scanner);
      RenderedIssue issue = issuesWithTriggerableAction.get(selectedIssueId);
      System.out.println("You selected '" + issue.getTitle() + "' issue.");

      List<Action> actions =
          issue.getActionsList().stream()
              .filter(it -> it.getIsAvailable() && it.hasBuiltinUserInputAction())
              .collect(Collectors.toList());

      // There could be multiple actions that can be triggered for a given issue. The user should
      // select the action they want to trigger.
      Action action;
      if (actions.size() == 1) {
        action = actions.get(0);
        System.out.println(
            "There is only one action '" + action.getButtonLabel() + "' that can be triggered.");
      } else {
        for (int i = 0; i < actions.size(); i++) {
          System.out.println("  [" + i + "] " + actions.get(i).getButtonLabel());
        }
        int selectedActionId = requestUserInputAsNumber(0, actions.size() - 1, "action", scanner);
        action = actions.get(selectedActionId);
        System.out.println("You selected '" + action.getButtonLabel() + "' action.");
      }

      BuiltInUserInputAction triggerableAction = action.getBuiltinUserInputAction();

      // There could be multiple action flows for a given action. The user should select the
      // specific action flow they want to trigger.
      ActionFlow actionFlow;
      if (triggerableAction.getFlowsList().size() > 1) {
        System.out.println(
            "For the '"
                + action.getButtonLabel()
                + "' there are "
                + triggerableAction.getFlowsCount()
                + " flows available:");
        for (int i = 0; i < triggerableAction.getFlowsCount(); i++) {
          System.out.println("  [" + i + "] " + triggerableAction.getFlowsList().get(i).getLabel());
        }
        int selectedFlowId =
            requestUserInputAsNumber(0, triggerableAction.getFlowsCount() - 1, "flow", scanner);
        actionFlow = triggerableAction.getFlows(selectedFlowId);
        System.out.println("You selected '" + actionFlow.getLabel() + "' flow");
      } else {
        actionFlow = triggerableAction.getFlowsList().get(0);
      }

      // For each flow, additional content needs to be shown to the user. There should be a dialog
      // with user input form and additional content. The content is defined by the
      // `dialog_title`, `dialog_callout` and `dialog_message` fields.
      System.out.println();
      System.out.println("Additional content to display to the merchant (as a dialog):");
      System.out.println(actionFlow.getDialogTitle());
      if (actionFlow.hasDialogCallout()) {
        System.out.println(actionFlow.getDialogCallout().getFullMessage().getSimpleValue());
      }
      System.out.println(actionFlow.getDialogMessage().getSimpleValue());

      // Collect user input values, that may be required to trigger the action (flow). This would be
      // displayed as a form to the user.
      List<InputValue> inputValues = new ArrayList<>();
      if (actionFlow.getInputsCount() > 0) {
        System.out.println();
        System.out.println("User input form:");

        for (InputField field : actionFlow.getInputsList()) {
          System.out.print("field [" + field.getId() + "]: " + field.getLabel().getSimpleValue());
          if (field.getRequired()) {
            System.out.print(" (required)");
          }
          // checkbox field
          if (field.hasCheckboxInput()) {
            // The user should confirm the checkbox.
            System.out.println(" [checkbox]");
            System.out.print(" Enter 'true' to confirm: ");
            String input = scanner.nextLine();
            if ("true".equals(input.toLowerCase())) {
              inputValues.add(
                  InputValue.newBuilder()
                      .setInputFieldId(field.getId())
                      .setCheckboxInputValue(CheckboxInputValue.newBuilder().setValue(true).build())
                      .build());
            } else {
              System.out.println("Wrong value");
              if (field.getRequired()) {
                System.out.println("The action can not be triggered without a required value");
                return;
              }
            }
            // select field
          } else if (field.hasChoiceInput()) {
            // For the ChoiceInput, there are multiple predefined options. The user should select
            // one of the predefined options.
            System.out.println(" [select]");
            ChoiceInput choiceInput = field.getChoiceInput();
            for (int i = 0; i < choiceInput.getOptionsCount(); i++) {
              System.out.println(
                  "  ["
                      + i
                      + "] "
                      + choiceInput.getOptions(i).getLabel().getSimpleValue()
                      + " ["
                      + choiceInput.getOptions(i).getId()
                      + "]");
            }
            int selectedOptionId =
                requestUserInputAsNumber(0, choiceInput.getOptionsCount() - 1, "option", scanner);
            ChoiceInputOption selectedOption = choiceInput.getOptions(selectedOptionId);
            System.out.println("You selected '" + selectedOption.getLabel().getSimpleValue() + "'");

            inputValues.add(
                InputValue.newBuilder()
                    .setInputFieldId(field.getId())
                    .setChoiceInputValue(
                        ChoiceInputValue.newBuilder()
                            .setChoiceInputOptionId(selectedOption.getId())
                            .build())
                    .build());
            // text input field
          } else if (field.hasTextInput()) {
            // For the TextInput, the user should enter the text.
            System.out.println(" [text]");
            System.out.print(" Enter the text: ");
            String input = scanner.nextLine();

            inputValues.add(
                InputValue.newBuilder()
                    .setInputFieldId(field.getId())
                    .setTextInputValue(TextInputValue.newBuilder().setValue(input).build())
                    .build());
          }
          System.out.println();
        }
      }

      // Build the request to trigger the action.
      TriggerActionRequest triggerActionRequest =
          TriggerActionRequest.newBuilder()
              .setLanguageCode(languageCode)
              .setName(name)
              .setPayload(
                  TriggerActionPayload.newBuilder()
                      // set the action context for selected action
                      .setActionContext(triggerableAction.getActionContext())
                      // set the action inputs
                      .setActionInput(
                          ActionInput.newBuilder()
                              // set the FlowId for the selected action flow
                              .setActionFlowId(actionFlow.getId())
                              // set user input for all (required) user input fields
                              .addAllInputValues(inputValues)
                              .build())
                      .build())
              .build();

      System.out.println("-----------------------------------------------------------------");
      System.out.println("Calling `triggerAction` with request: ");
      System.out.println(triggerActionRequest);
      TriggerActionResponse triggerActionResponse = client.triggerAction(triggerActionRequest);

      System.out.println("The full response:");
      System.out.println(triggerActionResponse);

    } catch (Exception e) {
      System.out.println("An error has occured: ");
      System.out.println(e);

      if (e.getMessage()
          .contains(
              "PERMISSION_DENIED: This API endpoint is not enabled for your cloud project id.")) {
        System.out.println("The access to the `triggerAction` method is currently limited.");
        System.out.println(
            "To get the access, you need to first, submit a request. A link to the form can be"
                + " found in the development guide");
      }
    }
  }

  private static int requestUserInputAsNumber(int min, int max, String itemName, Scanner scanner) {
    while (true) {
      System.out.print(
          "Enter a number from <" + min + "-" + max + "> to select the " + itemName + ": ");
      try {
        int value = scanner.nextInt();
        if (value >= min && value <= max) {
          scanner.nextLine();
          return value;
        } else {
          System.out.println("Invalid input.");
        }
      } catch (java.util.InputMismatchException e) {
        System.out.println("Invalid input. Please enter a valid number.");
        scanner.nextLine(); // Clear the invalid input from the scanner
      }
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    String timeZone = "Europe/Zurich";
    String languageCode = "en_GB";
    // To implement complex troubleshooting actions directly in your app,
    // the RenderAccountIssuesRequest must have set the `userInputActionOption` as
    // `BUILT_IN_USER_INPUT_ACTIONS`. Otherwise, these actions would be handled as redirects to
    // the Merchant Center (by default).
    UserInputActionRenderingOption inputActionOption =
        UserInputActionRenderingOption.BUILT_IN_USER_INPUT_ACTIONS;

    try (Scanner scanner = new Scanner(System.in)) {
      renderAccountIssuesAndTriggerAction(
          config, languageCode, timeZone, inputActionOption, scanner);
    }
  }
}
// [END merchantapi_trigger_action_for_account_issue]
