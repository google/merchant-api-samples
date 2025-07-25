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
// [START merchantapi_get_merchant_review]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Reviews\V1beta\Client\MerchantReviewsServiceClient;
use Google\Shopping\Merchant\Reviews\V1beta\GetMerchantReviewRequest;

/**
 * This class demonstrates how to get a Merchant review.
 */
class GetMerchantReviewSample
{
    private const MERCHANT_REVIEW_ID = 'YOUR_MERCHANT_REVIEW_ID';

    /**
     * Retrieves a merchant review from your Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $merchantReviewId The ID of the merchant review to retrieve.
     */
    public static function getMerchantReviewSample(array $config, string $merchantReviewId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $merchantReviewsServiceClient = new MerchantReviewsServiceClient($options);

        // The name of the merchant review to retrieve.
        // Format: accounts/{account}/merchantReviews/{merchant_review}
        $name = sprintf(
            'accounts/%s/merchantReviews/%s',
            $config['accountId'],
            $merchantReviewId
        );

        // Creates the request message.
        $request = (new GetMerchantReviewRequest())
            ->setName($name);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending get merchant review request:%s", PHP_EOL);
            $response = $merchantReviewsServiceClient->getMerchantReview($request);
            printf("Merchant review retrieved successfully:%s", PHP_EOL);
            printf("%s%s", $response->getName(), PHP_EOL);
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
        self::getMerchantReviewSample($config, self::MERCHANT_REVIEW_ID);
    }
}

// Run the script.
$sample = new GetMerchantReviewSample();
$sample->callSample();
// [END merchantapi_get_merchant_review]

