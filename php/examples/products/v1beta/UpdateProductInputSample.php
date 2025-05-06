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
// [START merchantapi_update_product_input]
use Google\ApiCore\ApiException;
use Google\Protobuf\FieldMask;
use Google\Shopping\Merchant\Products\V1beta\Attributes;
use Google\Shopping\Merchant\Products\V1beta\Client\ProductInputsServiceClient;
use Google\Shopping\Merchant\Products\V1beta\ProductInput;
use Google\Shopping\Merchant\Products\V1beta\UpdateProductInputRequest;
use Google\Shopping\Type\CustomAttribute;

/**
 * This class demonstrates how to update a product input.
 */
class UpdateProductInputSample
{
    // An ID assigned to a product by Google. In the format
    // channel~contentLanguage~feedLabel~offerId
    // Please ensure this product ID exists for the update to succeed.
    private const PRODUCT_ID = "online~en~label~sku123";

    // Identifies the data source that will own the product input.
    // Please ensure this data source ID exists.
    private const DATASOURCE_ID = "<INSERT_DATASOURCE_ID>";

    /**
     * Helper function to construct the full product input resource name.
     *
     * @param string $accountId The merchant account ID.
     * @param string $productInputId The product input ID (e.g., "online~en~label~sku123").
     * @return string The full product input resource name.
     */
    private static function getProductInputName(string $accountId, string $productInputId): string
    {
        return sprintf("accounts/%s/productInputs/%s", $accountId, $productInputId);
    }

    /**
     * Helper function to construct the full data source resource name.
     *
     * @param string $accountId The merchant account ID.
     * @param string $dataSourceId The data source ID.
     * @return string The full data source resource name.
     */
    private static function getDataSourceName(string $accountId, string $dataSourceId): string
    {
        return sprintf("accounts/%s/dataSources/%s", $accountId, $dataSourceId);
    }

    /**
     * Updates an existing product input in your Merchant Center account.
     *
     * @param array $config The configuration array containing the account ID.
     * @param string $productId The ID of the product input to update.
     * @param string $dataSourceId The ID of the data source.
     */
    public static function updateProductInput(
        array $config,
        string $productId,
        string $dataSourceId
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a ProductInputsServiceClient.
        $productInputsServiceClient = new ProductInputsServiceClient($options);

        // Construct the full resource name of the product input to be updated.
        $name = self::getProductInputName($config['accountId'], $productId);

        // Define the FieldMask to specify which fields to update.
        // Only 'attributes' and 'custom_attributes' can be specified in the
        // FieldMask for product input updates.
        $fieldMask = new FieldMask([
            'paths' => [
                "attributes.title",
                "attributes.description",
                "attributes.link",
                "attributes.image_link",
                "attributes.availability",
                "attributes.condition",
                "attributes.gtin",
                "custom_attributes.mycustomattribute" // Path for a specific custom attribute
            ]
        ]);

        // Calls the API and handles any network failures or errors.
        try {
            // Define the new attributes for the product.
            $attributes = new Attributes([
                'title' => 'A Tale of Two Cities 3',
                'description' => 'A classic novel about the French Revolution',
                'link' => 'https://exampleWebsite.com/tale-of-two-cities.html',
                'image_link' => 'https://exampleWebsite.com/tale-of-two-cities.jpg',
                'availability' => 'in stock',
                'condition' => 'new',
                'gtin' => ['9780007350896'] // GTIN is a repeated field.
            ]);

            // Construct the full data source name.
            // This specifies the data source context for the update.
            $dataSource = self::getDataSourceName($config['accountId'], $dataSourceId);

            // Create the ProductInput object with the desired updates.
            // The 'name' field must match the product input being updated.
            $productInput = new ProductInput([
                'name' => $name,
                'attributes' => $attributes,
                'custom_attributes' => [ // Provide the list of custom attributes.
                    new CustomAttribute([
                        'name' => 'mycustomattribute',
                        'value' => 'Example value'
                    ])
                ]
            ]);

            // Create the UpdateProductInputRequest.
            $request = new UpdateProductInputRequest([
                'update_mask' => $fieldMask,
                'data_source' => $dataSource,
                'product_input' => $productInput
            ]);

            print "Sending update ProductInput request\n";
            // Make the API call to update the product input.
            $response = $productInputsServiceClient->updateProductInput($request);

            print "Updated ProductInput Name below\n";
            // The name of the updated product input.
            // The last part of the product name is the product ID (e.g., channel~contentLanguage~feedLabel~offerId).
            print $response->getName() . "\n";
            print "Updated Product below\n";
            // Print the full updated product input object.
            print $response->serializeToJsonString(['prettyPrint' => true]) . "\n";

        } catch (ApiException $e) {
            printf("ApiException caught: %s\n", $e->getMessage());
        }
    }

    /**
     * Executes the UpdateProductInput sample.
     */
    public function callSample(): void
    {
        $config = Config::generateConfig();
        $productId = self::PRODUCT_ID;
        $dataSourceId = self::DATASOURCE_ID;

        self::updateProductInput($config, $productId, $dataSourceId);
    }
}

// Run the script.
$sample = new UpdateProductInputSample();
$sample->callSample();
// [END merchantapi_update_product_input]
