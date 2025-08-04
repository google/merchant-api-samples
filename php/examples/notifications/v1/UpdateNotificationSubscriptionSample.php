<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_update_notification_subscription]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Notifications\V1\Client\NotificationsApiServiceClient;
use Google\Shopping\Merchant\Notifications\V1\NotificationSubscription;
use Google\Shopping\Merchant\Notifications\V1\NotificationSubscription\NotificationEventType;
use Google\Shopping\Merchant\Notifications\V1\UpdateNotificationSubscriptionRequest;

/**
 * Updates a Notification Subscription.
 */
class UpdateNotificationSubscription
{
    /**
     * Updates a specific Notification Subscription for a given Merchant Center account.
     *
     * @param array $config
     *  Configuration data for authentication and account details.
     * @param string $notificationSubscriptionId
     *  The ID of the notification subscription to update.
     * @param int $eventType
     *  The new event type for the subscription.
     * @param string $callbackUri
     *  The new callback URI for the subscription.
     * @param bool $allManagedAccounts
     *  Whether to apply the subscription to all managed accounts.
     * @throws ApiException
     */
    public static function updateNotificationSubscriptionSample(
        $config,
        $notificationSubscriptionId,
        $eventType,
        $callbackUri,
        $allManagedAccounts
    ): void {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Set up client options.
        $options = ['credentials' => $credentials];

        // Create a client instance.
        $notificationsApiServiceClient = new NotificationsApiServiceClient($options);

        // Construct the resource name.
        $subscriptionName = "accounts/" . $config['accountId'] .
            "/notificationsubscriptions/" . $notificationSubscriptionId;

        // Create the updated NotificationSubscription object.
        $updatedSubscription = new NotificationSubscription([
            'name' => $subscriptionName,
            'call_back_uri' => $callbackUri,
            'registered_event' => $eventType,
            'all_managed_accounts' => $allManagedAccounts,
        ]);

        // Create a FieldMask to specify which fields to update.
        $updateMask = new FieldMask([
            'paths' => ['call_back_uri', 'registered_event', 'all_managed_accounts'],
        ]);

        // Create the update request.
        $request = new UpdateNotificationSubscriptionRequest([
            'notification_subscription' => $updatedSubscription,
            'update_mask' => $updateMask,
        ]);

        print "Sending update Notification Subscription request:\n";

        // Make the API call.
        try {
            $response = $notificationsApiServiceClient->updateNotificationSubscription($request);
            print "Updated Notification Subscription below:\n";
            print_r($response);
        } catch (ApiException $e) {
            print "Request failed:\n";
            print $e->getMessage() . "\n";
        }
    }

    /**
     * Execute the sample.
     *
     * @throws ApiException
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // Replace with the actual notification subscription ID.
        $notificationSubscriptionId = "YOUR_NOTIFICATION_SUBSCRIPTION_ID";
        // Set the new event type.
        $eventType = NotificationEventType::PRODUCT_STATUS_CHANGE;
        // Set the new callback URI.
        $callbackUri = "https://an-updated-uri.com";
        // Set whether to apply to all managed accounts.
        $allManagedAccounts = true;

        self::updateNotificationSubscriptionSample(
            $config,
            $notificationSubscriptionId,
            $eventType,
            $callbackUri,
            $allManagedAccounts
        );
    }
}

// Run the script.
$sample = new UpdateNotificationSubscription();
$sample->callSample();
// [END merchantapi_update_notification_subscription]

