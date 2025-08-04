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
"""Sample for fetching a FileInput DataSource."""

# [START merchantapi_fetch_file_data_source]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_datasources_v1 import DataSourcesServiceClient
from google.shopping.merchant_datasources_v1 import FetchDataSourceRequest

# Read the Merchant Center account ID from the configuration file.
# This ID is used to identify the account under which the data source operation
# will be performed.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def fetch_file_data_source(datasource_id: str):
  """Fetches a specific FileInput DataSource for the configured Merchant Center account.

  This operation is specific to DataSources of type FileInput. Attempting to
  fetch other types of DataSources will result in an API error.

  Args:
    datasource_id: The unique identifier of the data source to fetch.
  """

  # Obtain OAuth 2.0 credentials for authenticating API requests.
  credentials = generate_user_credentials.main()

  # Create an instance of the DataSourcesServiceClient using the obtained
  # credentials. This client is used to interact with the Merchant API's
  # DataSources service.
  client = DataSourcesServiceClient(credentials=credentials)

  # Construct the full resource name for the data source.
  # The format is `accounts/{account_id}/dataSources/{datasource_id}`.
  name = f"accounts/{_ACCOUNT_ID}/dataSources/{datasource_id}"

  # Create a FetchDataSourceRequest object, setting the name of the data source
  # to be fetched.
  request = FetchDataSourceRequest(name=name)

  # Inform the user that the fetch request is being sent.
  print("Sending FETCH DataSource request:")

  # Execute the fetch_data_source API call.
  try:
    # For FileInput DataSources, a successful fetch operation does not return
    # any data in the response body. The call's success implies the fetch was
    # triggered. If the API call encounters an error (e.g., DataSource not
    # found, network issue, or if the DataSource is not a FileInput type), a
    # RuntimeError will be raised.
    client.fetch_data_source(request=request)
    print("Successfully fetched DataSource.")
  except RuntimeError as e:
    # If an error occurs during the API call, print the error details.
    print(e)


if __name__ == "__main__":
  # The ID of the FileInput DataSource that needs to be fetched.
  # IMPORTANT: You must replace "<DATASOURCE_ID>" with a valid DataSource ID
  # that corresponds to a FileInput type DataSource in your Merchant Center
  # account.
  datasource_id_to_fetch = "<DATASOURCE_ID>"

  fetch_file_data_source(datasource_id=datasource_id_to_fetch)

# [END merchantapi_fetch_file_data_source]
