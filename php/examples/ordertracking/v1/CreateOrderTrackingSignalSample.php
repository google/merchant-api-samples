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
// [START merchantapi_create_order_tracking_signal]
use Google\ApiCore\ApiException;
use Google\Shopping\Merchant\OrderTracking\V1\Client\OrderTrackingSignalsServiceClient;
use Google\Shopping\Merchant\OrderTracking\V1\CreateOrderTrackingSignalRequest;
use Google\Shopping\Merchant\OrderTracking\V1\OrderTrackingSignal;
use Google\Shopping\Merchant\OrderTracking\V1\OrderTrackingSignal\LineItemDetails;
use Google\Shopping\Merchant\OrderTracking\V1\OrderTrackingSignal\ShipmentLineItemMapping;
use Google\Shopping\Merchant\OrderTracking\V1\OrderTrackingSignal\ShippingInfo;
use Google\Shopping\Merchant\OrderTracking\V1\OrderTrackingSignal\ShippingInfo\ShippingState;
use Google\Shopping\Type\Price;
use Google\Type\DateTime;
use Google\Type\TimeZone;

/** This class demonstrates how to create an order tracking signal. */
class CreateOrderTrackingSignalSample
{
    /**
     * A helper function to create the parent string.
     *
     * @param string $accountId The account that owns the product.
     *
     * @return string The parent has the format: `accounts/{account_id}`
     */
    private static function getParent(string $accountId): string
    {
        return sprintf('accounts/%s', $accountId);
    }

