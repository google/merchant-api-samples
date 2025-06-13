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

# [START merchantapi_generate_product_text_suggestions]
"""A module to generate product text suggestions."""

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_productstudio_v1alpha import GenerateProductTextSuggestionsRequest
from google.shopping.merchant_productstudio_v1alpha import OutputSpec
from google.shopping.merchant_productstudio_v1alpha import ProductInfo
from google.shopping.merchant_productstudio_v1alpha import TextSuggestionsServiceClient


# Fetches the Merchant Center account ID from the configuration file.
# This ID is used to construct the 'name' for the API request.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
# The parent resource name for the GenerateProductTextSuggestionsRequest.
# Format: "accounts/{account}"
_PARENT_RESOURCE_NAME = f"accounts/{_ACCOUNT_ID}"


def generate_product_text_suggestions_sample():
  """Generates product text suggestions for a given product."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client for the TextSuggestionsService.
  client = TextSuggestionsServiceClient(credentials=credentials)

  # Defines the product information for which suggestions are needed.
  # This includes attributes like title and description.
  product_info = ProductInfo(
      product_attributes={
          "title": "Mens shirt",
          "description": "A blue shirt for men in size S",
      }
  )

  # Defines the output specification.
  # The 'workflow_id' specifies the type of text suggestion, e.g., "title".
  output_spec = OutputSpec(workflow_id="title")

  # Creates the request object for generating product text suggestions.
  # It includes the parent resource name, product information, and output
  # specification.
  request = GenerateProductTextSuggestionsRequest(
      name=_PARENT_RESOURCE_NAME,
      product_info=product_info,
      output_spec=output_spec,
  )

  # Sends the request to the API.
  print(
      f"Sending GenerateProductTextSuggestions request: {_PARENT_RESOURCE_NAME}"
  )
  try:
    response = client.generate_product_text_suggestions(request=request)
    # Prints the generated suggestions.
    print("Generated product text suggestions response below:")
    print(response)
  except RuntimeError as e:
    # Catches and prints any errors that occur during the API call.
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  generate_product_text_suggestions_sample()

# [END merchantapi_generate_product_text_suggestions]
