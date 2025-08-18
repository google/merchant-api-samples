# -*- coding: utf-8 -*-
# Copyright 2025 Google LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

"""This is a sample for creating an online return policy."""

# [START merchantapi_create_online_return_policy]

from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import CreateOnlineReturnPolicyRequest
from google.shopping.merchant_accounts_v1 import OnlineReturnPolicy
from google.shopping.merchant_accounts_v1 import OnlineReturnPolicyServiceClient


_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def create_online_return_policy() -> None:
  """Creates an online return policy."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OnlineReturnPolicyServiceClient(credentials=credentials)

  # Creates an OnlineReturnPolicy and populates its attributes.
  online_return_policy = OnlineReturnPolicy()
  online_return_policy.label = "US Return Policy"
  online_return_policy.return_policy_uri = (
      "https://www.google.com/returnpolicy-sample"
  )
  online_return_policy.countries.append("US")
  online_return_policy.policy.type_ = (
      OnlineReturnPolicy.Policy.Type.LIFETIME_RETURNS
  )
  online_return_policy.item_conditions.append(
      OnlineReturnPolicy.ItemCondition.NEW
  )
  online_return_policy.return_methods.append(
      OnlineReturnPolicy.ReturnMethod.IN_STORE
  )
  online_return_policy.process_refund_days = 10

  # Creates the request.
  request = CreateOnlineReturnPolicyRequest(
      parent=_PARENT, online_return_policy=online_return_policy
  )

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending create OnlineReturnPolicy request:")
    response = client.create_online_return_policy(request=request)
    print("Retrieved OnlineReturnPolicy below")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  create_online_return_policy()


# [END merchantapi_create_online_return_policy]
