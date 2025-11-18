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
"""A module to get a single product for a given Merchant Center account."""

# [START merchantapi_get_product]
import base64

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_products_v1 import GetProductRequest
from google.shopping.merchant_products_v1 import ProductsServiceClient


# This is needed for base64url encoding if product IDs contain special
# characters such as forward slashes.
def encode_product_id(product_id_to_encode: str) -> str:
  """Base64url encodes a string without padding.

  Args:
    product_id_to_encode: The product ID string to encode.

  Returns:
    The encoded product ID string.
  """
  encoded_bytes = base64.urlsafe_b64encode(product_id_to_encode.encode("utf-8"))
  return encoded_bytes.rstrip(b"=").decode("utf-8")


def get_product(account_id_arg: str, product_id_arg: str) -> None:
  """Retrieves a single product from a Merchant Center account.

  Args:
    account_id_arg: The ID of the Merchant Center account.
    product_id_arg: The ID of the product to retrieve.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ProductsServiceClient(credentials=credentials)

  # The name has the format: accounts/{account}/products/{product}
  name = f"accounts/{account_id_arg}/products/{product_id_arg}"

  # Creates the request.
  request = GetProductRequest(name=name)

  print("Sending get product request:")

  # Makes the request and catches and prints any error messages.
  try:
    response = client.get_product(request=request)
    print("Retrieved Product below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Retrieves the configured account ID from the config file.
  account_id = configuration.Configuration().read_merchant_info()

  # The ID of the product, which is the final component of the product's
  # resource name. The product ID is the same as the offer ID.
  # For example, `en~US~sku123`.
  product_id = "en~US~sku123"  # Replace with your actual product ID.

  # Uncomment the following line if the product ID contains special characters
  # (such as forward slashes) and needs base64url encoding.
  # product_id = encode_product_id(product_id)

  get_product(account_id, product_id)


# [END merchantapi_get_product]
