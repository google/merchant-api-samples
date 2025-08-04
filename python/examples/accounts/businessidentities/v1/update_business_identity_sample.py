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
"""A module to update BusinessIdentity."""

# [START merchantapi_update_business_identity]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1 import BusinessIdentity
from google.shopping.merchant_accounts_v1 import BusinessIdentityServiceClient
from google.shopping.merchant_accounts_v1 import UpdateBusinessIdentityRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_business_identity():
  """Updates a business identity of a Merchant Center account."""

  credentials = generate_user_credentials.main()

  client = BusinessIdentityServiceClient(credentials=credentials)

  # Creates BusinessIdentity name to identify BusinessIdentity.
  name = "accounts/" + _ACCOUNT + "/businessIdentity"

  # Create a BusinessIdentity with the updated fields.
  business_identity = BusinessIdentity(
      name=name,
      small_business=BusinessIdentity.IdentityAttribute(
          identity_declaration=BusinessIdentity.IdentityAttribute.IdentityDeclaration.SELF_IDENTIFIES_AS,
      ),
  )

  field_mask = field_mask_pb2.FieldMask(paths=["small_business"])

  request = UpdateBusinessIdentityRequest(
      business_identity=business_identity, update_mask=field_mask
  )

  try:
    response = client.update_business_identity(request=request)
    print("Updated BusinessIdentity below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  update_business_identity()

# [END merchantapi_update_business_identity]
