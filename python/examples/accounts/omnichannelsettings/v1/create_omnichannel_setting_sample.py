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
"""This class demonstrates how to create an omnichannel setting."""

# [START merchantapi_create_omnichannel_setting]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import CreateOmnichannelSettingRequest
from google.shopping.merchant_accounts_v1 import InStock
from google.shopping.merchant_accounts_v1 import OmnichannelSetting
from google.shopping.merchant_accounts_v1 import OmnichannelSettingsServiceClient


def create_omnichannel_setting(account_id: str, region_code: str) -> None:
  """Creates an omnichannel setting for a given Merchant Center account.

  Args:
      account_id: The ID of the Merchant Center account.
      region_code: The country for which you're creating the setting.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OmnichannelSettingsServiceClient(credentials=credentials)

  # The parent account under which to create the setting.
  parent = f"accounts/{account_id}"

  # Creates an omnichannel setting with GHLSF type in the given country.
  omnichannel_setting = OmnichannelSetting()
  omnichannel_setting.region_code = region_code
  omnichannel_setting.lsf_type = OmnichannelSetting.LsfType.GHLSF
  omnichannel_setting.in_stock = InStock()

  # Creates the request.
  request = CreateOmnichannelSettingRequest(
      parent=parent, omnichannel_setting=omnichannel_setting
  )

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending create omnichannel setting request:")
    response = client.create_omnichannel_setting(request=request)
    print("Inserted Omnichannel Setting below:")
    print(response)
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The ID of the account to get the omnichannel settings for.
  _ACCOUNT = configuration.Configuration().read_merchant_info()

  # The country which you're targeting.
  _REGION_CODE = "{REGION_CODE}"

  create_omnichannel_setting(_ACCOUNT, _REGION_CODE)

# [END merchantapi_create_omnichannel_setting]
