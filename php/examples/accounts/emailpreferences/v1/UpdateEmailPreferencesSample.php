<?php
/**
 * Copyright 2025 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';
// [START merchantapi_update_email_preferences]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Accounts\V1beta\Client\EmailPreferencesServiceClient;
use Google\Shopping\Merchant\Accounts\V1beta\EmailPreferences;
use Google\Shopping\Merchant\Accounts\V1beta\EmailPreferences\OptInState;
use Google\Shopping\Merchant\Accounts\V1beta\UpdateEmailPreferencesRequest;

/**
 * This class demonstrates how to update a EmailPreferences to OPT_IN to News and Tips. This service
 * only permits retrieving and updating email preferences for the authenticated user.
 */
class UpdateEmailPreferences
{

    /**
     * Updates email preferences to OPT_IN to News and Tips.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the acccount ID.
     * @param string $email The email address of this user. If you want to get the user information
     *      of the user making the Merchant API request, you can also use "me" instead
     *      of an email address.
     *
     * @return void
     */
    public static function updateEmailPreferencesSample($config, $email): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $emailPreferencesServiceClient = new EmailPreferencesServiceClient($options);

        // Creates EmailPreferences name to identify the EmailPreferences.
        // The name has the format: accounts/{account}/users/{user}/emailPreferences
        $name = "accounts/" . $config['accountId'] . "/users/" . $email . "/emailPreferences";

        // Create a EmailPreferences with the updated fields.
        $emailPreferences = (new EmailPreferences())
            ->setName($name)
            ->setNewsAndTips(OptInState::OPTED_OUT);

        $fieldMask = (new FieldMask())
            ->setPaths(['news_and_tips']);

        try {
            $request = (new UpdateEmailPreferencesRequest())
                ->setEmailPreferences($emailPreferences)
                ->setUpdateMask($fieldMask);

            print "Sending Update EmailPreferences request\n";
            $response = $emailPreferencesServiceClient->updateEmailPreferences($request);
            print "Updated EmailPreferences Name below\n";
            print $response->getName() . "\n";
        } catch (ApiException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Helper to execute the sample.
     *
     * @return void
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        // The email address of this user. If you want to get the user information
        // Of the user making the Merchant API request, you can also use "me" instead
        // Of an email address.
        // $email = "testUser@gmail.com";
        $email = "me";

        self::updateEmailPreferencesSample($config, $email);
    }
}

// Run the script
$sample = new UpdateEmailPreferences();
$sample->callSample();
// [END merchantapi_update_email_preferences]

