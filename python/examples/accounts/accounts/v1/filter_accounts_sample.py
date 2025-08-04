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
"""A module to filter Merchant Center accounts."""


# [START merchantapi_filter_accounts]
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountsServiceClient
from google.shopping.merchant_accounts_v1 import ListAccountsRequest


def filter_accounts():
  """Filters the accounts the user making the request has access to."""

  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client.
  client = AccountsServiceClient(credentials=credentials)

  # Create the filter string.
  filter_string = 'accountName = "*store*" AND relationship(providerId = 123)'

  # Create the request.
  request = ListAccountsRequest(filter=filter_string)

  # Make the request and print the response.
  try:
    print("Sending list accounts request with filter:")
    response = client.list_accounts(request=request)

    count = 0
    for account in response:
      print(account)
      count += 1

    print(f"The following count of elements were returned: {count}")

  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  filter_accounts()

# [END merchantapi_filter_accounts]
