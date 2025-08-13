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

// [START merchantapi_get_product]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {ProductsServiceClient} = require('@google-shopping/products').v1;

/**
 * Retrieves a single product for a given Merchant Center account.
 * @param {!object} config - The configuration object.
 * @param {string} productName - The name of the product to retrieve.
 */
async function getProduct(config, productName) {
  // Get credentials.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Create options object for the client.
  const options = {authClient: authClient};

  // Create the ProductsServiceClient.
  const productsClient = new ProductsServiceClient(options);

  // Construct the request object.
  const request = {
    name: productName,
  };

  try {
    console.log('Sending get product request:');
    // Call the API to get the product.
    const response = await productsClient.getProduct(request);

    console.log('Retrieved Product below');
    console.log(response);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Main function to call the getProduct sample.
 */
async function main() {
  const config = authUtils.getConfig();
  // Read merchant_id from merchant-info.json.
  const merchantInfo =
      JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
  const merchantId = merchantInfo.merchantId;

  // The name of the `product`. Replace {product} with the actual ID.
  // Format: accounts/{account}/products/{product}
  // {product} is usually in the format:
  // contentLanguage~feedLabel~offerId
  const productName = `accounts/${merchantId}/products/{productId}`;

  await getProduct(config, productName);
}

main();
// [END merchantapi_get_product]
