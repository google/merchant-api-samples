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

"""Gets an account relationship.

Retrieves an existing account relationship between a merchant and a provider.
"""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccountRelationshipsServiceClient
from google.shopping.merchant_accounts_v1 import GetAccountRelationshipRequest

# [START merchantapi_get_account_relationship]
# Gets the account ID from the configuration file.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def get_account_relationship(account_id: str, provider_id: int) -> None:
  """Gets an account relationship."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountRelationshipsServiceClient(credentials=credentials)

  # Creates the name of the relationship to retrieve.
  # The name has the format: accounts/{account}/relationships/{provider}
  name = f"accounts/{account_id}/relationships/{provider_id}"

  # Creates the request.
  request = GetAccountRelationshipRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Get Account Relationship request:")
    response = client.get_account_relationship(request=request)
    print("Retrieved Account Relationship below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # The provider ID of the relationship to retrieve.
  provider_id_ = 111
  get_account_relationship(_ACCOUNT_ID, provider_id_)

# [END merchantapi_get_account_relationship]
