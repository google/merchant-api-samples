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
"""This class demonstrates how to create a Primary product datasource all `feedLabel` and `contentLanguage` combinations.

This works only for API primary feeds.
"""

# [START merchantapi_create_primary_product_data_source_multiple_languages]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_datasources_v1beta import CreateDataSourceRequest
from google.shopping.merchant_datasources_v1beta import DataSource
from google.shopping.merchant_datasources_v1beta import DataSourcesServiceClient
from google.shopping.merchant_datasources_v1beta import PrimaryProductDataSource

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def create_primary_product_data_source_multiple_languages():
  """Creates a `DataSource` resource."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = DataSourcesServiceClient(credentials=credentials)

  # Creates a PrimaryProductDataSource.
  primary_datasource = PrimaryProductDataSource()
  primary_datasource.countries = ["GB"]
  # Channel can be "ONLINE_PRODUCTS" or "LOCAL_PRODUCTS" or "PRODUCTS" .
  # While accepted, datasources with channel "products" representing unified
  # products currently cannot be used with the Products sub-API.
  primary_datasource.channel = PrimaryProductDataSource.Channel.ONLINE_PRODUCTS

  # Creates a DataSource and populates its attributes.
  data_source = DataSource()
  data_source.display_name = "Example Multiple Languages Primary DataSource"
  data_source.primary_product_data_source = primary_datasource

  # Creates the request.
  request = CreateDataSourceRequest(parent=_PARENT, data_source=data_source)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.create_data_source(request=request)
    print(f"DataSource successfully created: {response}")
  except RuntimeError as e:
    print("DataSource creation failed")
    print(e)


if __name__ == "__main__":
  create_primary_product_data_source_multiple_languages()

# [END merchantapi_create_primary_product_data_source_multiple_languages]
