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
"""This class demonstrates how to get an omnichannel setting."""

# [START merchantapi_get_omnichannel_setting]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import GetOmnichannelSettingRequest
from google.shopping.merchant_accounts_v1beta import OmnichannelSettingsServiceClient


def get_omnichannel_setting(account_id: str, region_code: str) -> None:
  """Gets the omnichannel settings for a given Merchant Center account.

  Args:
      account_id: The ID of the Merchant Center account.
      region_code: The country for which you're retrieving the setting.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OmnichannelSettingsServiceClient(credentials=credentials)

  # The name of the omnichannel setting to retrieve.
  name = f"accounts/{account_id}/omnichannelSettings/{region_code}"

  # Creates the request.
  request = GetOmnichannelSettingRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending get omnichannel setting request:")
    response = client.get_omnichannel_setting(request=request)
    print("Retrieved Omnichannel Setting below:")
    print(response)
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The ID of the account to get the omnichannel settings for.
  _ACCOUNT = configuration.Configuration().read_merchant_info()

  # The country which you're targeting.
  _REGION_CODE = "{REGION_CODE}"

  get_omnichannel_setting(_ACCOUNT, _REGION_CODE)

# [END merchantapi_get_omnichannel_setting]
