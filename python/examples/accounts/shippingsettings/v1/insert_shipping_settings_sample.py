# -*- coding: utf-8 -*-
# Copyright 2024 Google LLC
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
"""A module to insert a ShippingSettings for a given Merchant Center account."""

# [START merchantapi_insert_shippingsettings]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_accounts_v1 import DeliveryTime
from google.shopping.merchant_accounts_v1 import InsertShippingSettingsRequest
from google.shopping.merchant_accounts_v1 import RateGroup
from google.shopping.merchant_accounts_v1 import Service
from google.shopping.merchant_accounts_v1 import ShippingSettings
from google.shopping.merchant_accounts_v1 import ShippingSettingsServiceClient
from google.shopping.merchant_accounts_v1 import Value

_ACCOUNT = configuration.Configuration().read_merchant_info()
_PARENT = f"accounts/{_ACCOUNT}"


def insert_shipping_settings():
  """Inserts a ShippingSettings for a Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  client = ShippingSettingsServiceClient(credentials=credentials)

  # Creates the request.
  request = InsertShippingSettingsRequest(
      parent=_PARENT,
      shipping_setting=ShippingSettings(
          # Etag needs to be an empty string on initial insert
          # On future inserts, call GET first to get the Etag
          # Then use the retrieved Etag on future inserts.
          # NOTE THAT ON THE INITIAL INSERT, YOUR SHIPPING SETTINGS WILL
          # NOT BE STORED, YOU HAVE TO CALL INSERT AGAIN WITH YOUR
          # RETRIEVED ETAG.
          etag="",
          services=[
              Service(
                  service_name="Canadian Postal Service",
                  active=True,
                  delivery_countries=["CA"],
                  currency_code="CAD",
                  delivery_time=DeliveryTime(
                      min_transit_days=0,
                      max_transit_days=3,
                      min_handling_days=0,
                      max_handling_days=3,
                  ),
                  rate_groups=[
                      RateGroup(
                          applicable_shipping_labels=[
                              "Oversized",
                              "Perishable",
                          ],
                          single_value=Value(price_percentage="5.4"),
                          name="Oversized and Perishable items",
                      )
                  ],
                  shipment_type=Service.ShipmentType.DELIVERY,
                  minimum_order_value={
                      "amount_micros": 10000000,
                      "currency_code": "CAD",
                  },
              )
          ],
      ),
  )

  # Makes the request and prints the inserted ShippingSettings name.
  try:
    response = client.insert_shipping_settings(request=request)
    print("Inserted ShippingSettings below")
    print(response)
    # You can apply ShippingSettings to specific products by using the
    # `shippingLabel` field on the product.
  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  insert_shipping_settings()

# [END merchantapi_insert_shippingsettings]
