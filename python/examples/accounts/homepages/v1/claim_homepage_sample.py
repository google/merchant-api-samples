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
"""A module to claim a Homepage."""

# [START merchantapi_claim_homepage]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import ClaimHomepageRequest
from google.shopping.merchant_accounts_v1beta import HomepageServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def claim_homepage():
  """Claims the homepage for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = HomepageServiceClient(credentials=credentials)

  # Creates Homepage name to identify Homepage.
  name = "accounts/" + _ACCOUNT + "/homepage"

  # Creates the request.
  request = ClaimHomepageRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.claim_homepage(request=request)
    print("Retrieved Homepage below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  claim_homepage()

# [END merchantapi_claim_homepage]
