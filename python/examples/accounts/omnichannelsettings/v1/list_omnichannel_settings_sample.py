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
"""This class demonstrates how to list omnichannel settings."""

# [START merchantapi_list_omnichannel_settings]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import ListOmnichannelSettingsRequest
from google.shopping.merchant_accounts_v1 import OmnichannelSettingsServiceClient


def list_omnichannel_settings(account_id: str) -> None:
  """Lists the omnichannel settings for a given Merchant Center account.

  Args:
      account_id: The ID of the Merchant Center account.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OmnichannelSettingsServiceClient(credentials=credentials)

  # The parent account for which to list the settings.
  parent = f"accounts/{account_id}"

  # Creates the request.
  request = ListOmnichannelSettingsRequest(parent=parent)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending list omnichannel setting request:")
    response = client.list_omnichannel_settings(request=request)

    count = 0
    # Iterates over all the entries in the response.
    for omnichannel_setting in response:
      print(omnichannel_setting)
      count += 1
    print(f"The following count of elements were returned: {count}")
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The ID of the account to get the omnichannel settings for.
  _ACCOUNT = configuration.Configuration().read_merchant_info()

  list_omnichannel_settings(_ACCOUNT)

# [END merchantapi_list_omnichannel_settings]
