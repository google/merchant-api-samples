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
"""A module to delete a specific Merchant Center account."""


# [START deleteAccount]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import AccountsServiceClient
from google.shopping.merchant_accounts_v1beta import DeleteAccountRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def delete_account():
  """Deletes a given Merchant Center account."""

  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client.
  client = AccountsServiceClient(credentials=credentials)

  # Create the account name.
  name = get_parent(_ACCOUNT)

  # Create the request.
  request = DeleteAccountRequest(name=name, force=True)

  # Make the request and print the response.
  try:
    print("Sending Delete Account request")
    client.delete_account(request=request)
    print("Delete successful.")
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  delete_account()

# [END deleteAccount]
