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
"""A module to list all the subaccounts of an advanced account."""

# [START merchantapi_list_subaccounts]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountsServiceClient
from google.shopping.merchant_accounts_v1 import ListSubAccountsRequest


def get_parent(account_id):
  return f"accounts/{account_id}"


def list_sub_accounts(account_id: str):
  """Lists all the subaccounts of an advanced account."""

  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client.
  client = AccountsServiceClient(credentials=credentials)

  # Get the parent advanced account ID.
  parent = get_parent(account_id)

  # Create the request.
  request = ListSubAccountsRequest(provider=parent)

  # Make the request and print the response.
  try:
    print("Sending list subaccounts request:")
    response = client.list_sub_accounts(request=request)

    count = 0
    for account in response:
      print(account)
      count += 1

    print(f"The following count of accounts were returned: {count}")
    return response

  except RuntimeError as e:
    print("An error has occured: ")
    print(e)
    raise


if __name__ == "__main__":
  _account_id = configuration.Configuration().read_merchant_info()
  list_sub_accounts(_account_id)

# [END merchantapi_list_subaccounts]
