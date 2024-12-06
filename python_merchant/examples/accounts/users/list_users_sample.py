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
"""A module to list users."""


# [START listUsers]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import ListUsersRequest
from google.shopping.merchant_accounts_v1beta import UserServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def list_users():
  """Lists all the users for a given Merchant Center account."""

  # Get OAuth credentials
  credentials = generate_user_credentials.main()

  # Create a UserServiceClient
  client = UserServiceClient(credentials=credentials)

  # Create parent string
  parent = get_parent(_ACCOUNT)

  # Create the request
  request = ListUsersRequest(parent=parent)

  try:
    print("Sending list users request:")
    response = client.list_users(request=request)

    count = 0
    for element in response:
      print(element)
      count += 1

    print("The following count of elements were returned: ")
    print(count)

  except RuntimeError as e:
    print(e)


# [END listUsers]

if __name__ == "__main__":
  list_users()
