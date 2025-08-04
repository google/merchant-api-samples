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

# [START merchantapi_create_primary_product_data_source]
"""Sample for creating a primary product data source."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_datasources_v1 import CreateDataSourceRequest
from google.shopping.merchant_datasources_v1 import DataSource
from google.shopping.merchant_datasources_v1 import DataSourcesServiceClient
from google.shopping.merchant_datasources_v1 import PrimaryProductDataSource
# Used for setting the destination type, e.g., SHOPPING_ADS.
from google.shopping.type import types as merchant_api_types


# Fetches the Merchant Center account ID from the configuration file.
# This ID is essential for constructing the 'parent' resource path
# required by the API.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
# Constructs the parent resource name format for Data Source operations.
_PARENT = f"accounts/{_ACCOUNT_ID}"


def create_primary_product_data_source(display_name: str) -> None:
  """Creates a primary product data source for the 'en' language and 'GB' region, targeting specific destinations.

  Args:
      display_name: The user-visible name for the new data source in Merchant
        Center.

  Returns:
      The resource name of the newly created data source if successful,
      otherwise None.
  """

  # Obtains OAuth 2.0 credentials for API authentication.
  credentials = generate_user_credentials.main()

  # Initializes the DataSourcesServiceClient with the obtained credentials.
  client = DataSourcesServiceClient(credentials=credentials)

  # Configures the PrimaryProductDataSource.
  # This section defines the core properties of the product data feed.
  primary_product_data_source = PrimaryProductDataSource()

  # Sets the target countries for this data source.
  primary_product_data_source.countries = ["GB"]
  # Sets the content language for the products.
  primary_product_data_source.content_language = "en"
  # Sets the feed label, often matching the country or language.
  primary_product_data_source.feed_label = "GB"

  # Defines the destinations for this data source and their states.
  # If destinations are not explicitly listed, defaults will be used.

  # Configure Shopping Ads destination (enabled).
  destination_shopping_ads = PrimaryProductDataSource.Destination()
  destination_shopping_ads.destination = (
      merchant_api_types.Destination.DestinationEnum.SHOPPING_ADS
  )
  destination_shopping_ads.state = (
      PrimaryProductDataSource.Destination.State.ENABLED
  )

  # Configure Free Listings destination (disabled).
  destination_free_listings = PrimaryProductDataSource.Destination()
  destination_free_listings.destination = (
      merchant_api_types.Destination.DestinationEnum.FREE_LISTINGS
  )
  destination_free_listings.state = (
      PrimaryProductDataSource.Destination.State.DISABLED
  )

  primary_product_data_source.destinations = [
      destination_free_listings,
      destination_shopping_ads
  ]

  # Assembles the DataSource object.
  data_source = DataSource()
  data_source.display_name = display_name
  data_source.primary_product_data_source = primary_product_data_source

  # Prepares the CreateDataSourceRequest.
  # This request includes the parent account and the data source configuration.
  request = CreateDataSourceRequest(parent=_PARENT, data_source=data_source)

  try:
    # Executes the API call to create the data source.
    print("Sending Create PrimaryProduct DataSource request")
    response = client.create_data_source(request=request)
    # Confirms creation and prints the new data source's name.
    print("Created DataSource Name below")
    print(response)
  except RuntimeError as e:
    # Handles any errors encountered during the API request.
    print(e)


if __name__ == "__main__":
  # Sets the desired display name for the data source in Merchant Center.
  datasource_display_name = "British Primary Product Data"

  # Calls the function to create the data source.
  create_primary_product_data_source(datasource_display_name)

# [END merchantapi_create_primary_product_data_source]
