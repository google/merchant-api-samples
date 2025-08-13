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
"""A module to insert a Local Inventory."""

# [START merchantapi_insert_local_inventory]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_inventories_v1
from google.shopping.merchant_inventories_v1.types import LocalInventoryAttributes


# ENSURE you fill in product ID and store code for the sample to
# work.
_ACCOUNT = configuration.Configuration().read_merchant_info()
# ENSURE you fill in product ID for the sample to work.
_PRODUCT = "INSERT_PRODUCT_HERE"
_PARENT = f"accounts/{_ACCOUNT}/products/{_PRODUCT}"
# ENSURE you fill in store code for the sample to work.
_STORE_CODE = "INSERT_STORE_CODE_HERE"


def insert_local_inventory():
  """Inserts a `LocalInventory` to a given product.

  Replaces the full `LocalInventory` resource if an entry with the same
  `region` already exists for the product.

  It might take up to 30 minutes for the new or updated `LocalInventory`
  resource to appear in products.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_inventories_v1.LocalInventoryServiceClient(
      credentials=credentials
  )

  # Creates a Local inventory and populate its attributes.
  local_inventory = merchant_inventories_v1.LocalInventory()
  local_inventory.store_code = _STORE_CODE
  local_inventory.local_inventory_attributes.availability = (
      LocalInventoryAttributes.Availability.IN_STOCK
  )
  local_inventory.local_inventory_attributes.price = {
      "currency_code": "USD",
      "amount_micros": 33450000,
  }

  # Creates the request.
  request = merchant_inventories_v1.InsertLocalInventoryRequest(
      parent=_PARENT,
      local_inventory=local_inventory,
  )

  # Makes the request and catch and print any error messages.
  try:
    response = client.insert_local_inventory(request=request)

    print("Insert successful")
    print(response)
  except RuntimeError as e:
    print("Insert failed")
    print(e)


if __name__ == "__main__":
  insert_local_inventory()

# [END merchantapi_insert_local_inventory]
