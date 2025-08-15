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
# [START merchantapi_list_account_relationships]
"""Sample for listing account relationships."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountRelationshipsServiceClient
from google.shopping.merchant_accounts_v1 import ListAccountRelationshipsRequest

# Gets the account ID from the configuration file.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT_ID}"


def list_account_relationships() -> None:
  """Lists all the account relationships of an account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountRelationshipsServiceClient(credentials=credentials)

  # Creates the request.
  request = ListAccountRelationshipsRequest(parent=_PARENT)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending list account relationships request:")
    response = client.list_account_relationships(request=request)

    count = 0
    # Iterates over all relationships and prints them.
    # The client library automatically handles pagination.
    for relationship in response:
      print(relationship)
      count += 1
    print(
        f"The following count of account relationships were returned: {count}"
    )
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  list_account_relationships()

# [END merchantapi_list_account_relationships]
