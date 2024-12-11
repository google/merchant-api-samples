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
"""A module to get the email preferences of specific user."""

# [START getEmailPreferences]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1beta import EmailPreferencesServiceClient
from google.shopping.merchant_accounts_v1beta import GetEmailPreferencesRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def get_email_preferences(email_address):
  """Gets the email preferences of a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = EmailPreferencesServiceClient(credentials=credentials)

  # Creates EmailPreferences name to identify the EmailPreferences.
  name = (
      "accounts/" + _ACCOUNT + "/users/" + email_address + "/emailPreferences"
  )

  # Creates the request.
  request = GetEmailPreferencesRequest(name=name)

  # Makes the request and catches and prints any error messages.
  try:
    response = client.get_email_preferences(request=request)
    print("Retrieved EmailPreferences below")
    print(response)
  except RuntimeError as e:
    print(e)


# [END getEmailPreferences]

if __name__ == "__main__":
  # The email address of this user. If you want to get the user information
  # Of the user making the Content API request, you can also use "me" instead
  # Of an email address.
  # email = "testUser@gmail.com"
  email = "me"

  get_email_preferences(email)
