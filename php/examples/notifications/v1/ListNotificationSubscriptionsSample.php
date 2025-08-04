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
// [START merchantapi_list_notification_subscriptions]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Notifications\V1\Client\NotificationsApiServiceClient;
use Google\Shopping\Merchant\Notifications\V1\ListNotificationSubscriptionsRequest;

/**
 * Lists Notification Subscriptions.
 */
class ListNotificationSubscriptions
{
    /**
     * Helper function to construct the parent resource name.
     * @param $accountId
     *  The Merchant Center account ID.
     * @return string
     *  The parent resource name.
     */
    private static function getParent($accountId)
    {
        return "accounts/" . $accountId;
    }

    /**
     * Lists Notification Subscriptions for a given Merchant Center account.
     *
     * @param array $config
     *  Configuration data for authentication and account details.
     * @throws ApiException
     */
    public static function listNotificationSubscriptionsSample($config): void
    {
        // Get OAuth credentials.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Set up client options.
        $options = ['credentials' => $credentials];

        // Create a client instance.
        $notificationsApiServiceClient = new NotificationsApiServiceClient($options);

        // Construct the parent resource name.
        $parent = self::getParent($config['accountId']);

        // Create the request object.
        $request = new ListNotificationSubscriptionsRequest(['parent' => $parent]);

        print "Sending list Notification Subscriptions request:\n";

        // Make the API call.
        try {
            $response = $notificationsApiServiceClient->listNotificationSubscriptions($request);
            $count = 0;
            foreach ($response->iterateAllElements() as $subscription) {
                print_r($subscription);
                $count++;
            }
            print "The following count of notification subscriptions were returned: " . $count . "\n";
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
        self::listNotificationSubscriptionsSample($config);
    }
}

// Run the script.
$sample = new ListNotificationSubscriptions();
$sample->callSample();
// [END merchantapi_list_notification_subscriptions]
