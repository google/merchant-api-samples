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
"""A module for listing Promotions."""

# [START merchantapi_list_promotions]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_promotions_v1 import ListPromotionsRequest
from google.shopping.merchant_promotions_v1 import PromotionsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def list_promotions():
  """Lists promotions for the given account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = PromotionsServiceClient(credentials=credentials)

  # Creates the request.
  request = ListPromotionsRequest(parent=_PARENT)

  # Makes the request and prints the results.
  try:
    print("Sending list promotions request:")
    response = client.list_promotions(request=request)

    count = 0

    # Iterates over all returned promotions and prints them.
    for promotion in response.promotions:
      print(promotion)
      count += 1
    print(f"The following count of promotions were returned: {count}")

  except RuntimeError as e:
    print("Failed to list promotions.")
    print(e)


if __name__ == "__main__":
  list_promotions()

# [END merchantapi_list_promotions]
