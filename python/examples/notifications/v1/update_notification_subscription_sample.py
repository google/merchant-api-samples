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
"""A module to update NotificationSubscription."""

# [START merchantapi_update_notification_subscription]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.protobuf import field_mask_pb2
from google.shopping.merchant_notifications_v1 import NotificationsApiServiceClient
from google.shopping.merchant_notifications_v1 import NotificationSubscription
from google.shopping.merchant_notifications_v1 import UpdateNotificationSubscriptionRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def update_notification_subscription():
  """Updates a specific Notification Subscription for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = NotificationsApiServiceClient(credentials=credentials)

  # Replace "YOUR_NOTIFICATION_SUBSCRIPTION_ID" with the actual ID.
  notification_subscription_id = "YOUR_NOTIFICATION_SUBSCRIPTION_ID"

  # The event type to update the subscription to.
  event_type = (
      NotificationSubscription.NotificationEventType.PRODUCT_STATUS_CHANGE
  )

  # The new callback URI to update the subscription to.
  callback_uri = "https://an-updated-uri.com"

  # Set to true to update the subscription to apply to all managed accounts.
  all_managed_accounts = True

  # Creates notification subscription name to identify the subscription.
  subscription_name = f"accounts/{_ACCOUNT}/notificationsubscriptions/{notification_subscription_id}"

  # Creates the updated NotificationSubscription object.
  updated_subscription = NotificationSubscription()
  updated_subscription.name = subscription_name
  updated_subscription.call_back_uri = callback_uri
  updated_subscription.registered_event = event_type
  updated_subscription.all_managed_accounts = all_managed_accounts

  # Creates a FieldMask to specify which fields to update.
  update_mask = field_mask_pb2.FieldMask(
      paths=["call_back_uri", "registered_event", "all_managed_accounts"]
  )

  # Creates the update request.
  request = UpdateNotificationSubscriptionRequest(
      notification_subscription=updated_subscription, update_mask=update_mask
  )

  print("Sending update Notification Subscription request:")
  # Makes the request and catches and prints any error messages.
  try:
    response = client.update_notification_subscription(request=request)
    print("Updated Notification Subscription below:")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  update_notification_subscription()
# [END merchantapi_update_notification_subscription]
