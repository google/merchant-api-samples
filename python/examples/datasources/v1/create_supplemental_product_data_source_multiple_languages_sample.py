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
"""This class demonstrates how to create a Supplemental product datasource all `feedLabel` and `contentLanguage` combinations.

This works only for API supplemental feeds.
"""

# [START merchantapi_create_supplemental_product_data_source_multiple_languages]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_datasources_v1

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def create_supplemental_product_data_source_multiple_languages():
  """Creates a `DataSource` resource."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_datasources_v1.DataSourcesServiceClient(
      credentials=credentials
  )

  # Creates a SupplementalProductDataSource.
  supplemental_datasource = (
      merchant_datasources_v1.SupplementalProductDataSource()
  )

  # Creates a DataSource and populates its attributes.
  data_source = merchant_datasources_v1.DataSource()
  data_source.display_name = (
      "Example Multiple Languages Supplemental DataSource"
  )
  data_source.supplemental_product_data_source = supplemental_datasource

  # Creates the request.
  request = merchant_datasources_v1.CreateDataSourceRequest(
      parent=_PARENT, data_source=data_source
  )

  # Makes the request and catches and prints any error messages.
  try:
    response = client.create_data_source(request=request)
    print(f"DataSource successfully created: {response}")
  except RuntimeError as e:
    print("DataSource creation failed")
    print(e)


if __name__ == "__main__":
  create_supplemental_product_data_source_multiple_languages()

# [END merchantapi_create_supplemental_product_data_source_multiple_languages]
