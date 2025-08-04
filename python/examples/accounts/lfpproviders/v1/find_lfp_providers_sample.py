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
# [START merchantapi_find_lfp_providers]
"""Sample for finding LFP providers for a given Merchant Center account."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import FindLfpProvidersRequest
from google.shopping.merchant_accounts_v1 import LfpProvidersServiceClient

# Gets the merchant account ID from the configuration file.
_ACCOUNT = configuration.Configuration().read_merchant_info()


def find_lfp_providers(region_code: str) -> None:
  """Gets the LFP Providers for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = LfpProvidersServiceClient(credentials=credentials)

  # The parent resource name of the omnichannel setting.
  # Format: `accounts/{account}/omnichannelSettings/{omnichannel_setting}`
  parent = f"accounts/{_ACCOUNT}/omnichannelSettings/{region_code}"

  # Creates the request.
  request = FindLfpProvidersRequest(parent=parent)

  print("Sending find LFP providers request:")
  # Makes the request and catches and prints any error messages.
  try:
    # Calls the API to find LFP providers.
    response = client.find_lfp_providers(request=request)

    count = 0
    # Iterates over all the entries in the response and prints them.
    for lfp_provider in response:
      print(lfp_provider)
      count += 1
    print(f"The following count of elements were returned: {count}")

  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The country you're targeting.
  _REGION_CODE = "{REGION_CODE}"
  find_lfp_providers(_REGION_CODE)

# [END merchantapi_find_lfp_providers]
