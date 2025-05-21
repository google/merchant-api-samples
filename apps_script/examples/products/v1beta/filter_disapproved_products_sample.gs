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

// [START merchantapi_filter_disapproved_products]

/**
 * Demonstrates how to filter disapproved products using the Merchant API Reports service.
 */
function filterDisapprovedProducts() {
  // IMPORTANT:
  // Enable the Merchant API Reports Bundle Advanced Service and call it
  // "MerchantApiReports"
  // Enable the Merchant API Products Bundle Advanced Service and call it
  // "MerchantApiProducts"

  // Replace this with your Merchant Center ID.
  const accountId = '<INSERT_MERCHANT_CENTER_ID>';

  // Construct the parent name
  const parent = 'accounts/' + accountId;

  try {
    console.log('Sending search Report request');
    // Set pageSize to the maximum value (default: 1000)
    let pageSize = 1000;
    let pageToken;
    // The query below is an example of a query for the productView that gets product informations
    // for all disapproved products.
    let query = 'SELECT offer_id,' +
        'id,' +
        'price,' +
        'title' +
        ' FROM product_view' +
        ' WHERE aggregated_reporting_context_status = "NOT_ELIGIBLE_OR_DISAPPROVED"';


    // Call the Reports.search API method. Use the pageToken to iterate through
    // all pages of results.
    do {
      response =
          MerchantApiReports.Accounts.Reports.search({query, pageSize, pageToken}, parent);
      for (const reportRow of response.results) {
        console.log("Printing data from Product View:");
        console.log(reportRow);

        // OPTIONALLY, you can get the full product details by calling the GetProduct method.
        let productName = parent + "/products/" + reportRow.getProductView().getId();
        product = MerchantApiProducts.Accounts.Products.get(productName);
        console.log(product);
      }
      pageToken = response.nextPageToken;
    } while (pageToken);  // Exits when there is no next page token.

  } catch (e) {
    console.log('ERROR!');
    console.log('Error message:' + e.message);
  }
}

// [END merchantapi_filter_disapproved_products]