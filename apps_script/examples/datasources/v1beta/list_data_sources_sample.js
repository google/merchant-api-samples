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

/**
 * Lists all data sources for a given Merchant Center account.
 */
function listDataSources() {
  // IMPORTANT:
  // Enable the Merchant API DataSources Bundle Advanced Service and call it
  // "DataSources"

  // Replace this with your Merchant Center ID.
  const accountId = '<MERCHANT_CENTER_ID>';

  // Construct the parent name
  const parent = 'accounts/' + accountId;
  let dataSources = [];
  let primaryDataSources = [];
  try {
    console.log('Sending list DataSources request');
    let pageToken;
    let pageSize = 10;
    // Call the DataSources.list API method. Use the pageToken to iterate through
    // all pages of results.
    do {
      response =
          DataSources.Accounts.DataSources.list(parent, {pageSize, pageToken});
      for (const datasource of response.dataSources) {
        dataSources.push(datasource);
        if (datasource.primaryProductDataSource) {
          primaryDataSources.push(datasource);
        }
      }
      pageToken = response.nextPageToken;
    } while (pageToken);  // Exits when there is no next page token.
    console.log('Retrieved ' + dataSources.length + ' data sources.');
    console.log(
        'There were ' + primaryDataSources.length +
        ' primary product data sources.');
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_list_data_sources]
