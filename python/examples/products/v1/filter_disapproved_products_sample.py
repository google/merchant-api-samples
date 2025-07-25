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
"""A module to filter disapproved products."""

# [START merchantapi_filter_disapproved_products]
from examples.authentication import configuration
from examples.authentication import generate_user_credentials
from google.shopping.merchant_products_v1beta import GetProductRequest
from google.shopping.merchant_products_v1beta import ProductsServiceClient
from google.shopping.merchant_reports_v1beta import ReportServiceClient
from google.shopping.merchant_reports_v1beta import SearchRequest

# Read the merchant account ID from the configuration file.
# This is a global variable used by the functions below.
_ACCOUNT_ID = configuration.Configuration().read_merchant_info()


def get_product(credentials, product_name: str):
  """Gets the product details for a given product name.

  Args:
    credentials: The OAuth2 credentials.
    product_name: The full resource name of the product, e.g.,
      "accounts/{account}/products/{product}".
  """
  # Create a Products API client.
  products_service_client = ProductsServiceClient(credentials=credentials)

  # Prepare the GetProduct request.
  # The name has the format: accounts/{account}/products/{productId}
  request = GetProductRequest(name=product_name)

  # Call the API and print the response or any errors.
  try:
    response = products_service_client.get_product(request=request)
    print(response)
  except RuntimeError as e:
    print(f"Failed to get product {product_name}:")
    print(e)


def filter_disapproved_products():
  """Filters disapproved products and prints their details."""
  # Get OAuth2 credentials.
  credentials = generate_user_credentials.main()

  # Create a Report API client.
  report_service_client = ReportServiceClient(credentials=credentials)

  # Construct the parent resource name for the account.
  # The parent has the format: accounts/{accountId}
  parent = f"accounts/{_ACCOUNT_ID}"

  # Define the query to select disapproved products.
  # This query retrieves product information for all disapproved products.
  # aggregated_reporting_context_status can be one of the following values:
  # NOT_ELIGIBLE_OR_DISAPPROVED, ELIGIBLE, PENDING, ELIGIBLE_LIMITED,
  # AGGREGATED_REPORTING_CONTEXT_STATUS_UNSPECIFIED
  query = (
      "SELECT offer_id, id, title, price "
      "FROM product_view "
      "WHERE aggregated_reporting_context_status ="
      "'NOT_ELIGIBLE_OR_DISAPPROVED'"
  )

  # Create the search report request.
  request = SearchRequest(parent=parent, query=query)

  print("Sending search report request for Product View.")
  try:
    # Call the Reports.search API method.
    response = report_service_client.search(request=request)
    print("Received search reports response: ")

    # Iterate over all report rows.
    # The client library automatically handles pagination.
    for row in response:
      print("Printing data from Product View:")
      print(row)

      # Construct the full product resource name using the product_view.id
      # (which is the REST ID like "online~en~GB~123") from the report.
      # The product_view.id from the report is the {product_id} part.
      product_name = (
          f"accounts/{_ACCOUNT_ID}/products/{row.product_view.id}"
      )

      # OPTIONAL, get full product details by calling the GetProduct method.
      print("Getting full product details by calling GetProduct method:")
      get_product(credentials, product_name)

  except RuntimeError as e:
    print(e)


if __name__ == "__main__":
  filter_disapproved_products()
# [END merchantapi_filter_disapproved_products]
