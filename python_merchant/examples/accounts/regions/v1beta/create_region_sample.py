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
"""A module to create a Region."""

# [START createRegion]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import CreateRegionRequest
from google.shopping.merchant_accounts_v1beta import Region
from google.shopping.merchant_accounts_v1beta import RegionsServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def create_region(region_id_to_create):
  """Creates a region for a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = RegionsServiceClient(credentials=credentials)

  # Creates parent to identify where to insert the region.
  parent = get_parent(_ACCOUNT)

  # Creates the request.
  request = CreateRegionRequest(
      parent=parent,
      region_id=region_id_to_create,
      region=Region(
          display_name="New York",
          postal_code_area=Region.PostalCodeArea(
              region_code="US",
              postal_codes=[
                  Region.PostalCodeArea.PostalCodeRange(
                      begin="10001", end="10282"
                  )
              ],
          ),
      ),
  )

  # Makes the request and catches and prints any error messages.
  try:
    response = client.create_region(request=request)
    print("Inserted Region Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


# [END createRegion]

if __name__ == "__main__":
  # The unique ID of this region.
  region_id = "123456AB"
  create_region(region_id)
