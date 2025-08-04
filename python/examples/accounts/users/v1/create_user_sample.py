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
"""A module to create a user."""


# [START merchantapi_create_user]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import AccessRight
from google.shopping.merchant_accounts_v1 import CreateUserRequest
from google.shopping.merchant_accounts_v1 import User
from google.shopping.merchant_accounts_v1 import UserServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_parent(account_id):
  return f"accounts/{account_id}"


def create_user(user_email):
  """Creates a user for a Merchant Center account."""

  # Get OAuth credentials
  credentials = generate_user_credentials.main()

  # Create a UserServiceClient
  client = UserServiceClient(credentials=credentials)

  # Create parent string
  parent = get_parent(_ACCOUNT)

  # Create the request
  request = CreateUserRequest(
      parent=parent,
      user_id=user_email,
      user=User(
          access_rights=[AccessRight.ADMIN, AccessRight.PERFORMANCE_REPORTING]
      ),
  )

  try:
    print("Sending Create User request")
    response = client.create_user(request=request)
    print("Inserted User Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # Modify this email to create a new user
  email = "USER_MAIL_ACCOUNT"
  create_user(email)

# [END merchantapi_create_user]
