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
"""Module for accepting the Terms of Service agreement for a given account."""

# [START merchantapi_accept_termsofservice]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AcceptTermsOfServiceRequest
from google.shopping.merchant_accounts_v1 import TermsOfServiceServiceClient

# Replace with your actual values.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
_TOS_VERSION = (  # Replace with the Terms of Service version to accept
    "VERSION_HERE"
)
_REGION_CODE = "US"  # Replace with the region code


def accept_terms_of_service():
  """Accepts the Terms of Service agreement for a given account."""

  credentials = generate_user_credentials.main()
  client = TermsOfServiceServiceClient(credentials=credentials)

  # Construct the request
  request = AcceptTermsOfServiceRequest(
      name=f"termsOfService/{_TOS_VERSION}",
      account=f"accounts/{_ACCOUNT_ID}",
      region_code=_REGION_CODE,
  )

  try:
    print("Sending request to accept terms of service...")
    client.accept_terms_of_service(request=request)
    print("Successfully accepted terms of service.")
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  accept_terms_of_service()

# [END merchantapi_accept_termsofservice]
