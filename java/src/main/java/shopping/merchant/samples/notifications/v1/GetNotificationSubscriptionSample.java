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

// [START merchantapi_get_notification_subscription]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.notifications.v1.GetNotificationSubscriptionRequest;
import com.google.shopping.merchant.notifications.v1.NotificationSubscription;
import com.google.shopping.merchant.notifications.v1.NotificationSubscriptionName;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceClient;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to get a specific Notification Subscription for a given Merchant
 * Center account.
 */
public class GetNotificationSubscriptionSample {

  public static void getNotificationSubscription(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    NotificationsApiServiceSettings notificationsApiServiceSettings =
        NotificationsApiServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Replace "YOUR_NOTIFICATION_SUBSCRIPTION_ID" with the actual ID.
    String notificationSubscriptionId = "YOUR_NOTIFICATION_SUBSCRIPTION_ID";

    // Creates notification subscription name to identify the subscription.
    String name =
        NotificationSubscriptionName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setNotificationSubscription(notificationSubscriptionId)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (NotificationsApiServiceClient notificationSubscriptionServiceClient =
        NotificationsApiServiceClient.create(notificationsApiServiceSettings)) {

      // The name has the format:
      // accounts/{account}/notificationSubscription/{notification_subscription}
      GetNotificationSubscriptionRequest request =
          GetNotificationSubscriptionRequest.newBuilder().setName(name).build();

      System.out.println("Sending get Notification Subscription request:");
      NotificationSubscription response =
          notificationSubscriptionServiceClient.getNotificationSubscription(request);

      System.out.println("Retrieved Notification Subscription below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    getNotificationSubscription(config);
  }
}
// [END merchantapi_get_notification_subscription]
