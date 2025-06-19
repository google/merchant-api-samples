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

// [START merchantapi_list_products]

/**
 * Lists all products for a given Merchant Center account.
 */
function productList() {
  // IMPORTANT:
  // Enable the Merchant API Products Bundle Advanced Service and call it
  // "MerchantApiProducts"

  // Replace this with your Merchant Center ID.
  const accountId = '<MERCHANT_CENTER_ID>';

  // Construct the parent name
  const parent = 'accounts/' + accountId;

  try {
    console.log('Sending list Products request');
    let pageToken;
    // Set the page size to 1000. This is the maximum allowed page size.
    let pageSize = 1000;

    console.log('Retrieved products below:');
    // Call the Products.list API method. Use the pageToken to iterate through
    // all pages of results.
    do {
      response = MerchantApiProducts.Accounts.Products.list(parent, {pageToken, pageSize});
      console.log(response);
      pageToken = response.nextPageToken;
    } while (pageToken); // Exits when there is no next page token.
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_list_products]