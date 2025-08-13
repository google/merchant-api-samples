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

# [START merchantapi_update_product_input]
"""A module to update a product input."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_products_v1 import Availability
from google.shopping.merchant_products_v1 import Condition
from google.shopping.merchant_products_v1 import ProductAttributes
from google.shopping.merchant_products_v1 import ProductInput
from google.shopping.merchant_products_v1 import ProductInputsServiceClient
from google.shopping.merchant_products_v1 import UpdateProductInputRequest
from google.shopping.type import CustomAttribute


# Fetches the Merchant Center account ID from the authentication examples.
# This ID is needed to construct resource names for the API.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def update_product_input(account_id: str, product_id: str, data_source_id: str):
  """Updates an existing product input for a specific account.

  Args:
    account_id: The Merchant Center account ID.
    product_id: The ID of the product input to update. This ID is assigned by
      Google and has the format `contentLanguage~feedLabel~offerId`.
    data_source_id: The ID of the data source that owns the product input.
  """

  # Obtains OAuth credentials for authentication.
  credentials = generate_user_credentials.main()

  # Creates a ProductInputsServiceClient instance.
  client = ProductInputsServiceClient(credentials=credentials)

  # Constructs the full resource name for the product input.
  # Format: accounts/{account}/productInputs/{productinput}
  name = f"accounts/{account_id}/productInputs/{product_id}"

  # Defines the FieldMask to specify which fields of the product input
  # are being updated. Only 'attributes' and 'custom_attributes' can be updated.
  field_mask = field_mask_pb2.FieldMask(
      paths=[
          "product_attributes.title",
          "product_attributes.description",
          "product_attributes.link",
          "product_attributes.image_link",
          "product_attributes.availability",
          "product_attributes.condition",
          "product_attributes.gtins",
          "custom_attributes.mycustomattribute",
      ]
  )

  # Prepares the new attribute values for the product.
  attributes = ProductAttributes(
      title="A Tale of Two Cities updated",
      description="A classic novel about the French Revolution",
      link="https://exampleWebsite.com/tale-of-two-cities.html",
      image_link="https://exampleWebsite.com/tale-of-two-cities.jpg",
      availability=Availability.IN_STOCK,
      condition=Condition.NEW,
      gtins=["9780007350896"],  # GTIN is a repeated field.
  )

  # Constructs the full resource name for the data source.
  # The data source can be primary or supplemental.
  # Format: accounts/{account}/dataSources/{datasource}
  data_source = f"accounts/{account_id}/dataSources/{data_source_id}"

  # Prepares the ProductInput object with the updated information.
  product_input_data = ProductInput(
      name=name,
      product_attributes=attributes,
      custom_attributes=[
          CustomAttribute(
              name="mycustomattribute", value="Example value"
          )
      ],
  )

  # Creates the UpdateProductInputRequest.
  request = UpdateProductInputRequest(
      update_mask=field_mask,
      data_source=data_source,
      product_input=product_input_data,
  )

  # Sends the update request to the API.
  try:
    print("Sending update ProductInput request")
    response = client.update_product_input(request=request)
    print("Updated ProductInput Name below")
    # The response includes the name of the updated product input.
    # The last part of the product name is the product ID assigned by Google.
    print(response.name)
    print("Updated Product below")
    print(response)
  except RuntimeError as e:
    # Catches and prints any errors that occur during the API call.
    print(e)


if __name__ == "__main__":
  # The ID of the product to be updated.
  # This ID is assigned by Google and typically follows the format:
  # contentLanguage~feedLabel~offerId
  # Replace with an actual product ID from your Merchant Center account.
  product_id_to_update = "online~en~label~sku123"

  # The ID of the data source that will own the updated product input.
  # Replace with an actual data source ID from your Merchant Center account.
  data_source_id_for_update = "<INSERT_DATA_SOURCE_ID>"

  update_product_input(
      _ACCOUNT_ID, product_id_to_update, data_source_id_for_update
  )

# [END merchantapi_update_product_input]
