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

# [START merchantapi_update_automaticimprovements]
"""Updates the automatic improvements settings for a Merchant Center account."""
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1beta import AutomaticImageImprovements
from google.shopping.merchant_accounts_v1beta import AutomaticImprovements
from google.shopping.merchant_accounts_v1beta import AutomaticImprovementsServiceClient
from google.shopping.merchant_accounts_v1beta import AutomaticItemUpdates
from google.shopping.merchant_accounts_v1beta import AutomaticShippingImprovements
from google.shopping.merchant_accounts_v1beta import UpdateAutomaticImprovementsRequest


# Fetches the Merchant Center account ID from the configuration.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()

# The resource name for the AutomaticImprovements settings of the account.
# Format: accounts/{account}/automaticImprovements
_AUTOMATIC_IMPROVEMENTS_RESOURCE_NAME = (
    f"accounts/{_ACCOUNT_ID}/automaticImprovements"
)


def update_automatic_improvements_settings():
  """Updates automatic improvements settings for a Merchant Center account to enable all available automatic improvements.
  """

  # Generates OAuth 2.0 credentials for authenticating with the API.
  credentials = generate_user_credentials.main()

  # Creates a client for the AutomaticImprovementsService.
  client = AutomaticImprovementsServiceClient(credentials=credentials)

  # Prepares the AutomaticImprovements object with all improvements enabled.
  # The 'name' field specifies the AutomaticImprovements resource to update.
  automatic_improvements_config = AutomaticImprovements(
      name=_AUTOMATIC_IMPROVEMENTS_RESOURCE_NAME,
      item_updates=AutomaticItemUpdates(
          account_item_updates_settings=AutomaticItemUpdates.ItemUpdatesAccountLevelSettings(
              allow_price_updates=True,
              allow_availability_updates=True,
              allow_strict_availability_updates=True,
              allow_condition_updates=True,
          )
      ),
      image_improvements=AutomaticImageImprovements(
          account_image_improvements_settings=
          AutomaticImageImprovements.ImageImprovementsAccountLevelSettings(
              allow_automatic_image_improvements=True
          )
      ),
      shipping_improvements=AutomaticShippingImprovements(
          allow_shipping_improvements=True
      ),
  )

  # Creates a field mask to specify which fields of the
  # AutomaticImprovements resource should be updated.
  # Using "*" indicates that all fields provided in the
  # automatic_improvements_config object should be updated.
  field_mask = field_mask_pb2.FieldMask(paths=["*"])

  # Creates the update request, including the configured
  # AutomaticImprovements object and the field mask.
  request = UpdateAutomaticImprovementsRequest(
      automatic_improvements=automatic_improvements_config,
      update_mask=field_mask,
  )

  # Sends the request to update automatic improvements and handles the response.
  try:
    print("Sending Update AutomaticImprovements request")
    response = client.update_automatic_improvements(request=request)
    print("Updated AutomaticImprovements Name below")
    print(response.name)
  except RuntimeError as e:
    # Catches and prints any errors that occur during the API call.
    print(e)


if __name__ == "__main__":
  update_automatic_improvements_settings()

# [END merchantapi_update_automaticimprovements]
