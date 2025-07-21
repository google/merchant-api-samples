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

// [START merchantapi_list_account_issues]
/**
 * Lists all issues for a given Merchant Center account.
 */
function listAccountIssues() {
  // IMPORTANT:
  // Enable the Merchant API Accounts sub-API Advanced Service and call it
  // "MerchantApiAccounts"

  // Replace this with your Merchant Center ID.
  const accountId = "<MERCHANT_CENTER_ID>";

  // Construct the parent name
  const parent = 'accounts/' + accountId;

  try {
    console.log('Sending list Account Issues request');
    // Set pageSize to the maximum value (default: 50)
    let pageSize = 100;
    let pageToken;
    let count = 0;
    // Call the Account.Issues.list API method. Use the pageToken to iterate
    // through all pages of results.
    do {
      response = MerchantApiAccounts.Accounts.Issues.list(parent, {pageSize, pageToken});
      for (const issue of response.accountIssues) {
        console.log(issue);
        count++;
      }
      pageToken = response.nextPageToken;
    } while (pageToken);  // Exits when there is no next page token.
    console.log('The following count of Account Issues were returned: ', count);
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_list_account_issues]
