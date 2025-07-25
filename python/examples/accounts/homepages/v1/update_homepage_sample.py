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
"""A module to update a Homepage."""

# [START merchantapi_update_homepage]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1beta import Homepage
from google.shopping.merchant_accounts_v1beta import HomepageServiceClient
from google.shopping.merchant_accounts_v1beta import UpdateHomepageRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_homepage(new_uri):
  """Updates a homepage to a new URL."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = HomepageServiceClient(credentials=credentials)

  # Creates Homepage name to identify Homepage.
  name = "accounts/" + _ACCOUNT + "/homepage"

  # Create a homepage with the updated fields.
  homepage = Homepage(name=name, uri=new_uri)

  # Create a FieldMask for the "uri" field.
  field_mask = field_mask_pb2.FieldMask(paths=["uri"])

  # Creates the request.
  request = UpdateHomepageRequest(homepage=homepage, update_mask=field_mask)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.update_homepage(request=request)
    print("Updated Homepage Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # The URI (a URL) of the store's homepage.
  uri = "https://example.com"
  update_homepage(uri)

# [END merchantapi_update_homepage]
