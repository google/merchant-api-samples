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

// [START merchantapi_search_report]

/**
 * Searches a report for a given Merchant Center account.
 */
function searchReport() {
  // IMPORTANT:
  // Enable the Merchant API Reports Bundle Advanced Service and call it
  // "MerchantApiReports"

  // Replace this with your Merchant Center ID.
  const accountId = '<MERCHANT_CENTER_ID>';

  // Construct the parent name
  const parent = 'accounts/' + accountId;

  try {
    console.log('Sending search Report request');
    // Set pageSize to the maximum value (default: 1000)
    let pageSize = 1000;
    let pageToken;
    // Uncomment the desired query from below. Documentation can be found at
    // https://developers.google.com/merchant/api/reference/rest/reports_v1beta/accounts.reports#ReportRow
    // The query below is an example of a query for the product_view.
    let query = 'SELECT offer_id,' +
        'id,' +
        'price,' +
        'gtin,' +
        'item_issues,' +
        'channel,' +
        'language_code,' +
        'feed_label,' +
        'title,' +
        'brand,' +
        'category_l1,' +
        'product_type_l1,' +
        'availability,' +
        'shipping_label,' +
        'thumbnail_link,' +
        'click_potential' +
        ' FROM product_view';

    /*
    // The query below is an example of a query for the
    price_competitiveness_product_view. let query = "SELECT offer_id,"
                 + "id,"
                 + "benchmark_price,"
                 + "report_country_code,"
                 + "price,"
                 + "title,"
                 + "brand,"
                 + "category_l1,"
                 + "product_type_l1"
                + " FROM price_competitiveness_product_view"
                + " WHERE date BETWEEN '2023-03-03' AND '2025-03-10'"; */
    /*
    // The query below is an example of a query for the
    price_insights_product_view. let query = "SELECT offer_id,"
                     + "id,"
                     + "suggested_price,"
                     + "price,"
                     + "effectiveness,"
                     + "title,"
                     + "brand,"
                     + "category_l1,"
                     + "product_type_l1,"
                     + "predicted_impressions_change_fraction,"
                     + "predicted_clicks_change_fraction,"
                     + "predicted_conversions_change_fraction"
                    + " FROM price_insights_product_view"; */

    /*
    // The query below is an example of a query for the
    product_performance_view. let query = "SELECT offer_id,"
            + "conversion_value,"
            + "marketing_method,"
            + "customer_country_code,"
            + "title,"
            + "brand,"
            + "category_l1,"
            + "product_type_l1,"
            + "custom_label0,"
            + "clicks,"
            + "impressions,"
            + "click_through_rate,"
            + "conversions,"
            + "conversion_rate"
            + " FROM product_performance_view"
            + " WHERE date BETWEEN '2023-03-03' AND '2025-03-10'"; */

    // Call the Reports.search API method. Use the pageToken to iterate through
    // all pages of results.
    do {
      response =
          MerchantApiReports.Accounts.Reports.search({query, pageSize, pageToken}, parent);
      for (const reportRow of response.results) {
        console.log(reportRow);
      }
      pageToken = response.nextPageToken;
    } while (pageToken);  // Exits when there is no next page token.

  } catch (e) {
    console.log('ERROR!');
    console.log(e);
    console.log('Error message:' + e.message);
    if (e.stack) {
      console.log('Stack trace:' + e.stack);
    }
  }
}


// [END merchantapi_search_report]