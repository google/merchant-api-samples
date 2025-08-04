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
"""A module to list all the Regions for a given Merchant Center account."""

# [START merchantapi_list_regions]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import ListRegionsRequest
from google.shopping.merchant_accounts_v1 import RegionsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def list_regions():
  """Lists all the regions for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = RegionsServiceClient(credentials=credentials)

  # Creates parent to identify the account from which to list all regions.
  parent = get_parent(_ACCOUNT)

  # Creates the request.
  request = ListRegionsRequest(parent=parent)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.list_regions(request=request)
    count = 0
    print("Sending list regions request:")
    for element in response:
      print(element)
      count += 1
    print(f"The following count of elements were returned: {count}")

  except RuntimeError as e:
    print("List region request failed!")
    print(e)


if __name__ == "__main__":
  list_regions()

# [END merchantapi_list_regions]
