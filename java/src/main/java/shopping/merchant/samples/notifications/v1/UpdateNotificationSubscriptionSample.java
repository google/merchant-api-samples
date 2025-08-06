// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

package shopping.merchant.samples.notifications.v1;

// [START merchantapi_update_notification_subscription]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.notifications.v1.NotificationSubscription;
import com.google.shopping.merchant.notifications.v1.NotificationSubscription.NotificationEventType;
import com.google.shopping.merchant.notifications.v1.NotificationSubscriptionName;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceClient;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceSettings;
import com.google.shopping.merchant.notifications.v1.UpdateNotificationSubscriptionRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to update a specific Notification Subscription for a given Merchant
 * Center account.
 */
public class UpdateNotificationSubscriptionSample {

  public static void updateNotificationSubscription(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    NotificationsApiServiceSettings notificationsApiServiceSettings =
        NotificationsApiServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Replace "YOUR_NOTIFICATION_SUBSCRIPTION_ID" with the actual ID.
    String notificationSubscriptionId = "YOUR_NOTIFICATION_SUBSCRIPTION_ID";

    // The event type to update the subscription to.
    NotificationEventType eventType = NotificationEventType.PRODUCT_STATUS_CHANGE;

    // The new callback URI to update the subscription to.
    String callbackUri = "https://an-updated-uri.com";

    // Set to true to update the subscription to apply to all managed accounts.
    boolean allManagedAccounts = true;

    // Creates notification subscription name to identify the subscription.
    NotificationSubscriptionName subscriptionName =
        NotificationSubscriptionName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setNotificationSubscription(notificationSubscriptionId)
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (NotificationsApiServiceClient notificationSubscriptionServiceClient =
        NotificationsApiServiceClient.create(notificationsApiServiceSettings)) {

      // Create the updated NotificationSubscription object.
      NotificationSubscription updatedSubscription =
          NotificationSubscription.newBuilder()
              .setName(subscriptionName.toString())
              .setCallBackUri(callbackUri)
              .setRegisteredEvent(eventType)
              .setAllManagedAccounts(allManagedAccounts)
              .build();

      // Create a FieldMask to specify which fields to update.  This is required for updates.
      FieldMask updateMask =
          FieldMask.newBuilder()
              .addPaths("call_back_uri")
              .addPaths("registered_event")
              .addPaths("all_managed_accounts")
              .build();

      // Create the update request.
      UpdateNotificationSubscriptionRequest request =
          UpdateNotificationSubscriptionRequest.newBuilder()
              .setNotificationSubscription(updatedSubscription)
              .setUpdateMask(updateMask)
              .build();

      System.out.println("Sending update Notification Subscription request:");
      NotificationSubscription response =
          notificationSubscriptionServiceClient.updateNotificationSubscription(request);

      System.out.println("Updated Notification Subscription below:");
      System.out.println(response);

    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    updateNotificationSubscription(config);
  }
}
// [END merchantapi_update_notification_subscription]
