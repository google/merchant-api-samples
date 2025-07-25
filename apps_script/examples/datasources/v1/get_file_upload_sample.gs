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

// [START merchantapi_get_file_upload]

/**
 * Gets a File Upload Data Source
 */
function getFileUploadDataSource() {
  // IMPORTANT:
  // Enable the Merchant API DataSources sub-API Advanced Service and call it
  // "MerchantApiDataSources"

  // Replace this with your Merchant Center ID.
  const accountId = '<MERCHANT_CENTER_ID>';
  // Replace this with the ID of a File Upload Data Source.
  const dataSourceId = '<FILE_UPLOAD_DATA_SOURCE_ID>';
  // Construct the name. Use 'latest' alias to get the latest version of the File Upload
  const fileUploadName = 'accounts/' + accountId + '/dataSources/' + dataSourceId + "/fileUploads/latest";

  try {
    console.log('Sending get File Uploads request');
    // Call the DataSources.fileUploads.get API method.
    fileUpload =
        MerchantApiDataSources.Accounts.DataSources.FileUploads.get(fileUploadName);
    console.log(fileUpload);
  } catch (e) {
    console.log('ERROR!');
    console.log(e);
  }
}
// [END merchantapi_get_file_upload]