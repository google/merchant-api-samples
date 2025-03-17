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

  # Change the query below. Documentation can be found at
  # https://developers.google.com/merchant/api/guides/reports/query-language
  product_performance_view_query = (
      "SELECT offer_id, clicks, impressions"
      " FROM product_performance_view"
      " WHERE date BETWEEN '2025-03-03' AND '2025-03-10'"
  )

  # Create the search report request.
  request = SearchRequest(
      parent=parent,
      query=product_performance_view_query,
  )

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
