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
// [START merchantapi_insert_shippingsettings]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\Accounts\V1\Client\ShippingSettingsServiceClient;
use Google\Shopping\Merchant\Accounts\V1\DeliveryTime;
use Google\Shopping\Merchant\Accounts\V1\InsertShippingSettingsRequest;
use Google\Shopping\Merchant\Accounts\V1\RateGroup;
use Google\Shopping\Merchant\Accounts\V1\Service;
use Google\Shopping\Merchant\Accounts\V1\Service\ShipmentType;
use Google\Shopping\Merchant\Accounts\V1\ShippingSettings;
use Google\Shopping\Merchant\Accounts\V1\Value;
use Google\Shopping\Type\Price;

/**
 * This class demonstrates how to insert a ShippingSettings for a Merchant Center account.
 */
class InsertShippingSettings
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The account ID.
     * @return string The parent in the format "accounts/{accountId}".
     */
    private static function getParent(string $accountId): string
    {
        return sprintf("accounts/%s", $accountId);
    }

    /**
     * Inserts shipping settings for the specified Merchant Center account.
     *
     * @param array $config The configuration data containing the account ID.
     * @return void
     */
    public static function insertShippingSettings($config)
    {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates options config containing credentials for the client to use.
        $options = ['credentials' => $credentials];

        // Creates a client.
        $shippingSettingsServiceClient = new ShippingSettingsServiceClient($options);

        // Creates parent to identify where to insert the shippingsettings.
        $parent = self::getParent($config['accountId']);

        // Calls the API and catches and prints any network failures/errors.
        try {
            $request = (new InsertShippingSettingsRequest())
                ->setParent($parent)
                ->setShippingSetting(
                    (new ShippingSettings())
                        // Etag needs to be an empty string on initial insert
                        // On future inserts, call GET first to get the Etag
                        // Then use the retrieved Etag on future inserts.
                        // NOTE THAT ON THE INITIAL INSERT, YOUR SHIPPING SETTINGS WILL
                        // NOT BE STORED, YOU HAVE TO CALL INSERT AGAIN WITH YOUR
                        // RETRIEVED ETAG.
                        ->setEtag("")
                        ->setServices([
                            (new Service())
                                ->setServiceName("Canadian Postal Service")
                                ->setActive(true)
                                ->setDeliveryCountries(["CA"])
                                ->setCurrencyCode("CAD")
                                ->setDeliveryTime(
                                    (new DeliveryTime())
                                        ->setMinTransitDays(0)
                                        ->setMaxTransitDays(3)
                                        ->setMinHandlingDays(0)
                                        ->setMaxHandlingDays(3)
                                )
                                ->setRateGroups(
                                    [(new RateGroup())
                                        ->setApplicableShippingLabels(["Oversized","Perishable"])
                                        ->setSingleValue((new Value())->setPricePercentage("5.4"))
                                        ->setName("Oversized and Perishable items")]
                                )
                                ->setShipmentType(ShipmentType::DELIVERY)
                                ->setMinimumOrderValue(
                                    (new Price())
                                        ->setAmountMicros(10000000)
                                        ->setCurrencyCode("CAD")
                                )
                        ])
                );

            print "Sending insert ShippingSettings request" . PHP_EOL;
            $response = $shippingSettingsServiceClient->insertShippingSettings($request);
            print "Inserted ShippingSettings below" . PHP_EOL;
            print_r($response);
            // You can apply ShippingSettings to specific products by using the `shippingLabel` field
            // on the product.
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

        // Makes the call to insert shipping settings for the MC account.
        self::insertShippingSettings($config);
    }
}

// Run the script
$sample = new InsertShippingSettings();
$sample->callSample();
// [END merchantapi_insert_shippingsettings]