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
"""This class demonstrates how to create product images with generated backgrounds."""

# [START merchantapi_generate_product_image_background]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_productstudio_v1alpha import GenerateImageBackgroundConfig
from google.shopping.merchant_productstudio_v1alpha import GenerateProductImageBackgroundRequest
from google.shopping.merchant_productstudio_v1alpha import ImageServiceClient
from google.shopping.merchant_productstudio_v1alpha import InputImage
from google.shopping.merchant_productstudio_v1alpha import OutputImageConfig


# Gets the merchant account ID from the user's configuration.
_ACCOUNT = configuration.Configuration().read_merchant_info()
# The name of the account to which the request is sent.
# Format: accounts/{account}
_NAME = f"accounts/{_ACCOUNT}"


def generate_product_image_background(image_uri: str) -> None:
  """Generates a product image with a custom background.

  Args:
    image_uri: The URI of the input image.
  """
  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ImageServiceClient(credentials=credentials)

  # Creates the output config.
  # Set `return_image_uri` to False to return the image bytes in the response.
  output_config = OutputImageConfig(return_image_uri=True)

  # Creates the input image.
  # You can also use image bytes here instead of a URI.
  input_image = InputImage(image_uri=image_uri)

  # Creates the generate image background config.
  generate_image_background_config = GenerateImageBackgroundConfig(
      product_description="a jar",
      background_description=(
          "sitting on a cracked stone surface surrounded by a cherry blossom"
          " tree and pink and white flowers in the background, high"
          " resolution, product photography, strong shadows and lights,"
          " creative"
      ),
  )

  # Creates the request.
  request = GenerateProductImageBackgroundRequest(
      name=_NAME,
      output_config=output_config,
      input_image=input_image,
      config=generate_image_background_config,
  )

  # Makes the request and catches and prints any error messages.
  try:
    print(f"Sending GenerateProductImageBackground request: {_NAME}")
    response = client.generate_product_image_background(request=request)
    print("Generated product image background response below:")
    print(response)
  except RuntimeError as e:
    print("Request failed.")
    print(e)


if __name__ == "__main__":
  # The URI of the image to use as the base for the generation.
  # Replace with your image URI.
  _IMAGE_URI = (
      "https://services.google.com/fh/files/misc/abundance_intention_bath_salts.jpg"
  )
  generate_product_image_background(_IMAGE_URI)


# [END merchantapi_generate_product_image_background]
