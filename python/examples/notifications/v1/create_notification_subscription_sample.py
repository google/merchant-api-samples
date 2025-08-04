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
"""A module to create NotificationSubscription."""

# [START merchantapi_create_notification_subscription]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_notifications_v1 import CreateNotificationSubscriptionRequest
from google.shopping.merchant_notifications_v1 import NotificationsApiServiceClient
from google.shopping.merchant_notifications_v1 import NotificationSubscription

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def create_notification_subscription():
  """Creates a Notification Subscription for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = NotificationsApiServiceClient(credentials=credentials)

  # The callback URI to be used to push the notification to.
  callback_uri = "https://www.samplesite.com"

  # The event type to register for notifications.
  event_type = (
      NotificationSubscription.NotificationEventType.PRODUCT_STATUS_CHANGE
  )

  # Creates the NotificationSubscription object.
  notification_subscription = NotificationSubscription()
  # Uncomment the next line to create a notification subscription for all
  # managed accounts.
  # notification_subscription.all_managed_accounts = True
  notification_subscription.target_account = _PARENT
  notification_subscription.call_back_uri = callback_uri
  notification_subscription.registered_event = event_type

  # Creates the request object.
  request = CreateNotificationSubscriptionRequest(
      parent=_PARENT, notification_subscription=notification_subscription
  )

  print("Sending create Notification Subscription request:")

  # Makes the API call.
  try:
    response = client.create_notification_subscription(request=request)
    print("Created Notification Subscription below:")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  create_notification_subscription()
# [END merchantapi_create_notification_subscription]
