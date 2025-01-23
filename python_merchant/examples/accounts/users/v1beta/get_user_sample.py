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
"""A module to get a user."""


# [START getUser]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import GetUserRequest
from google.shopping.merchant_accounts_v1beta import UserServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_user(user_email):
  """Gets a single user for a given Merchant Center account."""

  # Get OAuth credentials
  credentials = generate_user_credentials.main()

  # Create a UserServiceClient
  client = UserServiceClient(credentials=credentials)

  # Create user name string
  name = "accounts/" + _ACCOUNT + "/users/" + user_email

  # Create the request
  request = GetUserRequest(name=name)

  try:
    print("Sending Get user request:")
    response = client.get_user(request=request)
    print("Retrieved User below")
    print(response)
  except RuntimeError as e:
    print(e)


# [END getUser]

if __name__ == "__main__":
  # Modify this email to get the user details
  email = "USER_MAIL_ACCOUNT"
  get_user(email)
