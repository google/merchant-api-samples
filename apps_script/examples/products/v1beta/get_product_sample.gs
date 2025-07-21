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
/**
 * Get a specific product for a given Merchant Center account.
 */
function getProduct() {
  // IMPORTANT:
  // Enable the Merchant API Products sub-API Advanced Service and call it
  // "MerchantApiProducts"

  // Replace this with your Merchant Center ID.
  const accountId = '<MERCHANT_CENTER_ID>';

  // The ID of the product to retrieve.
  // This ID is assigned by Google and typically follows the format:
  // channel~contentLanguage~feedLabel~offerId
  // Replace with an actual product ID from your Merchant Center account.
  const productId = '<PRODUCT_ID>';

  // Construct the parent name
  const parent = 'accounts/' + accountId;

  // Construct the product resource name
  const name = parent + "/products/" + productId;

  try {
    console.log('Sending get Product request');
    // Call the Products.get API method.
    product = MerchantApiProducts.Accounts.Products.get(name);
    console.log(product);
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_get_product]
