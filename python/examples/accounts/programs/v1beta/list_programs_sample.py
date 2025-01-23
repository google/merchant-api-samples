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
"""A module for listing all program resources for a Merchant Center account."""

# [START merchantapi_list_programs]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import ListProgramsRequest
from google.shopping.merchant_accounts_v1beta import ProgramsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def list_programs():
  """Lists all program resources for a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ProgramsServiceClient(credentials=credentials)

  # Creates parent to identify the account for which to list programs.
  parent = "accounts/" + _ACCOUNT

  # Creates the request.
  request = ListProgramsRequest(parent=parent)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending List Programs request:")
    response = client.list_programs(request=request)

    count = 0
    # Iterates over all programs in all pages and prints each program.
    for program in response:
      print(program)
      count += 1

    print("The count of Programs returned: ")
    print(count)

  except RuntimeError as e:
    print(e)

if __name__ == "__main__":
  list_programs()

# [END merchantapi_list_programs]
