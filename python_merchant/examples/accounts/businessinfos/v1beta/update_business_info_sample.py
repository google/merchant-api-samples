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
"""A module to update BusinessInfo."""


# [START updateBusinessInfo]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1beta import BusinessInfo
from google.shopping.merchant_accounts_v1beta import BusinessInfoServiceClient
from google.shopping.merchant_accounts_v1beta import UpdateBusinessInfoRequest
from google.type import postal_address_pb2

FieldMask = field_mask_pb2.FieldMask
_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_business_info():
  """Updates a BusinessInfo to a new address."""

  # Get OAuth credentials
  credentials = generate_user_credentials.main()

  # Create a BusinessInfoServiceClient
  business_info_service_client = BusinessInfoServiceClient(
      credentials=credentials
  )

  # Create BusinessInfo name
  name = "accounts/" + _ACCOUNT + "/businessInfo"

  # Create a PostalAddress
  address = postal_address_pb2.PostalAddress(
      language_code="en",
      postal_code="C1107",
      address_lines=[
          "Av. Alicia Moreau de Justo 350, Cdad. Autónoma de Buenos Aires,"
          " Argentina"
      ],
  )

  # Create a BusinessInfo object
  business_info = BusinessInfo(name=name, address=address)

  # Create a FieldMask
  field_mask = FieldMask(paths=["address"])

  # Create the request
  request = UpdateBusinessInfoRequest(
      business_info=business_info, update_mask=field_mask
  )

  # Call the API and print the response
  try:
    print("Sending Update BusinessInfo request")
    response = business_info_service_client.update_business_info(
        request=request
    )
    print("Updated BusinessInfo Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


# [END updateBusinessInfo]

if __name__ == "__main__":
  update_business_info()
