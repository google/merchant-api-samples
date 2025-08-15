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
# [START merchantapi_list_account_services]
"""This class demonstrates how to list all the account services of an account."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountServicesServiceClient
from google.shopping.merchant_accounts_v1 import ListAccountServicesRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def list_account_services() -> None:
  """Lists all account services for the configured account."""
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountServicesServiceClient(credentials=credentials)

  # Creates the request.
  request = ListAccountServicesRequest(parent=_PARENT)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending list account services request:")
    response = client.list_account_services(request=request)

    count = 0

    # Iterates over all returned account services and prints them.
    # The client library automatically uses the `next_page_token` to fetch all
    # pages of data.
    for account_service in response:
      print(account_service)
      count += 1
    print(f"The following count of account services were returned: {count}")
  except RuntimeError as e:
    print(f"An error has occured: \n{e}")


if __name__ == "__main__":
  list_account_services()

# [END merchantapi_list_account_services]
