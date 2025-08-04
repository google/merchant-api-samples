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
"""A module for getting a program resource for a Merchant Center account."""

# [START merchantapi_get_program]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import GetProgramRequest
from google.shopping.merchant_accounts_v1 import ProgramsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_program(program_):
  """Gets a program resource for a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ProgramsServiceClient(credentials=credentials)

  # Creates program name to identify the program.
  name = "accounts/" + _ACCOUNT + "/programs/" + program_

  # Creates the request.
  request = GetProgramRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Get Program request:")
    response = client.get_program(request=request)
    print("Retrieved Program below")
    print(response)
  except RuntimeError as e:
    print(e)

if __name__ == "__main__":
  program = "free-listings"
  get_program(program)

# [END merchantapi_get_program]
