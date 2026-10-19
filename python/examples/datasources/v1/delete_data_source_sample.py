# -*- coding: utf-8 -*-
# Copyright 2024 Google LLC
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
"""A module to delete a DataSource."""

# [START merchantapi_delete_data_source]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_datasources_v1


def delete_data_source(account_id: str, datasource_id: str):
  """Deletes the specified `DataSource` resource.

  Delete works for any datasource type.
  If Type "Supplemental", delete will only work if it's not linked to any
  primary feed. If a link exists and the Type is "Supplemental", you will need
  to remove the supplemental feed from the default and/or custom rule(s) of any
  primary feed(s) that references it. Then retry the delete.

  Args:
    account_id: The Merchant Center account ID.
    datasource_id: The ID of the data source to delete.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_datasources_v1.DataSourcesServiceClient(
      credentials=credentials
  )

  name = f"accounts/{account_id}/dataSources/{datasource_id}"

  # Creates the request.
  request = merchant_datasources_v1.DeleteDataSourceRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    # No response is returned on request.
    client.delete_data_source(request=request)
    print("Deletion successful")
  except RuntimeError as e:
    print("Deletion failed")
    print(e)
    raise


if __name__ == "__main__":
  # Retrieves the Merchant Center account ID from the configuration file.
  merchant_account_id = configuration.Configuration().read_merchant_info()
  # ENSURE you fill in the datasource ID for the sample to
  # work.
  # An ID automatically assigned to the datasource after creation by Google.
  target_datasource = "[INSERT_DATASOURCE_HERE]"

  delete_data_source(merchant_account_id, target_datasource)

# [END merchantapi_delete_data_source]
