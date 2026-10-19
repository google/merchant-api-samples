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
"""A module to list quota for a given Merchant Center account."""

# [START merchantapi_list_quota]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping import merchant_quota_v1


def list_quotas(account_id: str):
  """This class demonstrates how to list quota for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = merchant_quota_v1.QuotaServiceClient(credentials=credentials)

  # Creates the request.
  parent = f"accounts/{account_id}"
  request = merchant_quota_v1.ListQuotaGroupsRequest(parent=parent)

  print("Sending list quotas request:")
  # Makes the request.
  try:
    response = client.list_quota_groups(request=request)

    count = 0

    # Iterates over all rows in all pages and prints the quota group in each
    # row. Automatically uses the `next_page_token` if returned to fetch all
    # pages of data.
    for quota in response:
      print(quota)
      count += 1
    print("The following count of quota were returned: ")
    print(count)
    return response
  except RuntimeError as e:
    print(e)
    raise


if __name__ == "__main__":
  _account_id = configuration.Configuration().read_merchant_info()
  list_quotas(_account_id)

# [END merchantapi_list_quota]
