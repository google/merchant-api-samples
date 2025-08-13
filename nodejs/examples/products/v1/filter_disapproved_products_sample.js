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
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {ProductsServiceClient} = require('@google-shopping/products').v1;
const {ReportServiceClient} = require('@google-shopping/reports').v1;

/**
 * Gets the product details for a given product using the GetProduct method.
 * @param {!Object} authClient - The authenticated Google API client.
 * @param {string} productName - The name of the product to retrieve, in the
 *   format: accounts/{account}/products/{productId}.
 */
async function getProduct(authClient, productName) {
  // Create options object for the client, including authentication.
  const productOptions = {authClient: authClient};

  // Create the Products API client.
  const productsClient = new ProductsServiceClient(productOptions);

  try {
    // Construct the request object.
    const request = {name: productName};
    // Call the API to get the product.
    // getProduct returns a Promise that resolves to an array.
    // The first element is the product resource.
    const response = await productsClient.getProduct(request);
    console.log(response);
  } catch (error) {
    // Log any errors that occur during the API call.
    console.log(error);
  }
}

/**
 * Filters disapproved products for a given Merchant Center account using the
 * Reporting API. Then, it prints the product details for each disapproved
 * product.
 * @param {!Object} authClient - The authenticated Google API client.
 * @param {string} merchantId - The Merchant Center account ID.
 */
async function filterDisapprovedProducts(authClient, merchantId) {
  // Create options object for the client, including authentication.
  const reportOptions = {authClient: authClient};

  // Create the Report API client.
  const reportServiceClient = new ReportServiceClient(reportOptions);

  try {
    // Construct the parent resource name for the report query.
    const parent = `accounts/${merchantId}`;

    // Define the query to select disapproved products.
    // aggregated_reporting_context_status can be one of the following values:
    // NOT_ELIGIBLE_OR_DISAPPROVED, ELIGIBLE, PENDING, ELIGIBLE_LIMITED,
    // AGGREGATED_REPORTING_CONTEXT_STATUS_UNSPECIFIED
    const query = `SELECT offer_id, id, title, price
      FROM product_view
      WHERE aggregated_reporting_context_status = 'NOT_ELIGIBLE_OR_DISAPPROVED'`;

    // Construct the search request.
    const searchRequest = {
      parent: parent,
      query: query,
    };

    console.log('Sending search report request for Product View.');
    // Call the Reports.search API method. This returns an async iterable.
    const iterable = reportServiceClient.searchAsync(searchRequest);
    console.log('Received search reports response: ');

    // Iterate over all report rows in the response.
    for await (const row of iterable) {
      console.log('Printing data from Product View:');
      console.log(row.productView);

      // Construct the full product resource name from the report row.
      // Assumes row.productView and row.productView.id are populated,
      // which should be the case based on the query.
      const productName =
          `accounts/${merchantId}/products/${row.productView.id}`;

      // OPTIONAL: Get and print the full product details using GetProduct.
      console.log('Getting full product details by calling GetProduct method:');
      // Get and print the full product details.
      await getProduct(authClient, productName);
    }
  } catch (error) {
    console.log('Failed to search reports for Product View.');
    // Log any errors that occur during the report search or processing.
    console.log(error);
  }
}

/**
 * Main function to execute the sample.
 */
async function main() {
  try {
    // Retrieve application configuration.
    const config = authUtils.getConfig();

    // Read merchant ID from the merchant-info.json file.
    const merchantInfo =
        JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
    const merchantId = merchantInfo.merchantId;

    // Authenticate and get the OAuth2 client.
    const authClient = await authUtils.getOrGenerateUserCredentials();

    // Call the function to filter disapproved products and get their details.
    await filterDisapprovedProducts(authClient, merchantId);
  } catch (error) {
    // Log any errors that occur during setup or authentication.
    console.error(error.message);
    process.exitCode = 1;
  }
}

main();
// [END merchantapi_filter_disapproved_products]