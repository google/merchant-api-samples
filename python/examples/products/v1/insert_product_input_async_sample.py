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
"""A module to insert product inputs asynchronously."""

# [START merchantapi_insert_product_input_async]
import asyncio
import functools
import random
import string

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_products_v1 import InsertProductInputRequest
from google.shopping.merchant_products_v1 import ProductAttributes
from google.shopping.merchant_products_v1 import ProductInput
from google.shopping.merchant_products_v1 import ProductInputsServiceAsyncClient
from google.shopping.merchant_products_v1 import Shipping
from google.shopping.type import Price

# Read merchant account information from the configuration file.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
# The parent account for the product input.
# Format: accounts/{account}
_PARENT = f"accounts/{_ACCOUNT_ID}"


def _generate_random_string(length: int = 8) -> str:
  """Generates a random string of a given length."""
  characters = string.ascii_letters + string.digits
  return "".join(random.choice(characters) for _ in range(length))


def _create_random_product() -> ProductInput:
  """Creates a ProductInput with random elements and predefined attributes."""
  price = Price(amount_micros=33450000, currency_code="USD")

  shipping1 = Shipping(price=price, country="GB", service="1st class post")
  shipping2 = Shipping(price=price, country="FR", service="1st class post")

  attributes = ProductAttributes(
      title="Async - A Tale of Two Cities",
      description="A classic novel about the French Revolution",
      link="https://exampleWebsite.com/tale-of-two-cities.html",
      image_link="https://exampleWebsite.com/tale-of-two-cities.jpg",
      availability="in stock",
      condition="new",
      google_product_category="Media > Books",
      gtins=["9780007350896"],
      shipping=[shipping1, shipping2],
  )

  return ProductInput(
      content_language="en",
      feed_label="US",
      offer_id=_generate_random_string(),
      product_attributes=attributes,
  )


def print_product_input(i, task):
  print("Inserted ProductInput number: ", i)
  # task.result() contains the response from async_insert_product_input
  print(task.result())


async def async_insert_product_input(
    client: ProductInputsServiceAsyncClient, request: InsertProductInputRequest
):
  """Inserts product inputs.

  Args:
    client: The ProductInputsServiceAsyncClient to use.
    request: The InsertProductInputRequest to send.

  Returns:
    The response from the insert_produc_input request.
  """

  print("Sending insert product input requests")

  try:
    response = await client.insert_product_input(request=request)
    # The response is an async corouting inserting the ProductInput.
    return response
  except RuntimeError as e:
    # Catch and print any exceptions that occur during the API calls.
    print(e)


async def main():
  # The ID of the data source that will own the product input.
  # This is a placeholder and should be replaced with an actual data source ID.
  datasource_id = "<INSERT_DATA_SOURCE_ID_HERE>"
  data_source_name = f"accounts/{_ACCOUNT_ID}/dataSources/{datasource_id}"

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a ProductInputsServiceClient.
  client = ProductInputsServiceAsyncClient(credentials=credentials)
  tasks = []
  for i in range(5):
    product_input = _create_random_product()
    request = InsertProductInputRequest(
        parent=_PARENT,
        data_source=data_source_name,
        product_input=product_input,
    )
    # Create the async task
    insert_product_task = asyncio.create_task(
        async_insert_product_input(client, request)
    )
    # Add the callback
    callback_function = functools.partial(print_product_input, i)
    insert_product_task.add_done_callback(callback_function)
    # Add the task to our list
    tasks.append(insert_product_task)

  # Await all tasks to complete concurrently
  # The print_product_input callback will be called for each as it finishes
  await asyncio.gather(*tasks)


if __name__ == "__main__":
  asyncio.run(main())
# [END merchantapi_insert_product_input_async]
