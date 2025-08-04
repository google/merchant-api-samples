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
"""A module to update AutofeedSettings."""

# [START merchantapi_update_autofeedsettings]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1 import AutofeedSettings
from google.shopping.merchant_accounts_v1 import AutofeedSettingsServiceClient
from google.shopping.merchant_accounts_v1 import UpdateAutofeedSettingsRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_autofeed_settings():
  """Updates the AutofeedSettings of a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AutofeedSettingsServiceClient(credentials=credentials)

  # Creates name to identify the AutofeedSettings.
  name = "accounts/" + _ACCOUNT + "/autofeedSettings"

  # Create AutofeedSettings with the updated fields.
  autofeed_settings = AutofeedSettings(name=name, enable_products=False)

  # Create the field mask.
  field_mask = field_mask_pb2.FieldMask(paths=["enable_products"])

  # Creates the request.
  request = UpdateAutofeedSettingsRequest(
      autofeed_settings=autofeed_settings, update_mask=field_mask
  )

  # Makes the request and catches and prints any error messages.
  try:
    response = client.update_autofeed_settings(request=request)
    print("Updated AutofeedSettings Name below")
    print(response.name)
  except RuntimeError as e:
    print("Update AutofeedSettings request failed")
    print(e)


if __name__ == "__main__":
  update_autofeed_settings()
# [END merchantapi_update_autofeedsettings]
