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
"""Module for retrieving the latest TermsOfService."""


# [START retrieveLatestTermsOfService]
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import RetrieveLatestTermsOfServiceRequest
from google.shopping.merchant_accounts_v1beta import TermsOfServiceKind
from google.shopping.merchant_accounts_v1beta import TermsOfServiceServiceClient

# Replace with your actual values.
_REGION_CODE = "US"  # Replace with your region code
_KIND = (
    TermsOfServiceKind.MERCHANT_CENTER
)  # Replace with your TermsOfServiceKind


def retrieve_latest_terms_of_service():
  """Retrieves the latest TermsOfService for a specific region and kind."""

  credentials = generate_user_credentials.main()
  client = TermsOfServiceServiceClient(credentials=credentials)

  request = RetrieveLatestTermsOfServiceRequest(
      region_code=_REGION_CODE, kind=_KIND
  )

  try:
    print("Sending Retrieve Latest TermsOfService request:")
    response = client.retrieve_latest_terms_of_service(request=request)
    print("Retrieved TermsOfService below")
    print(response)
  except RuntimeError as e:
    print(e)


# [END retrieveLatestTermsOfService]

if __name__ == "__main__":
  retrieve_latest_terms_of_service()
