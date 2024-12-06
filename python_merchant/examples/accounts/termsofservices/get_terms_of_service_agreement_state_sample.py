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
"""Module for retrieving the state for a specific kind of the TermsOfService."""

# [START getTermsOfServiceAgreementState]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import GetTermsOfServiceAgreementStateRequest
from google.shopping.merchant_accounts_v1beta import TermsOfServiceAgreementStateServiceClient

# Replace with your actual value.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
_IDENTIFIER = "MERCHANT_CENTER-US"  # Replace with your identifier


def get_terms_of_service_agreement_state():
  """Gets a TermsOfServiceAgreementState for a specific TermsOfServiceKind and country."""

  credentials = generate_user_credentials.main()
  client = TermsOfServiceAgreementStateServiceClient(credentials=credentials)

  name = (
      "accounts/"
      + _ACCOUNT_ID
      + "/termsOfServiceAgreementStates/"
      + _IDENTIFIER
  )

  print(name)

  request = GetTermsOfServiceAgreementStateRequest(name=name)

  try:
    print("Sending Get TermsOfServiceAgreementState request:")
    response = client.get_terms_of_service_agreement_state(request=request)
    print("Retrieved TermsOfServiceAgreementState below")
    print(response)
  except RuntimeError as e:
    print(e)


# [END getTermsOfServiceAgreementState]

if __name__ == "__main__":
  get_terms_of_service_agreement_state()
