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

// [START merchantapi_insert_product_input]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {
  ProductInputsServiceClient,
} = require('@google-shopping/products').v1beta;

/**
 * Inserts a product input for a given Merchant Center account and data source.
 * @param {!object} config - The configuration object.
 * @param {string} dataSource - The data source name.
 */
async function insertProductInput(config, dataSource) {
  // Read merchant_id from merchant-info.json.
  const merchantInfo = JSON.parse(
    fs.readFileSync(config.merchantInfoFile, 'utf8')
  );
  const merchantId = merchantInfo.merchantId;

  // Construct the parent resource name.
  // Format: accounts/{account}
  const parent = `accounts/${merchantId}`;

  // Get credentials.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Create options object for the client.
  const options = {authClient: authClient};

  // Create the ProductInputsServiceClient.
  const productInputsClient = new ProductInputsServiceClient(options);

  // Define the price object.
  const price = {
    amountMicros: 33450000, // $33.45
    currencyCode: 'USD',
  };

  // Define shipping details.
  const shipping1 = {
    price: price,
    country: 'GB',
    service: '1st class post',
  };

  const shipping2 = {
    price: price,
    country: 'FR',
    service: '1st class post',
  };

  // Define product attributes.
  const attributes = {
    title: 'A Tale of Two Cities',
    description: 'A classic novel about the French Revolution',
    link: 'https://exampleWebsite.com/tale-of-two-cities.html',
    imageLink: 'https://exampleWebsite.com/tale-of-two-cities.jpg',
    availability: 'in stock',
    condition: 'new',
    googleProductCategory: 'Media > Books',
    gtin: ['9780007350896'], // GTIN is a repeated field
    shipping: [shipping1, shipping2], // Shipping is a repeated field
  };

  // Define the product input object.
  const productInput = {
    channel: 'ONLINE', // Use the string representation of the enum
    contentLanguage: 'en',
    feedLabel: 'label',
    offerId: 'sku123',
    attributes: attributes,
  };

  // Construct the request object.
  const request = {
    parent: parent,
    // You can only insert products into datasource types of Input "API" and "FILE",
    // and of Type "Primary" or "Supplemental."
    // This field takes the `name` field of the datasource.
    // If this product is already owned by another datasource, when re-inserting,
    // the new datasource will take ownership of the product.
    dataSource: dataSource,
    productInput: productInput,
  };

  try {
    console.log('Sending insert ProductInput request');
    // Call the API to insert the product input.
    const [response, options, rawResponse] = await productInputsClient.insertProductInput(request);

    console.log('Inserted ProductInput Name below');
    // The last part of the product name will be the product ID assigned by Google.
    // Product ID has the format `channel~contentLanguage~feedLabel~offerId`
    console.log(response.name);
    console.log('Inserted Product Name below');
    console.log(response.product);
    console.log('Inserted Product below');
    console.log(response);
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Main function to call the insertProductInput function.
 */
async function main() {
  const config = authUtils.getConfig();
  // Read merchant_id from merchant-info.json.
  const merchantInfo = JSON.parse(
    fs.readFileSync(config.merchantInfoFile, 'utf8')
  );
  const merchantId = merchantInfo.merchantId;

  // Identifies the data source that will own the product input.
  // Replace {INSERT_DATASOURCE_ID} with the actual ID of your data source.
  // Format: accounts/{account}/dataSources/{dataSource}
  const dataSource = `accounts/${merchantId}/dataSources/{INSERT_DATASOURCE_ID}`;

  await insertProductInput(config, dataSource);
}

main();
// [END merchantapi_insert_product_input]