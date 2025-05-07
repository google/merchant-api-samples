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
const {ProductsServiceClient} = require('@google-shopping/products').v1beta;

/**
 * Lists all products for a given merchant and filters for disapproved ones.
 * @param {!object} config - The configuration object.
 */
async function filterDisapprovedProducts(config) {
  // Read merchant_id from merchant-info.json.
  const merchantInfo =
      JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
  const merchantId = merchantInfo.merchantId;

  // Construct the parent resource name.
  // Format: accounts/{account}
  const parent = `accounts/${merchantId}`;

  // Get credentials.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Create options object for the client.
  const options = {authClient: authClient};

  // Create the ProductsServiceClient.
  const productsClient = new ProductsServiceClient(options);

  // Construct the request object for listing products. Set the page size to
  // the maximum value.
  const request = {
    parent: parent,
    pageSize: 250
  };

  console.log('Sending list products request:');
  console.log('Will filter through response for disapproved products.');

  const disapprovedProducts = [];

  try {
    // Call the API to list products using async iteration.
    const iterable = productsClient.listProductsAsync(request);

    // Iterate over all products returned.
    for await (const product of iterable) {
      // Check if the product has status information.
      if (product.productStatus && product.productStatus.destinationStatuses) {
        // Iterate through destination statuses.
        for (const destinationStatus of
                 product.productStatus.destinationStatuses) {
          // Check if the product is disapproved in any country for this
          // destination.
          if (destinationStatus.disapprovedCountries &&
              destinationStatus.disapprovedCountries.length > 0) {
            disapprovedProducts.push(product);
            // Break the inner loop to avoid adding the same product multiple
            // times if disapproved for multiple destinations.
            break;
          }
        }
      }
    }
    console.log(`The following count of disapproved products were returned: ${
        disapprovedProducts.length}`);
    // You can optionally print the disapproved products themselves:
    // disapprovedProducts.forEach(product => console.log(product));
  } catch (error) {
    console.error('An error occurred:');
    console.error(error.message);
  }
}

/**
 * Calls the filterDisapprovedProducts function with the configuration.
 */
async function main() {
  const config = authUtils.getConfig();
  await filterDisapprovedProducts(config);
}

main();
// [END merchantapi_filter_disapproved_products]
