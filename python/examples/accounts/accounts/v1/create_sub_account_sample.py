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
"""A module to create and configure a sub-account under an advanced account."""


# [START merchantapi_create_sub_account]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import Account
from google.shopping.merchant_accounts_v1 import AccountAggregation
from google.shopping.merchant_accounts_v1 import AccountsServiceClient
from google.shopping.merchant_accounts_v1 import CreateAndConfigureAccountRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def create_sub_account():
  """Creates a sub-account under an advanced account."""

  # Get OAuth credentials.
  credentials = generate_user_credentials.main()

  # Create a client.
  client = AccountsServiceClient(credentials=credentials)

  # Get the parent advanced account ID.
  parent = get_parent(_ACCOUNT)

  # Create the request.
  request = CreateAndConfigureAccountRequest(
      account=Account(
          account_name="Demo Business",
          adult_content=False,
          time_zone={"id": "America/New_York"},
          language_code="en-US",
      ),
      service=[
          CreateAndConfigureAccountRequest.AddAccountService(
              provider=parent,
              account_aggregation=AccountAggregation(),
          )
      ],
  )

  # Make the request and print the response.
  try:
    print("Sending Create SubAccount request")
    response = client.create_and_configure_account(request=request)
    print("Inserted Account Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  create_sub_account()

# [END merchantapi_create_sub_account]
