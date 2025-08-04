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
"""This class demonstrates how to update an omnichannel setting."""

# [START merchantapi_update_omnichannel_setting]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1 import InventoryVerification
from google.shopping.merchant_accounts_v1 import OmnichannelSetting
from google.shopping.merchant_accounts_v1 import OmnichannelSettingsServiceClient
from google.shopping.merchant_accounts_v1 import (
    UpdateOmnichannelSettingRequest,
)


def update_omnichannel_setting(
    account_id: str, region_code: str, contact: str, email: str
) -> None:
  """Updates an omnichannel setting for a given Merchant Center account.

  Args:
      account_id: The ID of the Merchant Center account.
      region_code: The country for which you're updating the setting.
      contact: The name of the inventory verification contact.
      email: The email of the inventory verification contact.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OmnichannelSettingsServiceClient(credentials=credentials)

  # The name of the omnichannel setting to update.
  name = f"accounts/{account_id}/omnichannelSettings/{region_code}"

  # Creates an omnichannel setting with the updated values.
  omnichannel_setting = OmnichannelSetting()
  omnichannel_setting.name = name
  omnichannel_setting.inventory_verification = InventoryVerification(
      contact=contact, contact_email=email
  )

  # Creates a field mask to specify which fields to update.
  field_mask = field_mask_pb2.FieldMask(paths=["inventory_verification"])

  # Creates the request.
  request = UpdateOmnichannelSettingRequest(
      omnichannel_setting=omnichannel_setting, update_mask=field_mask
  )

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending update omnichannel setting request:")
    response = client.update_omnichannel_setting(request=request)
    print("Updated Omnichannel Setting below:")
    print(response)
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The ID of the account to get the omnichannel settings for.
  _ACCOUNT = configuration.Configuration().read_merchant_info()

  # The country which you're targeting.
  _REGION_CODE = "{REGION_CODE}"
  # The name of the inventory verification contact you want to update.
  _CONTACT = "{NAME}"
  # The address of the inventory verification email you want to update.
  _EMAIL = "{EMAIL}"

  update_omnichannel_setting(_ACCOUNT, _REGION_CODE, _CONTACT, _EMAIL)

# [END merchantapi_update_omnichannel_setting]
