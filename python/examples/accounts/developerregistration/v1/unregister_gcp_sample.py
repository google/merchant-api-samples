# -*- coding: utf-8 -*-
# Copyright 2026 Google LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     https://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
"""This example unregisters the GCP project used to call the Merchant API."""

# [START merchantapi_unregister_gcp]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import DeveloperRegistrationServiceClient
from google.shopping.merchant_accounts_v1 import UnregisterGcpRequest


def unregister_gcp(account_id: str) -> None:
  """Unregisters the GCP project used to call the Merchant API for an account.

  Args:
    account_id: The ID of your Merchant Center account.
  """
  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client to the Developer Registration Service.
  client = DeveloperRegistrationServiceClient(credentials=credentials)

  # The name has the format: accounts/{account}/developerRegistration
  name = f"accounts/{account_id}/developerRegistration"

  # Create the request to unregister the GCP project.
  request = UnregisterGcpRequest(name=name)

  # Make the API call and handle potential errors.
  try:
    print(f"Sending UnregisterGcp request for: {name}")
    client.unregister_gcp(request=request)
    print("Unregister GCP successful.")
  except RuntimeError as e:
    print(f"An error occurred: {e}")
    raise


if __name__ == "__main__":
  _account_id = configuration.Configuration().read_merchant_info()
  unregister_gcp(_account_id)
# [END merchantapi_unregister_gcp]
