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

// [START merchantapi_register_gcp]
/**
 * Registers a specific developer's email address on the current GCP project
 * Note: every Apps Script project might be associated with a different default 
 * GCP project.
 * Check https://developers.google.com/apps-script/guides/cloud-platform-projects
 * for more details.
 */
function registerDeveloper() {
  // IMPORTANT:
  // Enable the Merchant API Accounts sub-API Advanced Service and call it
  // "MerchantApiAccounts"


  // Replace this with your Merchant Center ID.
  const accountId = '<ACCOUNT_ID>';

  // Replace this with your email address.
  const developerEmail = '<YOUR_EMAIL>';

  // Construct the parent resource name.
  const parent = 'accounts/' + accountId + "/developerRegistration";

  const requestBody = {
    "developerEmail": developerEmail
  };
  try {
    console.log('Sending register GCP request');
    const response = MerchantApiAccounts.Accounts.DeveloperRegistration.registerGcp(requestBody, parent);
    console.log(response);
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_register_gcp]