    /**
     * Creates an order tracking signal.
     *
     * @param array $config The configuration data used for authentication and
     *     getting the account ID.
     * @param array $productIds The product IDs for all items in the order.
     *
     * @return void
     */
    public static function createOrderTrackingSignalSample(
        array $config,
        array $productIds
    ): void {
        // Gets the OAuth credentials to make the request.
        $credentials = Authentication::useServiceAccountOrTokenFile();

        // Creates a client.
        $orderTrackingSignalsServiceClient = new OrderTrackingSignalsServiceClient(
            ['credentials' => $credentials]
        );

        // Creates parent to identify where to insert the product.
        $parent = self::getParent($config['accountId']);

        $firstProductId = $productIds[0];
        $secondProductId = $productIds[1];

        // The timezone for all DateTime objects.
        $timeZone = new TimeZone(['id' => 'America/Los_Angeles']);

        // The time the order was created.
        $orderCreatedTime = new DateTime(
            [
                'year' => 2025,
                'month' => 3,
                'day' => 24,
                'hours' => 12,
                'minutes' => 2,
                'seconds' => 22,
                'time_zone' => $timeZone
            ]
        );

        // The time the first shipment was shipped.
        $shippedTime1 = new DateTime(
            [
                'year' => 2025,
                'month' => 3,
                'day' => 25,
                'hours' => 16,
                'minutes' => 22,
                'time_zone' => $timeZone
            ]
        );

        // The time the second shipment was shipped.
        $shippedTime2 = new DateTime(
            [
                'year' => 2025,
                'month' => 3,
                'day' => 26,
                'hours' => 16,
                'minutes' => 22,
                'time_zone' => $timeZone
            ]
        );

        // The earliest promised delivery time.
        $earliestDeliveryPromiseTime = new DateTime(
            [
                'year' => 2025,
                'month' => 3,
                'day' => 27,
                'time_zone' => $timeZone
            ]
        );

        // The latest promised delivery time.
        $latestDeliveryPromiseTime = new DateTime(
            [
                'year' => 2025,
                'month' => 3,
                'day' => 30,
                'time_zone' => $timeZone
            ]
        );

        // The actual delivery time.
        $actualDeliveryTime = new DateTime(
            [
                'year' => 2025,
                'month' => 3,
                'day' => 29,
                'hours' => 16,
                'minutes' => 22,
                'time_zone' => $timeZone
            ]
        );

        // Shipping information for the first shipment.
        $shippingInfo1 = new ShippingInfo(
            [
                'shipment_id' => 'shipment_id1',
                'carrier' => 'UPS',
                'carrier_service' => 'Ground',
                'tracking_id' => '1Z23456789',
                'shipped_time' => $shippedTime1,
                'earliest_delivery_promise_time' => $earliestDeliveryPromiseTime,
                'latest_delivery_promise_time' => $latestDeliveryPromiseTime,
                'actual_delivery_time' => $actualDeliveryTime,
                'shipping_status' => ShippingState::DELIVERED,
                'origin_postal_code' => '94043',
                'origin_region_code' => 'US'
            ]
        );

        // Shipping information for the second shipment.
        $shippingInfo2 = new ShippingInfo(
            [
                'shipment_id' => 'shipment_id2',
                'carrier' => 'USPS',
                'carrier_service' => 'Ground Advantage',
                'tracking_id' => '987654321',
                'shipped_time' => $shippedTime2,
                'shipping_status' => ShippingState::SHIPPED,
                'origin_postal_code' => '94043',
                'origin_region_code' => 'US'
            ]
        );

        // Calls the API and catches and prints any network failures/errors.
        try {
            // The price represented as a number in micros (1 million micros is an
            // equivalent to one's currency standard unit, for example, 1 USD = 1000000
            // micros).
            $customerShippingFee = new Price(
                [
                    // Equivalent to 5 USD.
                    'amount_micros' => 5000000,
                    'currency_code' => 'USD'
                ]
            );

            // Details of the line items in the order including quantity and fields
            // identifying the product.
            $lineItems = [
                new LineItemDetails(
                    [
                        'quantity' => 2,
                        'product_id' => $firstProductId,
                        'line_item_id' => 'item1'
                    ]
                ),
                new LineItemDetails(
                    [
                        'quantity' => 1,
                        'product_id' => $secondProductId,
                        'line_item_id' => 'item2',
                        // Optional fields used to identify the product when product ID is not
                        // sufficient.
                        'mpn' => '00638HAY',
                        'product_title' => 'Tshirt-small-blue',
                        'brand' => 'Brand1',
                        // Any GTINs associated with the product.
                        'gtins' => ['001234567890']
                    ]
                )
            ];

            // Mapping of line items to shipments.
            $shipmentLineItemMapping = [
                new ShipmentLineItemMapping(
                    [
                        'shipment_id' => 'shipment_id1',
                        'line_item_id' => 'item2',
                        'quantity' => 1
                    ]
                ),
                new ShipmentLineItemMapping(
                    [
                        'shipment_id' => 'shipment_id2',
                        'line_item_id' => 'item1',
                        'quantity' => 1
                    ]
                ),
                new ShipmentLineItemMapping(
                    [
                        'shipment_id' => 'shipment_id1',
                        'line_item_id' => 'item1',
                        'quantity' => 1
                    ]
                )
            ];

            // Constructs the OrderTrackingSignal.
            $orderTrackingSignal = new OrderTrackingSignal(
                [
                    // Unique order ID across all merchants orders.
                    'order_id' => 'unique_order_id443455',
                    // If sending signal on behalf of another merchant use setMerchantId to
                    // indicate the merchant.
                    // 'merchant_id' => 123,
                    'order_created_time' => $orderCreatedTime,
                    'shipping_info' => [$shippingInfo1, $shippingInfo2],
                    'line_items' => $lineItems,
                    'shipment_line_item_mapping' => $shipmentLineItemMapping,
                    'customer_shipping_fee' => $customerShippingFee,
                    'delivery_postal_code' => '10011'
                ]
            );

            // Prepares the request message.
            $request = new CreateOrderTrackingSignalRequest(
                [
                    'parent' => $parent,
                    'order_tracking_signal' => $orderTrackingSignal
                ]
            );

            print "Sending Create OrderTrackingSignal request.\n";
            $response = $orderTrackingSignalsServiceClient->createOrderTrackingSignal(
                $request
            );
            print "Created OrderTrackingSignal below.\n";
            // Pretty-prints the Protobuf JSON response.
            print $response->serializeToJsonString(true) . "\n";
        } catch (ApiException $e) {
            printf("Call failed with message: %s\n", $e->getMessage());
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
        // All products in the order. Replace with actual products in the order. Be sure to include all
        // products in the order.
        $productId1 = 'online~en~us~sku123';
        $productId2 = 'online~en~us~skuabc';
        $productIds = [$productId1, $productId2];
        self::createOrderTrackingSignalSample($config, $productIds);
    }
}

// Runs the script.
$sample = new CreateOrderTrackingSignalSample();
$sample->callSample();
// [END merchantapi_create_order_tracking_signal]
