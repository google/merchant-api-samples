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
// [START merchantapi_list_promotions]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Promotions\V1beta\ListPromotionsRequest;
use Google\Shopping\Merchant\Promotions\V1beta\Promotion;
use Google\Shopping\Merchant\Promotions\V1beta\Client\PromotionsServiceClient;

/**
 * This class demonstrates how to list promotions.
 */
class ListPromotions
{

    /**
     * Lists promotions for the given account.
     *
     * @param array $config
     *      The configuration data used for authentication and getting the account ID.
     * @return void
     */
    public static function listPromotionsSample($config): void
    {

        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $promotionsServiceClient = new PromotionsServiceClient($options);

        try {
            // Creates the request.
            $request = new ListPromotionsRequest([
                'parent' => sprintf('accounts/%s', $config['accountId']),
            ]);

            print "Sending list promotions request:\n";

            // Makes the request.
            $response = $promotionsServiceClient->listPromotions($request);

            $count = 0;

            // Iterates over all promotions returned in the response.
            foreach ($response->iterateAllElements() as $promotion) {
                print_r($promotion);
                $count++;
            }
            printf("The following count of promotions were returned: %s\n", $count);
        } catch (ApiException $e) {
            printf("Failed to list promotions.\n");
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

        // Makes the call to list promotions.
        self::listPromotionsSample($config);
    }
}


// Run the script
$sample = new ListPromotions();
$sample->callSample();
// [END merchantapi_list_promotions]