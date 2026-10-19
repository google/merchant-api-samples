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
"""A module to delete a Product Input."""

# [START merchantapi_delete_product_input]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_products_v1


def delete_product_input(account_id: str, product_id: str, data_source: str):
  """Deletes the specified `ProductInput` resource.

  Args:
    account_id: The Merchant Center account ID.
    product_id: The ID of the product input to delete, in the format
      `contentLanguage~feedLabel~offerId`.
    data_source: The resource name of the data source from which to delete the
      product.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_products_v1.ProductInputsServiceClient(
      credentials=credentials
  )

  name = f"accounts/{account_id}/productInputs/{product_id}"

  # Creates the request.
  request = merchant_products_v1.DeleteProductInputRequest(
      name=name, data_source=data_source
  )

  # Makes the request and catch and print any error messages.
  try:
    client.delete_product_input(request=request)
    print("Deletion successful")
  except RuntimeError as e:
    print("Deletion failed")
    print(e)
    raise


if __name__ == "__main__":
  # Retrieves the Merchant Center account ID from the configuration file.
  merchant_account_id = configuration.Configuration().read_merchant_info()

  # ENSURE you fill in the product ID and data source for the
  # sample to work.
  # In the format of `contentLanguage~feedLabel~offerId`
  target_product = "[INSERT_PRODUCT_HERE]"
  target_data_source = (
      f"accounts/{merchant_account_id}/dataSources/[INSERT_DATA_SOURCE_HERE]"
  )

  delete_product_input(
      merchant_account_id, target_product, target_data_source
  )

# [END merchantapi_delete_product_input]
