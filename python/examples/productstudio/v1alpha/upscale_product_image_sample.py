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
"""This class demonstrates how to create upscaled product images."""

# [START merchantapi_upscale_product_image]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_productstudio_v1alpha import ImageServiceClient
from google.shopping.merchant_productstudio_v1alpha import InputImage
from google.shopping.merchant_productstudio_v1alpha import OutputImageConfig
from google.shopping.merchant_productstudio_v1alpha import UpscaleProductImageRequest

# Gets the merchant account ID from the user's configuration.
_ACCOUNT = configuration.Configuration().read_merchant_info()
# The name of the account to which the request is sent.
# Format: accounts/{account}
_NAME = f"accounts/{_ACCOUNT}"


def upscale_product_image(image_uri: str) -> None:
  """Upscales a product image.

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

  # Creates the request.
  request = UpscaleProductImageRequest(
      name=_NAME, output_config=output_config, input_image=input_image
  )

  # Makes the request and catches and prints any error messages.
  try:
    print(f"Sending UpscaleProductImage request: {_NAME}")
    response = client.upscale_product_image(request=request)
    print("Upscaled product image response below:")
    print(response)
  except RuntimeError as e:
    print("Request failed.")
    print(e)


if __name__ == "__main__":
  # The URI of the image to upscale.
  # Replace with your image URI.
  _IMAGE_URI = "https://services.google.com/fh/files/misc/ring_image_400_600.jpg"
  upscale_product_image(_IMAGE_URI)


# [END merchantapi_upscale_product_image]
