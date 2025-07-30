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

// [START merchantapi_list_notification_subscriptions]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.api.gax.rpc.ApiException;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.notifications.v1.ListNotificationSubscriptionsRequest;
import com.google.shopping.merchant.notifications.v1.NotificationSubscription;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceClient;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceClient.ListNotificationSubscriptionsPagedResponse;
import com.google.shopping.merchant.notifications.v1.NotificationsApiServiceSettings;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to list NotificationSubscriptions for a given Merchant Center
 * account.
 */
public class ListNotificationSubscriptionsSample {

  public static void listNotificationSubscriptions(Config config) throws Exception {

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

      ListNotificationSubscriptionsRequest request =
          ListNotificationSubscriptionsRequest.newBuilder().setParent(parent).build();

      System.out.println("Sending list Notification Subscriptions request:");
      ListNotificationSubscriptionsPagedResponse response =
          notificationsApiServiceClient.listNotificationSubscriptions(request);
      int count = 0;
      for (NotificationSubscription notificationSubscription : response.iterateAll()) {
        System.out.println(notificationSubscription);
        count++;
      }
      System.out.print("The following count of notification subscriptions were returned: ");
      System.out.println(count);

    } catch (ApiException e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    listNotificationSubscriptions(config);
  }
}
// [END merchantapi_list_notification_subscriptions]
