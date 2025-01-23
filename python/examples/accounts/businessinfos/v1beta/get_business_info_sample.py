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
"""A module to get BusinessInfo."""


# [START merchantapi_get_business_info]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import BusinessInfoServiceClient
from google.shopping.merchant_accounts_v1beta import GetBusinessInfoRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_business_info():
  """Gets the business information of a Merchant Center account."""

  # Get OAuth credentials
  credentials = generate_user_credentials.main()

  # Create a BusinessInfoServiceClient
  business_info_service_client = BusinessInfoServiceClient(
      credentials=credentials
  )

  # Create BusinessInfo name
  name = "accounts/" + _ACCOUNT + "/businessInfo"

  # Create the request
  request = GetBusinessInfoRequest(name=name)

  # Call the API and print the response
  try:
    print("Sending get BusinessInfo request:")
    response = business_info_service_client.get_business_info(request=request)
    print("Retrieved BusinessInfo below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  get_business_info()

# [END merchantapi_get_business_info]
