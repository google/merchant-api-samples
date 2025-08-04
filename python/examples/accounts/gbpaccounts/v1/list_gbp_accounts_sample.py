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
"""Demonstrates how to get the list of GBP accounts for a given Merchant Center account."""

# [START merchantapi_list_gbp_accounts]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import GbpAccountsServiceClient
from google.shopping.merchant_accounts_v1 import ListGbpAccountsRequest

# Gets the merchant account ID from the configuration file.
_ACCOUNT = configuration.Configuration().read_merchant_info()
# Creates the parent resource name string.
_PARENT = f"accounts/{_ACCOUNT}"


def list_gbp_accounts() -> None:
  """Lists the Google Business Profile accounts for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = GbpAccountsServiceClient(credentials=credentials)

  # Creates the request.
  request = ListGbpAccountsRequest(parent=_PARENT)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending list GBP accounts request:")
    # Makes the request and retrieves the list of GBP accounts.
    response = client.list_gbp_accounts(request=request)

    count = 0
    # Iterates over all the GBP accounts in the response and prints them.
    for gbp_account in response:
      print(gbp_account)
      count += 1
    print(f"The following count of elements were returned: {count}")
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  list_gbp_accounts()

# [END merchantapi_list_gbp_accounts]
