# -*- coding: utf-8 -*-
# Copyright 2025 Google LLC
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

"""This example registers a GCP project with a developer email."""
# [START merchantapi_register_gcp]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import DeveloperRegistrationServiceClient
from google.shopping.merchant_accounts_v1 import RegisterGcpRequest


def register_gcp(account_id: str, developer_email: str) -> None:
  """Registers the GCP project used to call the Merchant API with a developer email.

  Args:
    account_id: The ID of your Merchant Center account.
    developer_email: The email address of the developer to register.
  """
  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client to the Developer Registration Service.
  client = DeveloperRegistrationServiceClient(credentials=credentials)

  # The name has the format: accounts/{account}/developerRegistration
  name = f"accounts/{account_id}/developerRegistration"

  # Create the request to register the GCP project.
  request = RegisterGcpRequest(
      name=name,
      developer_email=developer_email,
  )

  # Make the API call and handle potential errors.
  try:
    print("Sending RegisterGcp request:")
    response = client.register_gcp(request=request)
    print("Registered GCP project successfully:")
    print(response)
  except RuntimeError as e:
    print(f"An error occurred: {e}")


if __name__ == "__main__":

  # Your Merchant Center account ID.
  # This can be found in the Merchant Center UI.
  _account_id = configuration.Configuration().read_merchant_info()

  # The developer email to associate with the GCP project.
  _developer_email = "YOUR_EMAIL_HERE"

  register_gcp(_account_id, _developer_email)
  # [END merchantapi_register_gcp]
