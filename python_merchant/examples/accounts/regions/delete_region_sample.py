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
"""A module to delete a Region."""

# [START deleteRegion]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import DeleteRegionRequest
from google.shopping.merchant_accounts_v1beta import RegionsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def delete_region(region_id_to_delete):
  """Deletes a given region from a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = RegionsServiceClient(credentials=credentials)

  # Creates Region name to identify the Region you want to delete.
  region_name = "accounts/" + _ACCOUNT + "/regions/" + region_id_to_delete

  # Creates the request.
  request = DeleteRegionRequest(name=region_name)

  # Makes the request and catches and prints any error messages.
  try:
    client.delete_region(request=request)
    print("Delete successful.")
  except RuntimeError as e:
    print("Delete failed")
    print(e)


# [END deleteRegion]

if __name__ == "__main__":
  region_id = "123456AB"
  delete_region(region_id)
