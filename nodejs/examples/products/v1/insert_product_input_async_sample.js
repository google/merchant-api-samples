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

// [START merchantapi_insert_product_input_async]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {
  ProductInputsServiceClient,
} = require('@google-shopping/products').v1beta;

/**
 * This class demonstrates how to insert a product input asynchronously.
 */

/**
 * Helper function to generate a random string for offerId
 * @returns {string} A sample offerId.
 */
function generateRandomString() {
  const characters =
    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let result = '';
  const length = 8;
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  return result;
}

/**
 * Helper function to create a sample ProductInput object
 * @returns {!object} A sample ProductInput object.
 */
function createRandomProduct() {
  const price = {
    amount_micros: 33450000, // 33.45 USD
    currency_code: 'USD',
  };

  const shipping = {
    price: price,
    country: 'GB',
    service: '1st class post',
  };

  const shipping2 = {
    price: price,
    country: 'FR',
    service: '1st class post',
  };

  const attributes = {
    title: 'A Tale of Two Cities',
    description: 'A classic novel about the French Revolution',
    link: 'https://exampleWebsite.com/tale-of-two-cities.html',
    image_link: 'https://exampleWebsite.com/tale-of-two-cities.jpg',
    availability: 'in stock',
    condition: 'new',
    google_product_category: 'Media > Books',
    gtins: ['9780007350896'],
    shipping: [shipping, shipping2],
    // Price is nested within attributes in the ProductInput message
    price: price,
  };

  // Construct the ProductInput object
  const productInput = {
    contentLanguage: 'en',
    feedLabel: 'CH',
    offerId: generateRandomString(),
    productAttributes: attributes,
  };

  return productInput;
}

/**
 * Inserts multiple product inputs asynchronously.
 * @param {!object} config - Configuration object.
 * @param {string} dataSource - The data source name.
 */
async function asyncInsertProductInput(config, dataSource) {
  // Read merchant_id from the configuration file.
  const merchantInfo = JSON.parse(
    fs.readFileSync(config.merchantInfoFile, 'utf8')
  );
  const merchantId = merchantInfo.merchantId;

  // Construct the parent resource name string.
  const parent = `accounts/${merchantId}`;

  // Get OAuth2 credentials.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Create client options with authentication.
  const options = {authClient: authClient};

  // Create the ProductInputsServiceClient.
  const productInputsServiceClient = new ProductInputsServiceClient(options);

  // Create five insert product input requests with random product details.
  const requests = [];
  for (let i = 0; i < 5; i++) {
    const request = {
      parent: parent,
      // You can only insert products into datasource types of Input "API" and "FILE", and
      // of Type "Primary" or "Supplemental."
      // This field takes the `name` field of the datasource, e.g.,
      // accounts/123/dataSources/456
      dataSource: dataSource,
      // If this product is already owned by another datasource, when re-inserting, the
      // new datasource will take ownership of the product.
      productInput: createRandomProduct(),
    };
    requests.push(request);
  }

  console.log('Sending insert product input requests...');

  // Create an array of promises by calling the insertProductInput method for each request.
  const insertPromises = requests.map(request =>
    productInputsServiceClient.insertProductInput(request)
  );

  // Wait for all insert operations to complete.
  // Promise.all returns an array of results, where each result is the response
  // from the corresponding insertProductInput call (which is the inserted ProductInput).
  // The response from insertProductInput is an array where the first element is the ProductInput.
  const results = await Promise.all(insertPromises);
  const insertedProducts = results.map(result => result[0]); // Extract ProductInput from each response array

  console.log('Inserted products below:');
  console.log(JSON.stringify(insertedProducts, null, 2));
}

/**
 * Main function to call the async insert product input method.
 */
async function main() {
  // Get configuration settings.
  const config = authUtils.getConfig();
  // Define the data source ID. Replace {datasourceId} with your actual data source ID.
  // The format is accounts/{account_id}/dataSources/{datasource_id}.
  const merchantInfo = JSON.parse(
    fs.readFileSync(config.merchantInfoFile, 'utf8')
  );
  const merchantId = merchantInfo.merchantId;
  const dataSource = `accounts/${merchantId}/dataSources/{datasourceId}`; // Replace {datasourceId}

  try {
    await asyncInsertProductInput(config, dataSource);
  } catch (error) {
    console.error(`An error occurred: ${error.message || error}`);
    // Log details if available (e.g., for gRPC errors)
    if (error.details) {
      console.error(`Details: ${error.details}`);
    }
  }
}

main();
// [END merchantapi_insert_product_input_async]
