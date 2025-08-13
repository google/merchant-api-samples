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

// [START merchantapi_search_report]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {ReportServiceClient} = require('@google-shopping/reports').v1;

/**
 * Searches reports for a given Merchant Center account and prints them.
 * @param {string} accountId The Merchant Center account ID.
 */
async function searchAndPrintReports(accountId) {
  // Authenticate and get the auth client using the utility from the example.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Configure the client with authentication details.
  const clientOptions = {authClient: authClient};

  // Instantiate the Report Service Client.
  const reportServiceClient = new ReportServiceClient(clientOptions);

  // Construct the parent resource name required by the API.
  // The format is "accounts/{accountId}".
  const parent = `accounts/${accountId}`;

  // Define the Merchant Query Language (MQL) query.
  // The commented-out queries below are examples for different report types.
  // For detailed documentation on MQL and available fields, refer to:
  // https://developers.google.com/merchant/api/reference/rest/reports_v1beta/accounts.reports#ReportRow
  //
  // This is an example query for the product_view report.
  let query =
      'SELECT offer_id, id, price, gtin, item_issues, channel, language_code, ' +
      'feed_label, title, brand, category_l1, product_type_l1, availability, ' +
      'shipping_label, thumbnail_link, click_potential ' +
      'FROM product_view';

  /*
  // An example query for the price_competitiveness_product_view report.
  query =
    'SELECT offer_id, id, benchmark_price, report_country_code, price, title, '
  + 'brand, category_l1, product_type_l1 ' + "FROM
  price_competitiveness_product_view " + "WHERE date BETWEEN '2023-03-03' AND
  '2025-03-10'";
  */
  /*
  // An example query for the price_insights_product_view report.
  query =
    'SELECT offer_id, id, suggested_price, price, effectiveness, title, brand, '
  + 'category_l1, product_type_l1, predicted_impressions_change_fraction, ' +
    'predicted_clicks_change_fraction, predicted_conversions_change_fraction ' +
    'FROM price_insights_product_view';
  */
  /*
  // An example query for the product_performance_view report.
  query =
    'SELECT offer_id, conversion_value, marketing_method, customer_country_code,
  ' + 'title, brand, category_l1, product_type_l1, custom_label0, clicks, ' +
    'impressions, click_through_rate, conversions, conversion_rate ' +
    "FROM product_performance_view " +
    "WHERE date BETWEEN '2023-03-03' AND '2025-03-10'";
  */

  // Prepare the search request object for the API call.
  const request = {
    parent: parent,
    query: query,
    // pageSize: 100 // Optional: Define the number of results per page.
    // The API will use a default page size if not specified.
  };

  console.log('Sending search reports request.');
  // Call the `search` method of the ReportServiceClient.
  // This method returns an iterable that yields each ReportRow.
  const response = await reportServiceClient.search(request);

  console.log('Received search reports response: ');
  // Iterate through each row in the response stream and print it to the
  // console. The client library transparently handles pagination to fetch all
  // results.
  for await (const row of response) {
    // Each 'row' is a ReportRow protobuf message object.
    console.log(row);  // Prints the object, typically in a compact format.
  }
}

/**
 * Main function that orchestrates the script.
 */
async function main() {
  try {
    // Load application configuration, e.g., path to credentials and merchant
    // info.
    const config = authUtils.getConfig();

    // Read the merchant ID (referred to as accountId in this API context)
    // from the JSON configuration file specified in the config.
    const merchantInfo =
        JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
    const accountId = merchantInfo.merchantId;

    // Execute the core logic to search and print reports.
    await searchAndPrintReports(accountId.toString());

  } catch (error) {
    console.log('Failed to search reports.');
    console.log(error);
  }
}

main();
// [END merchantapi_search_report]