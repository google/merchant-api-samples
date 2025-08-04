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
"""Module for retrieving the latest Merchant Center account's TermsOfService state."""


# [START merchantapi_retrieve_for_application_termsofservice_agreementstate]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import RetrieveForApplicationTermsOfServiceAgreementStateRequest
from google.shopping.merchant_accounts_v1 import TermsOfServiceAgreementStateServiceClient

# Replace with your actual value.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def retrieve_for_application_terms_of_service_agreement_state():
  """Retrieves the latest TermsOfService agreement state for the account."""

  credentials = generate_user_credentials.main()
  client = TermsOfServiceAgreementStateServiceClient(credentials=credentials)

  parent = f"accounts/{_ACCOUNT_ID}"

  request = RetrieveForApplicationTermsOfServiceAgreementStateRequest(
      parent=parent
  )

  try:
    print("Sending RetrieveForApplication TermsOfService Agreement request:")
    response = client.retrieve_for_application_terms_of_service_agreement_state(
        request=request
    )
    print("Retrieved TermsOfServiceAgreementState below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  retrieve_for_application_terms_of_service_agreement_state()

# [END merchantapi_retrieve_for_application_termsofservice_agreementstate]
