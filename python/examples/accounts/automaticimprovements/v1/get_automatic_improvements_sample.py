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

# [START merchantapi_get_automatic_improvements]
"""Gets the automatic improvements settings for a Merchant Center account."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import AutomaticImprovementsServiceClient
from google.shopping.merchant_accounts_v1beta import GetAutomaticImprovementsRequest


# Fetches the account ID from the config file.
# This is a placeholder for your actual account ID.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
# Construct the resource name for AutomaticImprovements.
# The format is accounts/{account}/automaticImprovements
_NAME = f"accounts/{_ACCOUNT_ID}/automaticImprovements"


def get_automatic_improvements_sample():
  """Gets the automatic improvements settings for a Merchant Center account."""

  # Generates OAuth 2.0 credentials for authentication.
  credentials = generate_user_credentials.main()

  # Creates a client for the AutomaticImprovementsService.
  client = AutomaticImprovementsServiceClient(credentials=credentials)

  # Creates the request to get automatic improvements.
  # The name parameter is the resource name of the automatic improvements
  # settings.
  request = GetAutomaticImprovementsRequest(name=_NAME)

  print("Sending get AutomaticImprovements request:")
  # Makes the API request to get automatic improvements.
  try:
    response = client.get_automatic_improvements(request=request)

    print("Retrieved AutomaticImprovements below")
    print(response)
  except RuntimeError as e:
    print(f"An API error occurred: {e}")


if __name__ == "__main__":
  get_automatic_improvements_sample()

# [END merchantapi_get_automatic_improvements]
