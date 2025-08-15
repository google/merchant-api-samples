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
# [START merchantapi_get_account_service]
"""This class demonstrates how to get an account service."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountServicesServiceClient
from google.shopping.merchant_accounts_v1 import GetAccountServiceRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_account_service(service: str) -> None:
  """Gets an account service.

  Args:
    service: The service to get. This is the ID of the service, not the full
      resource name.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountServicesServiceClient(credentials=credentials)

  # The name has the format: accounts/{account}/services/{provider}
  name = f"accounts/{_ACCOUNT}/services/{service}"

  # Creates the request.
  request = GetAccountServiceRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Get Account Service request:")
    response = client.get_account_service(request=request)
    print("Retrieved Account Service below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Update this with the name of the service you want to get (e.g. from a
  # previous list call).
  service_ = "111~222~333"
  get_account_service(service_)

# [END merchantapi_get_account_service]
