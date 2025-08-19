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
"""This class demonstrates how to get a single Merchant Center account by its alias."""

# [START merchantapi_get_account_by_alias]
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountsServiceClient
from google.shopping.merchant_accounts_v1 import GetAccountRequest


def get_account_by_alias(provider_id: int, alias: str) -> None:
  """Gets a single Merchant Center account by its alias.

  Args:
      provider_id: The provider ID of the account.
      alias: The alias of the account.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountsServiceClient(credentials=credentials)

  # Creates the name of the account to retrieve.
  # The name has the format: accounts/{providerId}~{alias}
  # This format can be used whenever an account name is needed. For example it
  # can also be used to get the homepage of an account or approve, get or list
  # its services etc.
  # For more information about aliases see
  # https://developers.google.com/merchant/api/guides/accounts/relationships
  name = f"accounts/{provider_id}~{alias}"

  # Creates the request.
  request = GetAccountRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Get Account request:")
    response = client.get_account(request=request)
    print("Retrieved Account below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Update this with the provider ID of the account you want to get.
  provider_id_ = 123
  # Update this with the alias of the account you want to get.
  alias_ = "alias"
  get_account_by_alias(provider_id_, alias_)

# [END merchantapi_get_account_by_alias]
