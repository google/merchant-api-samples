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
"""A module to insert a Regional Inventory."""

# [START merchantapi_insert_regional_inventory]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_inventories_v1
from google.shopping.merchant_inventories_v1.types import RegionalInventoryAttributes

# ENSURE you fill in the product ID and region ID for the sample to
# work.
_ACCOUNT = configuration.Configuration().read_merchant_info()
# ENSURE you fill in the product ID for the sample to work.
_PRODUCT = "PRODUCT_ID"
_PARENT = f"accounts/{_ACCOUNT}/products/{_PRODUCT}"
# ENSURE you fill in region ID for the sample to work.
_REGION = "REGION_ID"


def insert_regional_inventory():
  """Inserts a `RegionalInventory` to a given product.

  Replaces the full `RegionalInventory` resource if an entry with the same
  `region` already exists for the product.

  It might take up to 30 minutes for the new or updated `RegionalInventory`
  resource to appear in products.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_inventories_v1.RegionalInventoryServiceClient(
      credentials=credentials
  )

  # Creates a regional inventory and populate its attributes.
  regional_inventory = merchant_inventories_v1.RegionalInventory()
  regional_inventory.region = _REGION
  regional_inventory.regional_inventory_attributes.availability = (
      RegionalInventoryAttributes.Availability.IN_STOCK
  )
  regional_inventory.regional_inventory_attributes.price = {
      "currency_code": "USD",
      "amount_micros": 33450000,
  }

  # Creates the request.
  request = merchant_inventories_v1.InsertRegionalInventoryRequest(
      parent=_PARENT,
      regional_inventory=regional_inventory,
  )

  # Makes the request and catch and print any error messages.
  try:
    response = client.insert_regional_inventory(request=request)

    print("Insert successful")
    print(response)
  except RuntimeError as e:
    print("Insert failed")
    print(e)


if __name__ == "__main__":
  insert_regional_inventory()

# [END merchantapi_insert_regional_inventory]
