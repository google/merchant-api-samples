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
"""A module to get BusinessIdentity."""

# [START merchantapi_get_business_identity]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import BusinessIdentityServiceClient
from google.shopping.merchant_accounts_v1 import GetBusinessIdentityRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_business_identity():
  """Gets the business identity of a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = BusinessIdentityServiceClient(credentials=credentials)

  # Creates BusinessIdentity name to identify the BusinessIdentity.
  name = "accounts/" + _ACCOUNT + "/businessIdentity"

  # Creates the request.
  request = GetBusinessIdentityRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.get_business_identity(request=request)
    print("Retrieved BusinessIdentity below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  get_business_identity()

# [END merchantapi_get_business_identity]
