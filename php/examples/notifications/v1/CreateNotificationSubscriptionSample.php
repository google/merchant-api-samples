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
// [START merchantapi_create_notification_subscription]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Notifications\V1\Client\NotificationsApiServiceClient;
use Google\Shopping\Merchant\Notifications\V1\CreateNotificationSubscriptionRequest;
use Google\Shopping\Merchant\Notifications\V1\NotificationSubscription;
use Google\Shopping\Merchant\Notifications\V1\NotificationSubscription\NotificationEventType;

/**
 * Creates a Notification Subscription.
 */
class CreateNotificationSubscription
{
    /**
     * Helper function to construct the parent resource name.
     *
     * @param string $accountId
     *  The Merchant Center account ID.
     * @return string
     *  The parent resource name.
     */
    private static function getParent($accountId)
    {
        return "accounts/" . $accountId;
    }

    /**
     * Creates a Notification Subscription for a given Merchant Center account.
     *
     * @param array $config
     *  Configuration data for authentication and account details.
     * @param string $callbackUri
     *  The URI to send notifications to.
     * @param int $eventType
     *  The type of event to subscribe to.
     * @throws ApiException
     */
    public static function createNotificationSubscriptionSample($config, $callbackUri, $eventType): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Set up client options.
        $options = ['credentials' => $credentials];

        // Create a client instance.
        $notificationsApiServiceClient = new NotificationsApiServiceClient($options);

        // Construct the parent resource name.
        $parent = self::getParent($config['accountId']);

        // Create the NotificationSubscription object.
        $notificationSubscription = new NotificationSubscription([
            // Uncomment to apply to all managed accounts.
            // 'all_managed_accounts' => true,
            'target_account' => $parent,
            'call_back_uri' => $callbackUri,
            'registered_event' => $eventType,
        ]);

        // Create the request object.
        $request = new CreateNotificationSubscriptionRequest([
            'parent' => $parent,
            'notification_subscription' => $notificationSubscription,
        ]);

        print "Sending create Notification Subscription request:\n";

        // Make the API call.
        try {
            $response = $notificationsApiServiceClient->createNotificationSubscription($request);
            print "Created Notification Subscription below:\n";
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
        // Replace with the actual callback URI.
        $callbackUri = "https://www.samplesite.com";
        // Set the event type.
        $eventType = NotificationEventType::PRODUCT_STATUS_CHANGE;

        self::createNotificationSubscriptionSample($config, $callbackUri, $eventType);
    }
}

// Run the script.
$sample = new CreateNotificationSubscription();
$sample->callSample();
// [END merchantapi_create_notification_subscription]
