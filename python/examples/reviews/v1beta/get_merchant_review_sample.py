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
# [START merchantapi_get_merchant_review]
"""This class demonstrates how to get a Merchant review."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_reviews_v1beta import GetMerchantReviewRequest
from google.shopping.merchant_reviews_v1beta import MerchantReviewsServiceClient


def get_merchant_review(account_id: str, merchant_review_id: str) -> None:
  """Gets a merchant review from the given account.

  Args:
    account_id: The ID of the Merchant Center account.
    merchant_review_id: The ID of the merchant review to retrieve.
  """
  # Gets OAuth credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = MerchantReviewsServiceClient(credentials=credentials)

  # The name of the review to retrieve.
  # Format: accounts/{account}/merchantReviews/{merchant_review}
  name = f"accounts/{account_id}/merchantReviews/{merchant_review_id}"

  # Creates the request.
  request = GetMerchantReviewRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending get merchant review request:")
    response = client.get_merchant_review(request=request)
    print("Merchant review retrieved successfully:")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Gets the merchant account ID from the user.
  merchant_account_id = configuration.Configuration().read_merchant_info()

  # The review ID is the last segment of the `name` field of the
  # `MerchantReview` resource. For example, if the `name` is
  # `accounts/12345/merchantReviews/67890`, the review ID is `67890`.
  review_id = "YOUR_MERCHANT_REVIEW_ID"

  get_merchant_review(merchant_account_id, review_id)


# [END merchantapi_get_merchant_review]
