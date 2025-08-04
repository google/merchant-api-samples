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
"""This class demonstrates how to request inventory verification."""

# [START merchantapi_request_inventory_verification]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import (
    OmnichannelSettingsServiceClient,
)
from google.shopping.merchant_accounts_v1 import (
    RequestInventoryVerificationRequest,
)


def request_inventory_verification(account_id: str, region_code: str) -> None:
  """Requests inventory verification for a given Merchant Center account.

  Args:
      account_id: The ID of the Merchant Center account.
      region_code: The country for which you're requesting verification.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OmnichannelSettingsServiceClient(credentials=credentials)

  # The name of the omnichannel setting for which to request verification.
  name = f"accounts/{account_id}/omnichannelSettings/{region_code}"

  # Creates the request.
  request = RequestInventoryVerificationRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending request inventory verification request:")
    response = client.request_inventory_verification(request=request)
    print("Omnichannel Setting after inventory verification request below:")
    print(response)
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The ID of the account to get the omnichannel settings for.
  _ACCOUNT = configuration.Configuration().read_merchant_info()

  # The country which you're targeting.
  _REGION_CODE = "{REGION_CODE}"

  request_inventory_verification(_ACCOUNT, _REGION_CODE)

# [END merchantapi_request_inventory_verification]
