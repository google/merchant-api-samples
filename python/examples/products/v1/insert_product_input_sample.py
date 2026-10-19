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
"""A module to insert a Product Input."""

# [START merchantapi_insert_product_input]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_products_v1
from google.shopping.merchant_products_v1 import Availability
from google.shopping.merchant_products_v1 import Condition
from google.shopping.type import Price


def create_product_input():
  """Creates a `ProductInput` resource."""

  # Creates a shipping setting
  price = Price()
  price.amount_micros = 33_450_000
  price.currency_code = "GBP"

  shipping_option_1 = merchant_products_v1.Shipping()
  shipping_option_1.price = price
  shipping_option_1.country = "GB"
  shipping_option_1.service = "1st class post"

  price2 = Price()
  price2.amount_micros = 33_450_000
  price2.currency_code = "EUR"

  shipping_option_2 = merchant_products_v1.Shipping()
  shipping_option_2.price = price2
  shipping_option_2.country = "FR"
  shipping_option_2.service = "2nd class post"

  # Sets product attributes. Make sure to replace these values with your own.
  attributes = merchant_products_v1.ProductAttributes()
  attributes.title = "A Tale of Two Cities"
  attributes.description = "A classic novel about the French Revolution"
  attributes.link = "https://exampleWebsite.com/tale-of-two-cities.html"
  attributes.image_link = "https://exampleWebsite.com/tale-of-two-cities.jpg"
  attributes.price = price
  attributes.availability = Availability.IN_STOCK
  attributes.condition = Condition.NEW
  attributes.google_product_category = "Media > Books"
  attributes.gtins = ["9780007350896"]
  attributes.shipping = [shipping_option_1, shipping_option_2]

  return merchant_products_v1.ProductInput(
      content_language="en",
      feed_label="GB",
      offer_id="sku123",
      product_attributes=attributes,
  )


def insert_product_input(account_id: str, data_source: str):
  """Inserts the specified `ProductInput` resource.

  Args:
    account_id: The Merchant Center account ID.
    data_source: The resource name of the data source that will own the product
      input.

  Returns:
    The inserted `ProductInput` resource.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_products_v1.ProductInputsServiceClient(
      credentials=credentials
  )

  parent = f"accounts/{account_id}"

  # Creates the request.
  request = merchant_products_v1.InsertProductInputRequest(
      parent=parent,
      # If this product is already owned by another datasource, when
      # re-inserting, the new datasource will take ownership of the product.
      product_input=create_product_input(),
      data_source=data_source,
  )

  # Makes the request and catches and prints any error messages.
  try:
    response = client.insert_product_input(request=request)
    # The last part of the product name will be the product ID assigned to a
    # product by Google. Product ID has the format
    # `contentLanguage~feedLabel~offerId`
    print(f"Input successful: {response}")
    return response
  except RuntimeError as e:
    print("Input failed")
    print(e)
    # After the product is inserted, the product ID will be returned in the
    # response. We recommend that you check the Merchant Center to ensure that
    # the product is approved and visible to users before using the product ID
    # in any downstream processes.
    raise


if __name__ == "__main__":
  # Retrieves the Merchant Center account ID from the configuration file.
  merchant_account_id = configuration.Configuration().read_merchant_info()
  # Identifies the data source that will own the product input.
  # You can only insert products into datasource types of Input "API" and
  # "FILE", and of Type "Primary" or "Supplemental."
  target_data_source = (
      f"accounts/{merchant_account_id}/dataSources/[INSERT_DATA_SOURCE_HERE]"
  )

  insert_product_input(merchant_account_id, target_data_source)

# [END merchantapi_insert_product_input]
