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

// [START merchantapi_list_accounts]
'use strict';
const authUtils = require('../../../authentication/authenticate.js');
const {AccountsServiceClient} = require('@google-shopping/accounts').v1;

/**
 * Lists all Merchant Center accounts accessible by the authenticated user.
 * Please note that "listAccounts" method charge API quota on behalf of each 
 * specific user running the request. "listSubAccounts" method is more suitable
 * to list large number of sub-accounts.
 */
async function listAccounts() {
  try {
    // Retrieve authenticated credentials for the API call.
    const authClient = await authUtils.getOrGenerateUserCredentials();

    // Create an options object containing the authenticated client.
    const options = {authClient};

    // Initialize the Accounts API client.
    const accountsClient = new AccountsServiceClient(options);

    // Construct the request to list accounts. No parameters are needed to list
    // all accessible accounts.
    const request = {};

    console.log('Sending list accounts request...');
    // Call the API method to list accounts. This returns an async iterable.
    const iterable = accountsClient.listAccountsAsync(request);

    let count = 0;
    // Iterate asynchronously over all the accounts returned in the response.
    for await (const account of iterable) {
      // Print the details of each account.
      console.log(account);
      count++;
    }
    // Print the total number of accounts found.
    console.log(`Found ${count} accounts.`);
  } catch (error) {
    // Log any errors encountered during the process.
    console.error(`Failed to list accounts: ${error.message}`);
  }
}

// Execute the function to list accounts.
listAccounts();
// [END merchantapi_list_accounts]
