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
// [START merchantapi_insert_product_reviews_async]
use GuzzleHttp\Promise\Utils;
use Google\Protobuf\Timestamp;
use Google\Shopping\Merchant\Reviews\V1beta\Client\ProductReviewsServiceClient;
use Google\Shopping\Merchant\Reviews\V1beta\InsertProductReviewRequest;
use Google\Shopping\Merchant\Reviews\V1beta\ProductReview;
use Google\Shopping\Merchant\Reviews\V1beta\ProductReviewAttributes;
use Google\Shopping\Merchant\Reviews\V1beta\ProductReviewAttributes\ReviewLink;
use Google\Shopping\Merchant\Reviews\V1beta\ProductReviewAttributes\ReviewLink\Type;


/**
 * This class demonstrates how to insert multiple product reviews asynchronously.
 */
class InsertProductReviewsAsyncSample
{
    private const DATA_SOURCE_ID = '<DATA_SOURCE_ID>';

    /**
     * Generates a random string of 8 alphanumeric characters.
     * @return string A random string.
     */
    private static function generateRandomString(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        $length = 8;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Creates a sample ProductReview object with a random ID.
     * @return ProductReview A sample ProductReview object.
     */
    private static function createProductReview(): ProductReview
    {
        // MAKE SURE YOU PASS AN ACTUAL PRODUCT REVIEW ID HERE if not generating randomly.
        $productReviewId = self::generateRandomString();

        $attributes = (new ProductReviewAttributes())
            ->setTitle('Would not recommend!')
            ->setContent('Not fantastic.')
            ->setMinRating(1)
            ->setMaxRating(5)
            ->setRating(2)
            ->setReviewTime(new Timestamp(['seconds' => 123456789]))
            ->setProductLinks(['exampleproducturl.com'])
            ->setReviewLink(
                (new ReviewLink())
                    ->setLink('examplereviewurl.com')
                    // The review page contains only this single review.
                    ->setType(Type::SINGLETON)
            )
            ->setGtins(['9780007350896', '9780007350897']);

        return (new ProductReview())
            ->setProductReviewId($productReviewId)
            ->setAttributes($attributes);
    }

    /**
     * Inserts multiple product reviews into your Merchant Center account asynchronously.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $dataSourceId The ID of the data source for the reviews.
     */
    public static function asyncInsertProductReviewsSample(array $config, string $dataSourceId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $productReviewsServiceClient = new ProductReviewsServiceClient($options);

        $parent = sprintf('accounts/%s', $config['accountId']);
        $dataSource = sprintf('accounts/%s/dataSources/%s', $config['accountId'], $dataSourceId);

        $insertedReviews = [];
        $errors = [];
        $promises = [];
        // Insert 5 product reviews.
        for ($i = 0; $i < 5; $i++) {
            try {
                $productReview = self::createProductReview();
                $productReviewId = $productReview->getProductReviewId();
                $request = (new InsertProductReviewRequest())
                    ->setParent($parent)
                    ->setProductReview($productReview)
                    ->setDataSource($dataSource);

                printf("Dispatching insert request for Product Review ID: %s%s", $productReviewId, PHP_EOL);

                // The async API returns a promise object.
                // Store the promise, keyed by a unique identifier for this review
                // This helps match responses/errors back to the original request.
                $promises[$productReviewId] = $productReviewsServiceClient->insertProductReviewAsync($request);

            } catch (Exception $e) {
                printf(
                    "Error preparing/dispatching product reviews %s",
                    $e->getMessage(),
                    PHP_EOL
                );
            }
        }

        if (empty($promises)) {
            echo "No review insert requests were dispatched." . PHP_EOL;
        } else {
            echo "All review insert requests dispatched. Waiting for responses..." . PHP_EOL;

            // Wait for all the promises to settle (either fulfilled or rejected)
            // Utils::settle() returns an array of results, each with 'state' and 'value' or 'reason'
            $results = Utils::settle($promises)->wait();

            $insertedReviewsResponses = [];
            $errors = [];

            foreach ($results as $productReviewId => $result) {

                if ($result['state'] === 'fulfilled') {
                    $response = $result['value']; // This is the actual response object from the API
                    printf("Successfully inserted product review ID: %s%s", $productReviewId, PHP_EOL);
                    $insertedReviewsResponses[] = $response;
                } elseif ($result['state'] === 'rejected') {
                    $reason = $result['reason']; // This is the exception object
                    printf(
                        "Error inserting product review Id: %s. %s",
                        $productReviewId,
                        $reason->getMessage(),
                        PHP_EOL
                    );
                    $errors[] = ['reviewId' => $productReviewId, 'reason' => $reason];
                }
            }

            // Now $insertedReviewsResponses contains actual successful response objects
            // And $errors contains details about the failed requests.
            printf("Processing complete. Successful inserts: %d, Errors: %d%s", count($insertedReviewsResponses), count($errors), PHP_EOL);
        }
    }

    /**
     * Helper to execute the sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        self::asyncInsertProductReviewsSample($config, self::DATA_SOURCE_ID);
    }
}

$sample = new InsertProductReviewsAsyncSample();
$sample->callSample();
// [END merchantapi_insert_product_reviews_async]
