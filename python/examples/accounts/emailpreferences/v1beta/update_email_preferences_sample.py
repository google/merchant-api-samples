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
"""A module to update the email preferences of specific user."""

# [START merchantapi_update_email_preferences]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1beta import EmailPreferences
from google.shopping.merchant_accounts_v1beta import EmailPreferencesServiceClient
from google.shopping.merchant_accounts_v1beta import UpdateEmailPreferencesRequest


FieldMask = field_mask_pb2.FieldMask
_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_email_preferences(email_address):
  """Updates a EmailPreferences to OPT_IN to News and Tips."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = EmailPreferencesServiceClient(credentials=credentials)

  # Creates EmailPreferences name to identify EmailPreferences.
  name = (
      "accounts/" + _ACCOUNT + "/users/" + email_address + "/emailPreferences"
  )

  # Create a EmailPreferences with the updated fields.
  email_preferences = EmailPreferences(
      name=name, news_and_tips=EmailPreferences.OptInState.OPTED_IN
  )

  # Create field mask
  field_mask = FieldMask(paths=["news_and_tips"])

  # Creates the request.
  request = UpdateEmailPreferencesRequest(
      email_preferences=email_preferences, update_mask=field_mask
  )

  # Makes the request and catches and prints any error messages.
  try:
    response = client.update_email_preferences(request=request)
    print("Updated EmailPreferences Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # The email address of this user. If you want to get the user information
  # of the user making the Content API request, you can also use "me" instead
  # of an email address.
  # email = "testUser@gmail.com"
  email = "me"

  update_email_preferences(email)

# [END merchantapi_update_email_preferences]
