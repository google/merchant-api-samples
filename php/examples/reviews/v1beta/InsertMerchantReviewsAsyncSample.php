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
// [START merchantapi_insert_merchant_reviews_async]
use GuzzleHttp\Promise\Utils;
use Google\Protobuf\Timestamp;
use Google\Shopping\Merchant\Reviews\V1beta\Client\MerchantReviewsServiceClient;
use Google\Shopping\Merchant\Reviews\V1beta\InsertMerchantReviewRequest;
use Google\Shopping\Merchant\Reviews\V1beta\MerchantReview;
use Google\Shopping\Merchant\Reviews\V1beta\MerchantReviewAttributes;

/**
 * This class demonstrates how to insert multiple merchant reviews asynchronously.
 */
class InsertMerchantReviewsAsyncSample
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
     * Creates a sample MerchantReview object with a random ID.
     * @return MerchantReview A sample MerchantReview object.
     */
    private static function createMerchantReview(): MerchantReview
    {
        $merchantReviewId = self::generateRandomString();

        $attributes = (new MerchantReviewAttributes())
            ->setTitle('Great Merchant!')
            ->setContent('Would buy there again.')
            ->setMinRating(1)
            ->setMaxRating(5)
            ->setRating(4)
            ->setReviewTime(new Timestamp(['seconds' => 1731165684]))
            ->setReviewLanguage('en-US');

        return (new MerchantReview())
            ->setMerchantReviewId($merchantReviewId)
            ->setAttributes($attributes);
    }

    /**
     * Inserts multiple merchant reviews into your Merchant Center account asynchronously.
     *
     * @param array $config The configuration data for authentication and account ID.
     * @param string $dataSourceId The ID of the data source for the reviews.
     */
    public static function asyncInsertMerchantReviewsSample(array $config, string $dataSourceId): void
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $merchantReviewsServiceClient = new MerchantReviewsServiceClient($options);

        $parent = sprintf('accounts/%s', $config['accountId']);
        $dataSource = sprintf('accounts/%s/dataSources/%s', $config['accountId'], $dataSourceId);

        $insertedReviews = [];
        $errors = [];
        $promises = [];
        // Inserts 5 merchant reviews
        for ($i = 0; $i < 5; $i++) {
            try {
                $merchantReview = self::createMerchantReview();
                $merchantReviewId = $merchantReview->getMerchantReviewId();
                $request = (new InsertMerchantReviewRequest())
                    ->setParent($parent)
                    ->setMerchantReview($merchantReview)
                    ->setDataSource($dataSource);

                printf("Dispatching insert request for Merchant Review ID: %s%s", $merchantReviewId, PHP_EOL);

                // Store the promise, keyed by a unique identifier for this review
                // This helps match responses/errors back to the original request.
                $promises[$merchantReviewId] = $merchantReviewsServiceClient->insertMerchantReviewAsync($request);

            } catch (Exception $e) {
                printf(
                    "Error preparing/dispatching merchant reviews %s",
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

            foreach ($results as $merchantReviewId => $result) {

                if ($result['state'] === 'fulfilled') {
                    $response = $result['value']; // This is the actual response object from the API
                    printf("Successfully inserted merchant review ID: %s%s", $merchantReviewId, PHP_EOL);
                    $insertedReviewsResponses[] = $response;
                } elseif ($result['state'] === 'rejected') {
                    $reason = $result['reason']; // This is the exception object
                    printf(
                        "Error inserting merchant review Id: %s. %s",
                        $merchantReviewId,
                        $reason->getMessage(),
                        PHP_EOL
                    );
                    $errors[] = ['reviewId' => $merchantReviewId, 'reason' => $reason];
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
        self::asyncInsertMerchantReviewsSample($config, self::DATA_SOURCE_ID);
    }
}

$sample = new InsertMerchantReviewsAsyncSample();
$sample->callSample();
// [END merchantapi_insert_merchant_reviews_async]

