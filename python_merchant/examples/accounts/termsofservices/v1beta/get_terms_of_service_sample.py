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
"""Module for retrieving a specific version of the TermsOfService."""

# [START getTermsOfService]
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import GetTermsOfServiceRequest
from google.shopping.merchant_accounts_v1beta import TermsOfServiceServiceClient

# Replace with your actual value.
_VERSION = "132"  # Replace with the version you want to retrieve


def get_terms_of_service():
  """Gets a TermsOfService for a specific version."""

  credentials = generate_user_credentials.main()
  client = TermsOfServiceServiceClient(credentials=credentials)

  name = "termsOfService/" + _VERSION

  request = GetTermsOfServiceRequest(name=name)

  try:
    print("Sending Get TermsOfService request:")
    response = client.get_terms_of_service(request=request)
    print("Retrieved TermsOfService below")
    print(response)
  except RuntimeError as e:
    print(e)


# [END getTermsOfService]

if __name__ == "__main__":
  get_terms_of_service()
