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

// [START merchantapi_list_products]
'use strict';
const fs = require('fs');
const authUtils = require('../../authentication/authenticate.js');
const {ProductsServiceClient} = require('@google-shopping/products').v1;

/**
 * Lists all products for a given merchant.
 */
async function main() {
  try {
    const config = authUtils.getConfig();

    // Read merchant_id from merchant-info.json
    const merchant_info =
        JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
    const merchant_id = merchant_info.merchantId;

    // Construct parent. Parent is in the format of accounts/{merchant_id}
    const parent = 'accounts/' + merchant_id;

    // Get credentials
    const authClient = await authUtils.getOrGenerateUserCredentials();

    // Create options object for the client
    const options = {'authClient' : authClient};

    // Create client
    const productsClient = new ProductsServiceClient(options);

    // Construct request. Set the page size to the maximum value.
    const request = {
      parent: parent,
      pageSize: 1000
    };

    // Run request
    const iterable = productsClient.listProductsAsync(request);
    for await (const response of iterable) {
      console.log(response);
    }
  } catch (error) {
    console.error(error.message);
  }
}

main();
// [END merchantapi_list_products]