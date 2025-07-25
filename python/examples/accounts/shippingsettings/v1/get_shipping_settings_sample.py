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
"""A module to get the ShippingSettings for a given Merchant Center account."""


# [START merchantapi_get_shippingsettings]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import GetShippingSettingsRequest
from google.shopping.merchant_accounts_v1beta import ShippingSettingsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def get_shipping_settings():
  """Gets the ShippingSettings for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ShippingSettingsServiceClient(credentials=credentials)

  # Creates the Shipping Settings name
  name = _PARENT + "/shippingSettings"

  # Creates the request.
  request = GetShippingSettingsRequest(name=name)

  # Makes the request and prints the retrieved ShippingSettings.
  try:
    response = client.get_shipping_settings(request=request)
    print("Retrieved ShippingSettings below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  get_shipping_settings()

# [END merchantapi_get_shippingsettings]
