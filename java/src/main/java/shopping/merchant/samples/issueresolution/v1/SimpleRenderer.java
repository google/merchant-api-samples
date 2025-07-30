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

import com.google.shopping.merchant.issueresolution.v1.Action;
import com.google.shopping.merchant.issueresolution.v1.ActionFlow;
import com.google.shopping.merchant.issueresolution.v1.Breakdown;
import com.google.shopping.merchant.issueresolution.v1.Breakdown.Region;
import com.google.shopping.merchant.issueresolution.v1.RenderedIssue;
import com.google.shopping.merchant.issueresolution.v1.Severity;
import java.util.stream.Collectors;

/** This is a simple implementation of how to render an issue. */
public class SimpleRenderer {

  private static final String ANSI_RED_BOLD = "\u001B[31m\u001B[1m";
  private static final String ANSI_ORANGE_BOLD = "\u001B[33m\u001B[1m";
  private static final String ANSI_BLUE_BOLD = "\u001B[34m\u001B[1m";
  private static final String ANSI_RESET = "\u001B[0m";

  static void printIssue(RenderedIssue issue) {
    // Print the title of the issue.
    // Communicate the severity with a color: ERROR(RED), WARNING(ORANGE), INFO(BLUE)
    if (Severity.ERROR.equals(issue.getImpact().getSeverity())) {
      // Print the title for Error as a Red, Bold text
      System.out.println("| " + ANSI_RED_BOLD + issue.getTitle() + ANSI_RESET);
    } else if (Severity.WARNING.equals(issue.getImpact().getSeverity())) {
      // Print the title for Warning as a Orange, Bold text
      System.out.println("| " + ANSI_ORANGE_BOLD + issue.getTitle() + ANSI_RESET);
    } else {
      // Print the title for Info as a Blue, Bold text
      System.out.println("| " + ANSI_BLUE_BOLD + issue.getTitle() + ANSI_RESET);
    }

    if (!issue.getImpact().getMessageBytes().isEmpty()) {
      // Print the summarized impact of the issue
      System.out.println("| " + issue.getImpact().getMessage());

      if (!issue.getImpact().getBreakdownsList().isEmpty()) {
        // Print the detailed breakdown of the impact
        System.out.println("| breakdown:");
        for (Breakdown breakdownItem : issue.getImpact().getBreakdownsList()) {
          // Print names for all countries with the same impact
          String groupOfCountries =
              breakdownItem.getRegionsList().stream()
                  .map(Region::getName)
                  .collect(Collectors.joining(", "));
          System.out.println("|     | " + groupOfCountries);

          // Print detailed impact for this group of countries
          for (String detail : breakdownItem.getDetailsList()) {
            System.out.println("|     | - " + detail);
          }
        }
      }
    }

    // Print detailed content for this issue as pre-rendered HTML
    System.out.println("| ");
    System.out.println("| details(HTML): ");
    System.out.println("| " + issue.getPrerenderedContent());

    // Print an out-of-court dispute settlement section as pre-rendered HTML
    if (issue.hasPrerenderedOutOfCourtDisputeSettlement()) {
      System.out.println("| ");
      System.out.println("| out-of-court dispute settlement(HTML): ");
      System.out.println("| " + issue.getPrerenderedOutOfCourtDisputeSettlement());
    }

    // Display available troubleshooting actions
    if (issue.getActionsList().stream().anyMatch(Action::getIsAvailable)) {
      System.out.println("| ");
      System.out.println("| available actions: ");

      for (Action action : issue.getActionsList()) {
        StringBuilder builder = new StringBuilder();
        builder.append("| " + action.getButtonLabel());

        if (action.hasExternalAction() && !action.getExternalAction().getUri().isEmpty()) {
          // The action is a simple redirect to an external location
          builder.append(" (redirect: " + action.getExternalAction().getUri() + ")");
        } else if (action.hasBuiltinSimpleAction()) {
          // The action can be implemented as a Built-In functionality
          builder.append(" (simple Built-In functionality)");
        } else if (action.hasBuiltinUserInputAction()) {
          // The complex Built-In action with user input. It may offer multiple options (flows).
          // Different flows may require different user input and may be handled differently.
          // A dialog with user input form and additional content needs to be displayed.
          // e.g. flow 1: 'I've fixed the issue', flow 2: 'I disagree with the issue'
          String options =
              action.getBuiltinUserInputAction().getFlowsList().stream()
                  .map(ActionFlow::getLabel)
                  .map(label -> "'" + label + "'")
                  .collect(Collectors.joining(", "));
          builder.append(
              " (complex action, requires a dialog with user input form, offers different flows: "
                  + options
                  + ")");
        }
        System.out.println(builder);
      }
    }
  }
}
