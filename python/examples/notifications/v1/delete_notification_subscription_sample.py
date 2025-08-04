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
"""A module to delete NotificationSubscription."""

# [START merchantapi_delete_notification_subscription]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_notifications_v1 import DeleteNotificationSubscriptionRequest
from google.shopping.merchant_notifications_v1 import NotificationsApiServiceClient


_ACCOUNT = configuration.Configuration().read_merchant_info()


def delete_notification_subscription():
  """Deletes a specific Notification Subscription for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = NotificationsApiServiceClient(credentials=credentials)

  # Replace "YOUR_NOTIFICATION_SUBSCRIPTION_ID" with the actual ID.
  notification_subscription_id = "YOUR_NOTIFICATION_SUBSCRIPTION_ID"

  # Creates notification subscription name to identify the subscription.
  name = f"accounts/{_ACCOUNT}/notificationsubscriptions/{notification_subscription_id}"

  # Creates the request.
  request = DeleteNotificationSubscriptionRequest(name=name)

  print("Sending delete Notification Subscription request:")
  # Makes the request and catches and prints any error messages.
  try:
    client.delete_notification_subscription(request=request)
    print(f"Deleted Notification Subscription: {name}")
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  delete_notification_subscription()
# [END merchantapi_delete_notification_subscription]
