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
# [START merchantapi_list_product_reviews]
"""This class demonstrates how to list all the product reviews in a given account."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_reviews_v1beta import ListProductReviewsRequest
from google.shopping.merchant_reviews_v1beta import ProductReviewsServiceClient


def list_product_reviews(account_id: str) -> None:
  """Lists all product reviews for a given account.

  Args:
    account_id: The ID of the Merchant Center account.
  """
  # Gets OAuth credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ProductReviewsServiceClient(credentials=credentials)

  # The parent account from which to retrieve reviews.
  # Format: accounts/{account}
  parent = f"accounts/{account_id}"

  # Creates the request.
  request = ListProductReviewsRequest(parent=parent)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending list product reviews request:")
    response = client.list_product_reviews(request=request)

    count = 0
    # Iterates over all reviews in all pages and prints them.
    for element in response:
      print(element)
      count += 1
    print(f"The following count of elements were returned: {count}")

  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Gets the merchant account ID from the user.
  merchant_account_id = configuration.Configuration().read_merchant_info()
  list_product_reviews(merchant_account_id)

# [END merchantapi_list_product_reviews]
