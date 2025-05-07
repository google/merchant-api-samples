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

// [START merchantapi_update_product_input]
'use strict';

// This sample demonstrates how to update a ProductInput for a given merchant.

const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {
  ProductInputsServiceClient,
} = require('@google-shopping/products').v1beta;

/**
 * Performs the actual API call to update the product input.
 *
 * @param {!object} authClient - An authenticated Google Auth client.
 * @param {string} accountId - The ID of the merchant account.
 * @param {string} productId - The ID of the product to update.
 * @param {string} dataSourceId - The ID of the data source.
 */
async function callUpdateProductInput(
    authClient, accountId, productId, dataSourceId) {
  // Create a new ProductInputsServiceClient with the authenticated client.
  const productInputsServiceClient = new ProductInputsServiceClient({
    authClient: authClient,
  });

  // Construct the full name of the product input resource.
  // Format: accounts/{account}/productinputs/{productinput}
  const name = `accounts/${accountId}/productInputs/${productId}`;

  // Define the FieldMask to specify which fields of the product input to
  // update. According to the API, only 'attributes' and 'customAttributes' can
  // be updated this way.
  const fieldMask = {
    paths: [
      'attributes.title', 'attributes.description', 'attributes.link',
      'attributes.image_link', 'attributes.availability',
      'attributes.condition', 'attributes.gtin',
      'custom_attributes.mycustomattribute',  // This path targets a custom
                                              // attribute by its name.
    ],
  };

  // Prepare the new attributes for the product.
  const attributes = {
    title: 'A Tale of Two Cities',
    description: 'A classic novel about the French Revolution',
    link: 'https://exampleWebsite.com/tale-of-two-cities.html',
    imageLink: 'https://exampleWebsite.com/tale-of-two-cities.jpg',
    availability: 'in stock',
    condition: 'new',
    gtin: [
      '9780007350896'
    ],  // GTIN is a repeated field, so it's provided as an array.
  };

  // Construct the full name of the data source resource.
  // The datasource can be either a primary or supplemental datasource.
  // Format: accounts/{account}/dataSources/{datasource}
  const dataSource = `accounts/${accountId}/dataSources/${dataSourceId}`;

  // Prepare the ProductInput object with the new data.
  const productInput = {
    name: name,  // The resource name of the product input being updated.
    attributes: attributes,
    customAttributes: [
      {
        name: 'mycustomattribute',
        value: 'Example value',
      },
    ],
  };

  // Construct the update request object.
  const request = {
    productInput: productInput,
    updateMask: fieldMask,
    dataSource: dataSource,
  };

  console.log('Sending update ProductInput request');
  // Make the API call to update the product input.
  // The response is an array, with the first element being the updated
  // ProductInput object.
  const [response] =
      await productInputsServiceClient.updateProductInput(request);

  console.log('Updated ProductInput Name below');
  // The response contains the updated ProductInput. Its 'name' field is the
  // full resource name, which includes the Google-assigned product ID (format:
  // channel~contentLanguage~feedLabel~offerId).
  console.log(response.name);
  console.log('Updated Product below');
  console.log(response);  // Log the full response object.
}

/**
 * Main function to orchestrate the product input update.
 * It handles configuration, authentication, and calls the core update logic.
 */
async function main() {
  // These are the IDs for the product and data source to be used in the update.
  // The productId is an ID assigned by Google, typically in the format:
  // channel~contentLanguage~feedLabel~offerId. Replace 'online~en~label~sku123'
  // with your specific product ID.
  const productId = 'online~en~label~sku123';
  // The dataSourceId identifies the data source that will own the product
  // input. Replace '{INSERT_DATASOURCE_ID}' with your actual data source ID.
  const dataSourceId = '{INSERT_DATASOURCE_ID}';

  try {
    // Load application configuration, which includes the path to
    // merchant-info.json.
    const config = authUtils.getConfig();

    // Read the Merchant Center account ID from the merchant-info.json file.
    const merchantInfo =
        JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
    const accountId = merchantInfo.merchantId;

    // Authenticate and get an OAuth2 client.
    const authClient = await authUtils.getOrGenerateUserCredentials();

    // Call the helper function to perform the product input update.
    // Ensure accountId is a string as it's part of a resource name.
    await callUpdateProductInput(
        authClient, accountId.toString(), productId, dataSourceId);
  } catch (error) {
    console.error(error);
    process.exitCode = 1;
  }
}

main();
// [END merchantapi_update_product_input]
