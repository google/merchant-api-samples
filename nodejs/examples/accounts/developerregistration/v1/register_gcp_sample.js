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
'use strict';
const fs = require('fs');
const authUtils = require('../../../authentication/authenticate.js');
const {DeveloperRegistrationServiceClient} = require('@google-shopping/accounts').v1;

/**
 * Registers the GCP project used to call the Merchant API with a developer email.
 */
async function main() {
  try {
    // Replace with your developer email
    const developerEmail = 'developer@example.com';

    const config = authUtils.getConfig();

    // Read merchant_id from merchant-info.json
    const merchant_info =
        JSON.parse(fs.readFileSync(config.merchantInfoFile, 'utf8'));
    const merchant_id = merchant_info.merchantId;

    // Construct parent. Parent is in the format of accounts/{merchant_id}
    const parent = 'accounts/' + merchant_id;

    // Construct name. Name is in the format of accounts/{merchant_id}/developerRegistration
    const name = parent + "/developerRegistration";

    // Get credentials
    const authClient = await authUtils.getOrGenerateUserCredentials();

    // Create options object for the client
    const options = {'authClient' : authClient};

    // Create client
    const developerRegistrationClient = new DeveloperRegistrationServiceClient(options);

    // Construct request.
    const request = {
      name: name,
      developerEmail: developerEmail
    };

    // Run request
    const response = await developerRegistrationClient.registerGcp(request);
    console.log('GCP project registered successfully:', response);
  } catch (error) {
    console.error(error.message);
  }
}

main();
// [END merchantapi_register_gcp]