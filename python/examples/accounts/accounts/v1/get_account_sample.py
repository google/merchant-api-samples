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
"""A module to get a specific Merchant Center account."""

# [START merchantapi_get_account]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountsServiceClient
from google.shopping.merchant_accounts_v1 import GetAccountRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def get_account():
  """Gets a single Merchant Center account."""

  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client.
  client = AccountsServiceClient(credentials=credentials)

  # Create the account name.
  name = get_parent(_ACCOUNT)

  # Create the request.
  request = GetAccountRequest(name=name)

  # Make the request and print the response.
  try:
    print("Sending Get Account request:")
    response = client.get_account(request=request)
    print("Retrieved Account below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  get_account()

# [END merchantapi_get_account]
