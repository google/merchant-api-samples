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
"""A module to search a report."""

# [START merchantapi_search_report]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_reports_v1beta import ReportServiceClient
from google.shopping.merchant_reports_v1beta import SearchRequest

_ACCOUNT = configuration.Configuration().read_merchant_info()


def search_report(account_id):
  """Searches a report for a given Merchant Center account."""

  # Gets OAuth Credentials.
  credentials = generate_user_credentials.main()

  # Creates a client.
  report_service_client = ReportServiceClient(credentials=credentials)

  # The parent has the format: accounts/{accountId}
  parent = f"accounts/{account_id}"

  # Uncomment the desired query from below. Documentation can be found at
  # https://developers.google.com/merchant/api/reference/rest/reports_v1beta/accounts.reports#ReportRow
  # The query below is an example of a query for the product_view.
  query = (
      "SELECT offer_id,"
      "id,"
      "price,"
      "gtin,"
      "item_issues,"
      "channel,"
      "language_code,"
      "feed_label,"
      "title,"
      "brand,"
      "category_l1,"
      "product_type_l1,"
      "availability,"
      "shipping_label,"
      "thumbnail_link,"
      "click_potential"
      " FROM product_view"
  )

  # The query below is an example of a query for the
  # price_competitiveness_product_view.
  # query = (
  #     "SELECT offer_id,"
  #     "id,"
  #     "benchmark_price,"
  #     "report_country_code,"
  #     "price,"
  #     "title,"
  #     "brand,"
  #     "category_l1,"
  #     "product_type_l1"
  #     " FROM price_competitiveness_product_view"
  #     " WHERE date BETWEEN '2023-03-03' AND '2025-03-10'"
  # )

  # The query below is an example of a query for the
  # price_insights_product_view.
  # query = (
  #     "SELECT offer_id,"
  #     "id,"
  #     "suggested_price,"
  #     "price,"
  #     "effectiveness,"
  #     "title,"
  #     "brand,"
  #     "category_l1,"
  #     "product_type_l1,"
  #     "predicted_impressions_change_fraction,"
  #     "predicted_clicks_change_fraction,"
  #     "predicted_conversions_change_fraction"
  #     " FROM price_insights_product_view"
  # )

  # The query below is an example of a query for the product_performance_view.
  # query = (
  #   "SELECT offer_id,"
  #    "conversion_value,"
  #    "marketing_method,"
  #    "customer_country_code,"
  #    "title,"
  #    "brand,"
  #    "category_l1,"
  #    "product_type_l1,"
  #    "custom_label0,"
  #    "clicks,"
  #    "impressions,"
  #    "click_through_rate,"
  #    "conversions,"
  #    "conversion_rate"
  #    " FROM product_performance_view"
  #    " WHERE date BETWEEN '2023-03-03' AND '2025-03-10'"
  # )

  # Create the search report request.
  request = SearchRequest(parent=parent, query=query)

  print("Sending search reports request.")
  try:
    response = report_service_client.search(request=request)
    print("Received search reports response: ")
    # Iterates over all report rows in all pages and prints the report row in
    # each row. Automatically uses the `nextPageToken` if returned to fetch all
    # pages of data.
    for row in response:
      print(row)
  except RuntimeError as e:
    print("Failed to search reports.")
    print(e)


if __name__ == "__main__":
  search_report(_ACCOUNT)

# [END merchantapi_search_report]
