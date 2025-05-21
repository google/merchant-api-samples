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

/**
 * Inserts a product into the products list. Logs the API response.
 */
function productInsert() {
  // IMPORTANT:
  // Enable the Merchant API Products Bundle Advanced Service and call it
  // "Products"

  // Replace this with your Merchant Center ID.
  const accountId = 'INSERT_MERCHANT_ID';

  // Replace this with the Data Source ID you want to use.
  const dataSourceId = 'INSERT_DATASOURCE_ID';

  // Construct the parent name
  const parent = 'accounts/' + accountId;

  // Construct the Data Source name
  const dataSource = parent + '/dataSources/' + dataSourceId;

  // Create a product resource and insert it
  const productResource = {
    'offerId': 'fromAppsScript',
    'contentLanguage': 'en',
    'feedLabel': 'US',
    'channel': 'online',
    'attributes': {
      'title': 'A Tale of Two Cities',
      'description': 'A classic novel about the French Revolution',
      'link': 'http://my-book-shop.com/tale-of-two-cities.html',
      'imageLink': 'http://my-book-shop.com/tale-of-two-cities.jpg',
      'availability': 'in stock',
      'condition': 'new',
      'googleProductCategory': 'Media > Books',
      'gtin': '[9780007350896]',
      'price': {'amountMicros': '2500000', 'currencyCode': 'USD'},
    }
  };

  try {
    console.log('Sending insert ProductInput request');
    // Call the ProductInputs.insert API method.
    response = Products.Accounts.ProductInputs.insert(
        productResource, parent, {dataSource});
    // RESTful insert returns the JSON object as a response.
    console.log('Inserted ProductInput below');
    console.log(response);
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_insert_product_input]
