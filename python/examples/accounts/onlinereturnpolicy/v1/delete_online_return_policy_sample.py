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

"""Sample for deleting an online return policy."""
# [START merchantapi_delete_online_return_policy]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import DeleteOnlineReturnPolicyRequest
from google.shopping.merchant_accounts_v1 import OnlineReturnPolicyServiceClient


_ACCOUNT = configuration.Configuration().read_merchant_info()


def delete_online_return_policy(account_id: str, return_policy_id: str) -> None:
  """Deletes an online return policy.

  Args:
    account_id: The merchant account ID.
    return_policy_id: The ID of the return policy to delete.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = OnlineReturnPolicyServiceClient(credentials=credentials)

  # The name has the format:
  # accounts/{account}/onlineReturnPolicies/{return_policy}
  name = f"accounts/{account_id}/onlineReturnPolicies/{return_policy_id}"

  # Creates the request.
  request = DeleteOnlineReturnPolicyRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending Delete OnlineReturnPolicy request:")
    client.delete_online_return_policy(request=request)
    print("Deleted OnlineReturnPolicy successfully.")
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # The ID of the account from which to delete the return policy.
  account_id_ = _ACCOUNT
  # The ID of the return policy to delete.
  # This is the `returnPolicyId` field of the `OnlineReturnPolicy` resource.
  return_policy_id_ = "<SET_RETURN_POLICY_ID>"
  delete_online_return_policy(account_id_, return_policy_id_)


# [END merchantapi_delete_online_return_policy]
