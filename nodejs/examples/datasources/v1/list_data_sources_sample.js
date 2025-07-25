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

// [START merchantapi_list_data_sources]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js'); // Assuming auth utils are in this relative path
const {DataSourcesServiceClient} = require('@google-shopping/datasources').v1beta; // Use the correct client and version

/**
 * This function lists all data sources for a given Merchant Center account.
 */
async function listDataSourcesWrapper() {
  // Retrieve configuration settings, including the path to the merchant info file.
  const config = authUtils.getConfig();

  // Read the merchant ID from the specified JSON file.
  const merchantInfo = JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
  const merchantId = merchantInfo.merchantId;

  // Obtain authenticated credentials for the API call.
  const authClient = await authUtils.getOrGenerateUserCredentials();

  // Configure the client options, including the authentication client.
  const clientOptions = {
    authClient: authClient,
  };

  // Create a new instance of the DataSourcesServiceClient using the options.
  const dataSourceClient = new DataSourcesServiceClient(clientOptions);

  await callListDataSources(dataSourceClient, merchantId);
}

/**
 * Calls the listDataSources API and processes the response.
 * @param {!DataSourcesServiceClient} dataSourceClient The authenticated client.
 * @param {string} merchantId The Merchant Center account ID.
 */
async function callListDataSources(dataSourceClient, merchantId) {
  // Construct the parent resource name string required by the API.
  // Format: accounts/{merchantId}
  const parent = `accounts/${merchantId}`;

  // Prepare the request object for the listDataSources API call.
  const request = {
    parent: parent,
  };

  console.log('Sending list data sources request:');

  try {
    // Call the API to list data sources. This returns an async iterable.
    const iterable = dataSourceClient.listDataSourcesAsync(request);

    let count = 0;
    const allDataSources = [];
    const primaryDataSources = [];

    // Iterate asynchronously over all data sources returned by the API.
    // The client library handles pagination automatically.
    for await (const dataSource of iterable) {
      console.log(JSON.stringify(dataSource, null, 2)); // Print the full data source object
      count++;
      allDataSources.push(dataSource);

      // Check if the data source is a primary product data source.
      // In Node.js protobufs, check for the presence of the specific oneof field.
      if (dataSource.primaryProductDataSource) {
        primaryDataSources.push(dataSource);
      }
    }

    // Print the total number of data sources found.
    console.log(`The following count of elements were returned: ${count}`);
    // You can optionally log the filtered primary data sources
    // console.log('Primary Product Data Sources:', primaryDataSources);

  } catch (error) {
    console.error(`Failed to list data sources: ${error.message}`);
  }
}

// Execute the main wrapper function and handle potential promise rejection.
listDataSourcesWrapper().catch(error => {
  console.error(error.message);
  process.exitCode = 1; 
});

// [END merchantapi_list_data_sources]