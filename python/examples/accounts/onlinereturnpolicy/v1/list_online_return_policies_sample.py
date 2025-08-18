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

"""Lists the online return policies for a given account."""

# [START merchantapi_list_online_return_policies]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import ListOnlineReturnPoliciesRequest
from google.shopping.merchant_accounts_v1 import OnlineReturnPolicyServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def list_online_return_policies() -> None:
  """Lists the online return policies for a given account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OnlineReturnPolicyServiceClient(credentials=credentials)

  # Creates the request.
  # The parent has the format: accounts/{account}
  request = ListOnlineReturnPoliciesRequest(parent=_PARENT, page_size=20)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending List OnlineReturnPolicies request:")
    # Issues the request and iterates over the response.
    response = client.list_online_return_policies(request=request)

    count = 0
    # Iterates through the OnlineReturnPolicy and prints each one.
    for online_return_policy in response:
      print(online_return_policy)
      count += 1

    print("The following count of OnlineReturnPolicies is returned: ")
    print(count)

  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  list_online_return_policies()


# [END merchantapi_list_online_return_policies]
