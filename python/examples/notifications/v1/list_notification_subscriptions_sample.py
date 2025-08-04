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
"""A module to list NotificationSubscriptions."""

# [START merchantapi_list_notification_subscriptions]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_notifications_v1 import ListNotificationSubscriptionsRequest
from google.shopping.merchant_notifications_v1 import NotificationsApiServiceClient

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def list_notification_subscriptions():
  """Lists NotificationSubscriptions for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = NotificationsApiServiceClient(credentials=credentials)

  # Creates the request.
  request = ListNotificationSubscriptionsRequest(parent=_PARENT)

  print("Sending list Notification Subscriptions request:")
  # Makes the request and catches and prints any error messages.
  try:
    response = client.list_notification_subscriptions(request=request)
    count = 0
    for notification_subscription in response:
      print(notification_subscription)
      count += 1
    print(
        "The following count of notification subscriptions were returned:"
        f" {count}"
    )
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  list_notification_subscriptions()
# [END merchantapi_list_notification_subscriptions]
