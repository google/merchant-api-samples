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

package shopping.merchant.samples.notifications.v1beta;

// [START merchantapi_create_notification_subscription]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.notifications.v1beta.CreateNotificationSubscriptionRequest;
import com.google.shopping.merchant.notifications.v1beta.NotificationSubscription;
import com.google.shopping.merchant.notifications.v1beta.NotificationSubscription.NotificationEventType;
import com.google.shopping.merchant.notifications.v1beta.NotificationsApiServiceClient;
import com.google.shopping.merchant.notifications.v1beta.NotificationsApiServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to create a Notification Subscription for a given Merchant Center
 * account.
 */
public class CreateNotificationSubscriptionSample {

  public static void createNotificationSubscription(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    NotificationsApiServiceSettings notificationsApiServiceSettings =
        NotificationsApiServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (NotificationsApiServiceClient notificationsApiServiceClient =
        NotificationsApiServiceClient.create(notificationsApiServiceSettings)) {

      // The parent has the format: accounts/{account}
      String parent = "accounts/" + config.getAccountId().toString();

      // INSERT HERE the URL to be used to push the notification to.
      String callbackUri = "https://www.samplesite.com";

      // The event type to register for notifications.
      NotificationEventType eventType = NotificationEventType.PRODUCT_STATUS_CHANGE;

      // Create the NotificationSubscription object.
      NotificationSubscription notificationSubscription =
          NotificationSubscription.newBuilder()
              // Uncomment the next line to create a notification subscription for all managed
              // accounts.
              // .setAllManagedAccounts(true)
              .setTargetAccount(parent)
              .setCallBackUri(callbackUri)
              .setRegisteredEvent(eventType)
              .build();

      // Create the request object.
      CreateNotificationSubscriptionRequest request =
          CreateNotificationSubscriptionRequest.newBuilder()
              .setParent(parent)
              .setNotificationSubscription(notificationSubscription)
              .build();

      System.out.println("Sending create Notification Subscription request:");

      // Make the API call.
      NotificationSubscription response =
          notificationsApiServiceClient.createNotificationSubscription(request);

      System.out.println("Created Notification Subscription below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    createNotificationSubscription(config);
  }
}
// [END merchantapi_create_notification_subscription]
