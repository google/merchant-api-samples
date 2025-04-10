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

// [START merchantapi_delete_product_input]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {
  ProductInputsServiceClient,
} = require('@google-shopping/products').v1beta;

/**
 * This class demonstrates how to delete a product input for a given
 * Merchant Center account.
 * @param {!object} config - The configuration object.
 * @param {string} productId - The ID of the product input to delete.
 * @param {string} dataSource - The name of the data source from which to delete the product input.
 */
async function deleteProductInput(config, productId, dataSource) {
  // Read merchant_id from merchant-info.json.
  const merchantInfo = JSON.parse(
    fs.readFileSync(config.merchantInfoFile, 'utf8')
  );
  const merchantId = merchantInfo.merchantId;

  // Construct the fully qualified product input name.
  // Format: accounts/{account}/productInputs/{productInput}
  const name = `accounts/${merchantId}/productInputs/${productId}`;

  // Get credentials.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Create options object for the client.
  const options = {authClient: authClient};

  // Create the ProductInputsServiceClient.
  const productInputsClient = new ProductInputsServiceClient(options);

  // Construct the request object.
  const request = {
    name: name,
    dataSource: dataSource,
  };

  try {
    console.log('Sending deleteProductInput request');
    // Call the API to delete the product input.
    // No response is returned on success.
    await productInputsClient.deleteProductInput(request);
    console.log(
      'Delete successful, note that it may take a few minutes for the delete to update in' +
        ' the system. If you make a products.get or products.list request before a few' +
        ' minutes have passed, the old product data may be returned.'
    );
  } catch (error) {
    console.error(error.message);
  }
}

/**
 * Main function to call the deleteProductInput function.
 */
async function main() {
  const config = authUtils.getConfig();
  // An ID assigned to a product input by Google. In the format
  // channel~contentLanguage~feedLabel~offerId
  const productId = 'online~en~label~sku123';

  const merchantInfo = JSON.parse(
    fs.readFileSync(config.merchantInfoFile, 'utf8')
  );
  const merchantId = merchantInfo.merchantId;

  // The name of the dataSource from which to delete the product input.
  // If it is a primary feed, this will delete the product completely.
  // If it's a supplemental feed, it will only delete the product information
  // from that feed, but the product will still be available from the primary feed.
  // Format: accounts/{account}/dataSources/{dataSource}
  // Replace {dataSource} with the actual ID of your data source.
  const dataSource = `accounts/${merchantId}/dataSources/{dataSource}`;

  await deleteProductInput(config, productId, dataSource);
}

main();
// [END merchantapi_delete_product_input]
