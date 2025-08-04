# -*- coding: utf-8 -*-
# Copyright 2025 Google LLC
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
"""A module to create OrderTrackingSignals."""

# [START merchantapi_create_order_tracking_signal]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_ordertracking_v1 import CreateOrderTrackingSignalRequest
from google.shopping.merchant_ordertracking_v1 import OrderTrackingSignalsServiceClient
from google.shopping.merchant_ordertracking_v1.types import OrderTrackingSignal
from google.shopping.type import Price
from google.type import datetime_pb2

# Read the Merchant Center account ID from the configuration.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()
# Construct the parent resource name for API calls.
_PARENT = f"accounts/{_ACCOUNT_ID}"


def create_order_tracking_signal(product_ids: list[str]):
  """Creates an order tracking signal for a given merchant account and product IDs."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client for the Order Tracking Signals API.
  client = OrderTrackingSignalsServiceClient(credentials=credentials)

  first_product_id = product_ids[0]
  second_product_id = product_ids[1]

  # Define the time the order was created.
  order_created_time = datetime_pb2.DateTime(
      year=2025,
      month=3,
      day=24,
      hours=12,
      minutes=2,
      seconds=22,
      time_zone=datetime_pb2.TimeZone(id="America/Los_Angeles"),
  )
  # Define the time the first shipment was made.
  shipped_time1 = datetime_pb2.DateTime(
      year=2025,
      month=3,
      day=25,
      hours=16,
      minutes=22,
      time_zone=datetime_pb2.TimeZone(id="America/Los_Angeles"),
  )
  # Define the time the second shipment was made.
  shipped_time2 = datetime_pb2.DateTime(
      year=2025,
      month=3,
      day=26,
      hours=16,
      minutes=22,
      time_zone=datetime_pb2.TimeZone(id="America/Los_Angeles"),
  )
  # Define the earliest promised delivery time.
  earliest_delivery_promise_time = datetime_pb2.DateTime(
      year=2025,
      month=3,
      day=27,
      time_zone=datetime_pb2.TimeZone(id="America/Los_Angeles"),
  )
  # Define the latest promised delivery time.
  latest_delivery_promise_time = datetime_pb2.DateTime(
      year=2025,
      month=3,
      day=30,
      time_zone=datetime_pb2.TimeZone(id="America/Los_Angeles"),
  )
  # Define the actual delivery time.
  actual_delivery_time = datetime_pb2.DateTime(
      year=2025,
      month=3,
      day=29,
      hours=16,
      minutes=22,
      time_zone=datetime_pb2.TimeZone(id="America/Los_Angeles"),
  )

  # Define shipping information for the first shipment.
  shipping_info1 = OrderTrackingSignal.ShippingInfo(
      shipment_id="shipment_id1",
      carrier="UPS",
      carrier_service="Ground",
      tracking_id="1Z23456789",
      shipped_time=shipped_time1,
      earliest_delivery_promise_time=earliest_delivery_promise_time,
      latest_delivery_promise_time=latest_delivery_promise_time,
      actual_delivery_time=actual_delivery_time,
      shipping_status=OrderTrackingSignal.ShippingInfo.ShippingState.DELIVERED,
      origin_postal_code="94043",
      origin_region_code="US",
  )
  # Define shipping information for the second shipment.
  shipping_info2 = OrderTrackingSignal.ShippingInfo(
      shipment_id="shipment_id2",
      carrier="USPS",
      carrier_service="Ground Advantage",
      tracking_id="987654321",
      shipped_time=shipped_time2,
      shipping_status=OrderTrackingSignal.ShippingInfo.ShippingState.SHIPPED,
      origin_postal_code="94043",
      origin_region_code="US",
  )

  # Construct the OrderTrackingSignal payload.
  order_tracking_signal_payload = OrderTrackingSignal(
      # Unique order ID across all merchants orders.
      order_id="unique_order_id443455",
      # If sending signal on behalf of another merchant use merchant_id to
      # indicate the merchant.
      # merchant_id=123,
      order_created_time=order_created_time,
      shipping_info=[shipping_info1, shipping_info2],
      # Details of the line items in the order including quantity and fields
      # identifying the product.
      line_items=[
          OrderTrackingSignal.LineItemDetails(
              quantity=2, product_id=first_product_id, line_item_id="item1"
          ),
          OrderTrackingSignal.LineItemDetails(
              quantity=1,
              product_id=second_product_id,
              line_item_id="item2",
              # Optional fields used to identify the product when product ID is
              # not sufficient.
              mpn="00638HAY",
              product_title="Tshirt-small-blue",
              brand="Brand1",
              # Any GTINs associated with the product.
              gtins=["001234567890"],
          ),
      ],
      # Mapping of line items to shipments.
      shipment_line_item_mapping=[
          OrderTrackingSignal.ShipmentLineItemMapping(
              shipment_id="shipment_id1", line_item_id="item2", quantity=1
          ),
          OrderTrackingSignal.ShipmentLineItemMapping(
              shipment_id="shipment_id2", line_item_id="item1", quantity=1
          ),
          OrderTrackingSignal.ShipmentLineItemMapping(
              shipment_id="shipment_id1", line_item_id="item1", quantity=1
          ),
      ],
      # The price represented as a number in micros (1 million micros is an
      # equivalent to one's currency standard unit, for example, 1 USD = 1000000
      # micros).
      customer_shipping_fee=Price(
          # Equivalent to 5 USD.
          amount_micros=5000000,
          currency_code="USD",
      ),
      delivery_postal_code="10011",
  )

  # Create the request object.
  request = CreateOrderTrackingSignalRequest(
      parent=_PARENT, order_tracking_signal=order_tracking_signal_payload
  )

  # Make the API call to create the order tracking signal.
  try:
    print("Sending Create OrderTrackingSignal request.")
    response = client.create_order_tracking_signal(request=request)
    print("Created OrderTrackingSignal below.")
    print(response)
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  # All products in the order. Replace with actual products in the order.
  # Be sure to include all products in the order.
  product_id1 = "online~en~US~sku123"
  product_id2 = "online~en~US~sku1234"
  product_ids_list = [product_id1, product_id2]
  create_order_tracking_signal(product_ids_list)

# [END merchantapi_create_order_tracking_signal]
