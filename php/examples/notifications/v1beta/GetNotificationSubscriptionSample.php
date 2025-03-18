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
// [START merchantapi_get_notification_subscription]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Notifications\V1beta\Client\NotificationsApiServiceClient;
use Google\Shopping\Merchant\Notifications\V1beta\GetNotificationSubscriptionRequest;

/**
 * Gets a Notification Subscription.
 */
class GetNotificationSubscription
{
    /**
     * Gets a specific Notification Subscription for a given Merchant Center account.
     *
     * @param array $config
     *  Configuration data for authentication and account details.
     * @param string $notificationSubscriptionId
     *  The ID of the notification subscription to retrieve.
     * @throws ApiException
     */
    public static function getNotificationSubscriptionSample($config, $notificationSubscriptionId): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Set up client options.
        $options = ['credentials' => $credentials];

        // Create a client instance.
        $notificationsApiServiceClient = new NotificationsApiServiceClient($options);

        // Construct the resource name.
        $name = "accounts/" . $config['accountId'] .
            "/notificationsubscriptions/" . $notificationSubscriptionId;

        // Create the request object.
        $request = new GetNotificationSubscriptionRequest(['name' => $name]);

        print "Sending get Notification Subscription request:\n";

        // Make the API call.
        try {
            $response = $notificationsApiServiceClient->getNotificationSubscription($request);
            print "Retrieved Notification Subscription below:\n";
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

        self::getNotificationSubscriptionSample($config, $notificationSubscriptionId);
    }
}

// Run the script.
$sample = new GetNotificationSubscription();
$sample->callSample();
// [END merchantapi_get_notification_subscription]
