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
"""A module for disabling a program for a Merchant Center account."""

# [START merchantapi_disable_program]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import DisableProgramRequest
from google.shopping.merchant_accounts_v1beta import ProgramsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def disable_program(program):
  """Disables a program for the given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ProgramsServiceClient(credentials=credentials)

  # Creates program name to identify the program.
  name = "accounts/" + _ACCOUNT + "/programs/" + program

  # Creates the request.
  request = DisableProgramRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.disable_program(request=request)
    print("Disabled Program below")
    print(response)
    return response
  except RuntimeError as e:
    print(e)
    return None


if __name__ == "__main__":
  # Replace this with the name of the program to be disabled.
  program_to_disable = "free-listings"
  disable_program(program_to_disable)

# [END merchantapi_disable_program]
