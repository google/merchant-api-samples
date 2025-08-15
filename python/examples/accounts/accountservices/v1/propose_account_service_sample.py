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
# [START merchantapi_propose_account_service]
"""This class demonstrates how to propose an account service."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountAggregation
from google.shopping.merchant_accounts_v1 import AccountService
from google.shopping.merchant_accounts_v1 import AccountServicesServiceClient
from google.shopping.merchant_accounts_v1 import ProposeAccountServiceRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def propose_account_service(provider_id: int) -> None:
  """Proposes an account service.

  Args:
    provider_id: The Merchant Center ID of the provider.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountServicesServiceClient(credentials=credentials)

  # Creates the provider resource name from the provider ID.
  provider = f"accounts/{provider_id}"

  # Creates an AccountService object.
  # For this request, only `account_aggregation` is needed.
  account_service = AccountService()
  account_service.account_aggregation = AccountAggregation()

  # Creates the request.
  request = ProposeAccountServiceRequest(
      parent=_PARENT,
      provider=provider,
      account_service=account_service,
  )

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Propose AccountService request")
    response = client.propose_account_service(request=request)
    print("Proposed AccountService below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Update this with the Merchant Center provider ID you want to get the
  # relationship for.
  provider_id_ = 111
  propose_account_service(provider_id_)

# [END merchantapi_propose_account_service]
