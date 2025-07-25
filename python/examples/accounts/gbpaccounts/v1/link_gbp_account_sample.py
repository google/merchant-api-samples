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
"""This class demonstrates how to link the specified merchant to a GBP account."""

# [START merchantapi_link_gbp_account]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import GbpAccountsServiceClient
from google.shopping.merchant_accounts_v1beta import LinkGbpAccountRequest

# Gets the merchant account ID from the configuration file.
_ACCOUNT = configuration.Configuration().read_merchant_info()
# Creates the parent resource name string.
_PARENT = f"accounts/{_ACCOUNT}"


def link_gbp_account(gbp_email: str) -> None:
  """Links the specified merchant to a Google Business Profile account.

  Args:
    gbp_email: The email address of the Business Profile account.
  """

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = GbpAccountsServiceClient(credentials=credentials)

  # Creates the request.
  request = LinkGbpAccountRequest(parent=_PARENT, gbp_email=gbp_email)

  # Makes the request and catches and prints any error messages.
  try:
    print("Sending link GBP account request:")
    # An empty response is returned on success.
    client.link_gbp_account(request=request)
    print(f"Successfully linked to GBP account: {gbp_email}")
  except RuntimeError as e:
    print("An error has occured: ")
    print(e)


if __name__ == "__main__":
  # The email address of the Business Profile account.
  _gbp_email = "{GBP_EMAIL}"
  link_gbp_account(_gbp_email)

# [END merchantapi_link_gbp_account]
