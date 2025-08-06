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
"""A module to delete a Local Inventory."""

# [START merchantapi_delete_local_inventory]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_inventories_v1

# ENSURE you fill in the product ID and store code
# for the sample to work.
_ACCOUNT = configuration.Configuration().read_merchant_info()
_PRODUCT = "[INSERT_PRODUCT_HERE]"
_STORE_CODE = "[INSERT_STORE_CODE_HERE]"
_NAME = (f"accounts/{_ACCOUNT}/products/{_PRODUCT}/localInventories/"
         f"{_STORE_CODE}")


def delete_local_inventory():
  """Deletes the specified `LocalInventory` resource from the given product.

  It might take up to an hour for the `LocalInventory` to be deleted
  from the specific product. Once you have received a successful delete
  response, wait for that period before attempting a delete again.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_inventories_v1.LocalInventoryServiceClient(
      credentials=credentials)

  # Creates the request.
  request = merchant_inventories_v1.DeleteLocalInventoryRequest(name=_NAME)

  # Makes the request and catch and print any error messages.
  try:
    client.delete_local_inventory(request=request)
    print("Delete successful")
  except RuntimeError as e:
    print("Delete failed")
    print(e)


if __name__ == "__main__":
  delete_local_inventory()

# [END merchantapi_delete_local_inventory]
