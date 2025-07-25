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
"""A module to get AutofeedSettings."""

# [START merchantapi_get_autofeed_settings]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import AutofeedSettingsServiceClient
from google.shopping.merchant_accounts_v1beta import GetAutofeedSettingsRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_autofeed_settings():
  """Gets the AutofeedSettings of a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AutofeedSettingsServiceClient(credentials=credentials)

  # Creates name to identify the AutofeedSettings.
  name = "accounts/" + _ACCOUNT + "/autofeedSettings"

  # Creates the request.
  request = GetAutofeedSettingsRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.get_autofeed_settings(request=request)
    print("Retrieved AutofeedSettings below")
    print(response)
  except RuntimeError as e:
    print("Get AutofeedSettings request failed")
    print(e)


if __name__ == "__main__":
  get_autofeed_settings()
# [END merchantapi_get_autofeed_settings]
