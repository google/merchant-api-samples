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
// [START merchantapi_link_gbp_account]
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../Authentication/Authentication.php';
require_once __DIR__ . '/../../../Authentication/Config.php';

use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\GbpAccountsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\LinkGbpAccountRequest;

/**
 * This class demonstrates how to link the specified merchant to a GBP account.
 */
class LinkGbpAccountSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The account ID.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Links the specified merchant to a GBP account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $gbpEmail The email address of the Business Profile account.
     *
     * @return void
     */
    public static function linkGbpAccount(array $config, string $gbpEmail): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $gbpAccountsServiceClient = new GbpAccountsServiceClient($options);

        // Creates the parent account name to identify the merchant.
        $parent = self::getParent($config['accountId']);

        // Creates the request to link the GBP account.
        $request = new LinkGbpAccountRequest([
            'parent' => $parent,
            'gbp_email' => $gbpEmail
        ]);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending link GBP account request:%s", PHP_EOL);
            // An empty response is returned on success.
            $gbpAccountsServiceClient->linkGbpAccount($request);
            printf("Successfully linked to GBP account: %s%s", $gbpEmail, PHP_EOL);
        } catch (ApiException $e) {
            printf("An error has occurred: %s%s", $e->getMessage(), PHP_EOL);
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

        // The email address of the Business Profile account.
        $gbpEmail = '{GBP_EMAIL}';

        self::linkGbpAccount($config, $gbpEmail);
    }
}

// Runs the script.
$sample = new LinkGbpAccountSample();
$sample->callSample();
// [END merchantapi_link_gbp_account]
