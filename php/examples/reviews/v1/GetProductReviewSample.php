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
// [START merchantapi_get_product_review]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Reviews\V1beta\Client\ProductReviewsServiceClient;
use Google\Shopping\Merchant\Reviews\V1beta\GetProductReviewRequest;

/**
 * This class demonstrates how to get a product review.
 */
class GetProductReviewSample
{
    private const PRODUCT_REVIEW_ID = 'YOUR_PRODUCT_REVIEW_ID';

    /**
     * Retrieves a product review from your Merchant Center account.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $productReviewId The ID of the product review to retrieve.
     */
    public static function getProductReviewSample(array $config, string $productReviewId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $productReviewsServiceClient = new ProductReviewsServiceClient($options);

        // The name of the product review to retrieve.
        // Format: accounts/{account}/productReviews/{product_review}
        $name = sprintf(
            'accounts/%s/productReviews/%s',
            $config['accountId'],
            $productReviewId
        );

        // Creates the request message.
        $request = (new GetProductReviewRequest())
            ->setName($name);

        // Calls the API and catches and prints any network failures/errors.
        try {
            printf("Sending get product review request:%s", PHP_EOL);
            $response = $productReviewsServiceClient->getProductReview($request);
            printf("Product review retrieved successfully:%s", PHP_EOL);
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
        self::getProductReviewSample($config, self::PRODUCT_REVIEW_ID);
    }
}

// Run the script.
$sample = new GetProductReviewSample();
$sample->callSample();
// [END merchantapi_get_product_review]
