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

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Authentication/Authentication.php';
require_once __DIR__ . '/../../Authentication/Config.php';
// [START merchantapi_list_merchant_reviews]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Reviews\V1beta\Client\MerchantReviewsServiceClient;
use Google\Shopping\Merchant\Reviews\V1beta\ListMerchantReviewsRequest;

/**
 * This class demonstrates how to list all the merchant reviews in a given account.
 */
class ListMerchantReviewsSample
{
    /**
     * Lists all merchant reviews for a given account.
     *
     * @param array $config The configuration data for authentication and account ID.
     */
    public static function listMerchantReviewsSample(array $config): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $merchantReviewsServiceClient = new MerchantReviewsServiceClient($options);

        // The parent account from which to retrieve reviews.
        // Format: accounts/{account}
        $parent = sprintf('accounts/%s', $config['accountId']);

        // Creates the request message.
        $request = (new ListMerchantReviewsRequest())
            ->setParent($parent);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending list merchant reviews request:%s", PHP_EOL);
            $response = $merchantReviewsServiceClient->listMerchantReviews($request);

            $count = 0;
            // Iterates over all rows in all pages and prints all merchant reviews.
            foreach ($response->iterateAllElements() as $element) {
                printf("%s%s", $element->serializeToJsonString(), PHP_EOL);
                $count++;
            }
            printf("The following count of elements were returned: %d%s", $count, PHP_EOL);
        } catch (ApiException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::listMerchantReviewsSample($config);
    }
}

// Run the script.
$sample = new ListMerchantReviewsSample();
$sample->callSample();
// [END merchantapi_list_merchant_reviews]
