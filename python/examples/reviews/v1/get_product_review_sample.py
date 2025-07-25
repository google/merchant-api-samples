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
# [START merchantapi_get_product_review]
"""This class demonstrates how to get a product review."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_reviews_v1beta import GetProductReviewRequest
from google.shopping.merchant_reviews_v1beta import ProductReviewsServiceClient


def get_product_review(account_id: str, product_review_id: str) -> None:
  """Gets a product review from the given account.

  Args:
    account_id: The ID of the Merchant Center account.
    product_review_id: The ID of the product review to retrieve.
  """
  # Gets OAuth credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ProductReviewsServiceClient(credentials=credentials)

  # The name of the review to retrieve.
  # Format: accounts/{account}/productReviews/{product_review}
  name = f"accounts/{account_id}/productReviews/{product_review_id}"

  # Creates the request.
  request = GetProductReviewRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending get product review request:")
    response = client.get_product_review(request=request)
    print("Product review retrieved successfully:")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Gets the merchant account ID from the user.
  merchant_account_id = configuration.Configuration().read_merchant_info()

  # The review ID is the last segment of the `name` field of the `ProductReview`
  # resource. For example, if the `name` is
  # `accounts/12345/productReviews/67890`, the review ID is `67890`.
  review_id = "YOUR_PRODUCT_REVIEW_ID"

  get_product_review(merchant_account_id, review_id)

# [END merchantapi_get_product_review]
