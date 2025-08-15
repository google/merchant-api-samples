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

# [START merchantapi_update_account_relationship]
"""Sample for updating an account relationship."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1 import AccountRelationship
from google.shopping.merchant_accounts_v1 import AccountRelationshipsServiceClient
from google.shopping.merchant_accounts_v1 import UpdateAccountRelationshipRequest

# Gets the account ID from the configuration file.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def update_account_relationship(account_id: str, provider_id: int) -> None:
  """Updates a business relationship."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = AccountRelationshipsServiceClient(credentials=credentials)

  # Creates the name of the relationship to update.
  # The name has the format: accounts/{account}/relationships/{provider}
  name = f"accounts/{account_id}/relationships/{provider_id}"

  # Creates an AccountRelationship with the updated fields.
  account_relationship = AccountRelationship()
  account_relationship.name = name
  account_relationship.account_id_alias = "alias"

  # Creates a field mask to specify which fields to update.
  field_mask = field_mask_pb2.FieldMask(paths=["account_id_alias"])

  # Creates the request.
  request = UpdateAccountRelationshipRequest(
      account_relationship=account_relationship,
      update_mask=field_mask,
  )

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Update AccountRelationship request")
    response = client.update_account_relationship(request=request)
    print("Updated AccountRelationship below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # The provider ID of the relationship to update.
  provider_id_ = 111
  update_account_relationship(_ACCOUNT_ID, provider_id_)

# [END merchantapi_update_account_relationship]
