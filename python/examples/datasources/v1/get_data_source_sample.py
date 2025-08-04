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
"""A module to get a DataSource."""

# [START merchantapi_get_datasource]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_datasources_v1

# ENSURE you fill in the datasource ID for the sample to
# work.
_ACCOUNT = configuration.Configuration().read_merchant_info()
_DATASOURCE = "[INSERT_DATASOURCE_HERE]"
_NAME = f"accounts/{_ACCOUNT}/dataSources/{_DATASOURCE}"


def get_data_source():
  """Gets the specified `DataSource` resource."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_datasources_v1.DataSourcesServiceClient(
      credentials=credentials
  )

  # Creates the request.
  request = merchant_datasources_v1.GetDataSourceRequest(name=_NAME)

  # Makes the request and catch and print any error messages.
  try:
    response = client.get_data_source(request=request)
    print(f"Get successful: {response}")
  except RuntimeError as e:
    print("Get failed")
    print(e)


if __name__ == "__main__":
  get_data_source()

# [END merchantapi_get_datasource]
