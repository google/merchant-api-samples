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

// [START merchantapi_filter_accounts]

/**
 * Filters and lists accounts for which the logged-in user has access to
 */
function filterAccounts() {
  // IMPORTANT:
  // Enable the Merchant API Accounts Bundle Advanced Service and call it
  // "MerchantApiAccounts"

  // Create the filter string.
  // Documentation can be found at
  // https://developers.google.com/merchant/api/guides/accounts/filter-syntax
  const filter = 'accountName = "*store*" AND relationship(providerId = 123)';
  try {
    console.log('Sending filter Accounts request');
    let pageToken;
    let pageSize = 500;
    // Call the Accounts.list API method with a filter. Use the pageToken to iterate through
    // all pages of results.
    do {
      response =
          MerchantApiAccounts.Accounts.list({pageSize, pageToken, filter});
      for (const account of response.accounts) {
        console.log(account);
      }
      pageToken = response.nextPageToken;
    } while (pageToken);  // Exits when there is no next page token.

  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_filter_accounts]