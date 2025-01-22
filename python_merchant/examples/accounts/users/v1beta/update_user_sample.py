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
"""A module to update a user."""


# [START updateUser]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_accounts_v1beta import AccessRight
from google.shopping.merchant_accounts_v1beta import UpdateUserRequest
from google.shopping.merchant_accounts_v1beta import User
from google.shopping.merchant_accounts_v1beta import UserServiceClient

FieldMask = field_mask_pb2.FieldMask

_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_user(user_email, user_access_right):
  """Updates a user to make it an admin of the MC account."""

  credentials = generate_user_credentials.main()

  client = UserServiceClient(credentials=credentials)

  # Create user name string
  name = "accounts/" + _ACCOUNT + "/users/" + user_email

  user = User(name=name, access_rights=[user_access_right])

  field_mask = FieldMask(paths=["access_rights"])

  try:
    request = UpdateUserRequest(user=user, update_mask=field_mask)

    print("Sending Update User request")
    response = client.update_user(request=request)
    print("Updated User Name below")
    print(response.name)
  except RuntimeError as e:
    print(e)


# [END updateUser]

if __name__ == "__main__":
  # Modify this email to update the right user
  email = "USER_MAIL_ACCOUNT"
  access_right = AccessRight.ADMIN
  update_user(email, access_right)
