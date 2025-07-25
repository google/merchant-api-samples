// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

package shopping.merchant.samples.ordertracking.v1beta;

// [START merchantapi_create_order_tracking_signal]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.ordertracking.v1beta.CreateOrderTrackingSignalRequest;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignal;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignal.LineItemDetails;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignal.ShipmentLineItemMapping;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignal.ShippingInfo;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignal.ShippingInfo.ShippingState;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignalsServiceClient;
import com.google.shopping.merchant.ordertracking.v1beta.OrderTrackingSignalsServiceSettings;
import com.google.shopping.type.Price;
import com.google.type.DateTime;
import com.google.type.TimeZone;
import java.util.Arrays;
import java.util.List;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to create an order tracking signal. */
public class CreateOrderTrackingSignalSample {
  private static String getParent(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  private static void createOrderTrackingSignal(Config config, List<String> productIds)
      throws Exception {
    GoogleCredentials credentials = new Authenticator().authenticate();
    OrderTrackingSignalsServiceSettings orderTrackingSignalsServiceSettings =
        OrderTrackingSignalsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credentials))
            .build();

    String parent = getParent(config.getAccountId().toString());

    String firstProductId = productIds.get(0);
    String secondProductId = productIds.get(1);

    DateTime orderCreatedTime =
        DateTime.newBuilder()
            .setYear(2025)
            .setMonth(3)
            .setDay(24)
            .setHours(12)
            .setMinutes(2)
            .setSeconds(22)
            .setTimeZone(TimeZone.newBuilder().setId("America/Los_Angeles"))
            .build();
    DateTime shippedTime1 =
        DateTime.newBuilder()
            .setYear(2025)
            .setMonth(3)
            .setDay(25)
            .setHours(16)
            .setMinutes(22)
            .setTimeZone(TimeZone.newBuilder().setId("America/Los_Angeles"))
            .build();
    DateTime shippedTime2 =
        DateTime.newBuilder()
            .setYear(2025)
            .setMonth(3)
            .setDay(26)
            .setHours(16)
            .setMinutes(22)
            .setTimeZone(TimeZone.newBuilder().setId("America/Los_Angeles"))
            .build();
    DateTime earliestDeliveryPromiseTime =
        DateTime.newBuilder()
            .setYear(2025)
            .setMonth(3)
            .setDay(27)
            .setTimeZone(TimeZone.newBuilder().setId("America/Los_Angeles"))
            .build();
    DateTime latestDeliveryPromiseTime =
        DateTime.newBuilder()
            .setYear(2025)
            .setMonth(3)
            .setDay(30)
            .setTimeZone(TimeZone.newBuilder().setId("America/Los_Angeles"))
            .build();
    DateTime actualDeliveryTime =
        DateTime.newBuilder()
            .setYear(2025)
            .setMonth(3)
            .setDay(29)
            .setHours(16)
            .setMinutes(22)
            .setTimeZone(TimeZone.newBuilder().setId("America/Los_Angeles"))
            .build();

    ShippingInfo shippingInfo1 =
        ShippingInfo.newBuilder()
            .setShipmentId("shipment_id1")
            .setCarrier("UPS")
            .setCarrierService("Ground")
            .setTrackingId("1Z23456789")
            .setShippedTime(shippedTime1)
            .setEarliestDeliveryPromiseTime(earliestDeliveryPromiseTime)
            .setLatestDeliveryPromiseTime(latestDeliveryPromiseTime)
            .setActualDeliveryTime(actualDeliveryTime)
            .setShippingStatus(ShippingState.DELIVERED)
            .setOriginPostalCode("94043")
            .setOriginRegionCode("US")
            .build();
    ShippingInfo shippingInfo2 =
        ShippingInfo.newBuilder()
            .setShipmentId("shipment_id2")
            .setCarrier("USPS")
            .setCarrierService("Ground Advantage")
            .setTrackingId("987654321")
            .setShippedTime(shippedTime2)
            .setShippingStatus(ShippingState.SHIPPED)
            .setOriginPostalCode("94043")
            .setOriginRegionCode("US")
            .build();

    try (OrderTrackingSignalsServiceClient orderTrackingSignalsServiceClient =
        OrderTrackingSignalsServiceClient.create(orderTrackingSignalsServiceSettings)) {
      CreateOrderTrackingSignalRequest request =
          CreateOrderTrackingSignalRequest.newBuilder()
              .setParent(parent)
              .setOrderTrackingSignal(
                  OrderTrackingSignal.newBuilder()
                      // Unique order ID across all merchants orders.
                      .setOrderId("unique_order_id443455")
                      // If sending signal on behalf of another merchant use setMerchantId to
                      // indicate the merchant.
                      // .setMerchantId(123L)
                      .setOrderCreatedTime(orderCreatedTime)
                      .addShippingInfo(shippingInfo1)
                      .addShippingInfo(shippingInfo2)
                      // Details of the line items in the order including quantity and fields
                      // identifying the product.
                      .addLineItems(
                          LineItemDetails.newBuilder()
                              .setQuantity(2)
                              .setProductId(firstProductId)
                              .setLineItemId("item1"))
                      .addLineItems(
                          LineItemDetails.newBuilder()
                              .setQuantity(1)
                              .setProductId(secondProductId)
                              .setLineItemId("item2")
                              // Optional fields used to identify the product when product ID is not
                              // sufficient.
                              .setMpn("00638HAY")
                              .setProductTitle("Tshirt-small-blue")
                              .setBrand("Brand1")
                              // Any GTIN associated with the product.
                              .setGtin("001234567890"))
                      // Mapping of line items to shipments.
                      .addShipmentLineItemMapping(
                          ShipmentLineItemMapping.newBuilder()
                              .setShipmentId("shipment_id1")
                              .setLineItemId("item2")
                              .setQuantity(1))
                      .addShipmentLineItemMapping(
                          ShipmentLineItemMapping.newBuilder()
                              .setShipmentId("shipment_id2")
                              .setLineItemId("item1")
                              .setQuantity(1))
                      .addShipmentLineItemMapping(
                          ShipmentLineItemMapping.newBuilder()
                              .setShipmentId("shipment_id1")
                              .setLineItemId("item1")
                              .setQuantity(1))
                      // The price represented as a number in micros (1 million micros is an
                      // equivalent to one's currency standard unit, for example, 1 USD = 1000000
                      // micros).
                      .setCustomerShippingFee(
                          Price.newBuilder()
                              // Equivalent to 5 USD.
                              .setAmountMicros(5000000)
                              .setCurrencyCode("USD"))
                      .setDeliveryPostalCode("10011"))
              .build();
      System.out.println("Sending Create OrderTrackingSignal request.");
      OrderTrackingSignal response =
          orderTrackingSignalsServiceClient.createOrderTrackingSignal(request);
      System.out.println("Created OrderTrackingSignal below.");
      System.out.println(response);

    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // All products in the order. Replace with actual products in the order. Be sure to include all
    // products in the order.
    String productId1 = "online~en~us~sku123";
    String productId2 = "online~en~us~skuabc";
    List<String> productIds = Arrays.asList(productId1, productId2);
    createOrderTrackingSignal(config, productIds);
  }
}
// [END merchantapi_create_order_tracking_signal]
