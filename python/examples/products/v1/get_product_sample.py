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
"""A module to get a Product."""

# [START merchantapi_get_product]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_products_v1


_ACCOUNT = configuration.Configuration().read_merchant_info()

# ENSURE you fill in the product ID for the sample to
# work.
# In the format of `contentLanguage~feedLabel~offerId`
_PRODUCT = "[INSERT_PRODUCT_HERE]"
_NAME = f"accounts/{_ACCOUNT}/products/{_PRODUCT}"


def get_product():
  """Gets the specified `Product` resource."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_products_v1.ProductsServiceClient(
      credentials=credentials
  )

  # Creates the request.
  request = merchant_products_v1.GetProductRequest(name=_NAME)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.get_product(request=request)
    print(f"Get successful: {response}")
  except RuntimeError as e:
    print("Get failed")
    print(e)


if __name__ == "__main__":
  get_product()

# [END merchantapi_get_product]
