# -*- coding: utf-8 -*-
# Copyright 2025 Google LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

"""A module to get a FileUpload DataSource."""
# [START merchantapi_get_file_upload]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_datasources_v1beta import FileUploadsServiceClient
from google.shopping.merchant_datasources_v1beta import GetFileUploadRequest

# Fetches the Merchant Center account ID from the local configuration file.
# Ensure you have a valid configuration file for this to work.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def get_file_upload_sample(account_id: str, datasource_id: str) -> None:
  """Demonstrates how to get the latest data source file upload.

  Args:
    account_id: The Merchant Center account ID.
    datasource_id: The ID of the data source.
  """

  # Generates OAuth 2.0 credentials for authenticating with the API.
  credentials = generate_user_credentials.main()

  # Creates a FileUploadsServiceClient instance with the generated credentials.
  client = FileUploadsServiceClient(credentials=credentials)

  # Constructs the full resource name for the file upload.
  # The format is:
  # accounts/{account}/dataSources/{datasource}/fileUploads/latest
  name = f"accounts/{account_id}/dataSources/{datasource_id}/fileUploads/latest"

  # Creates a request to get the specified file upload.
  request = GetFileUploadRequest(name=name)

  print("Sending get FileUpload request:")
  try:
    # Makes the API call to retrieve the file upload information.
    response = client.get_file_upload(request=request)

    print("Retrieved FileUpload below")
    print(response)
  except RuntimeError as e:
    # Catches and prints any errors that occur during the API call.
    print(e)


if __name__ == "__main__":
  # The ID of the datasource for which to retrieve the latest file upload.
  # This is a placeholder ID. Replace with a valid datasource ID.
  datasource_id_to_get = "<INSERT_DATASOURCE_ID>"

  get_file_upload_sample(_ACCOUNT_ID, datasource_id_to_get)

# [END merchantapi_get_file_upload]
