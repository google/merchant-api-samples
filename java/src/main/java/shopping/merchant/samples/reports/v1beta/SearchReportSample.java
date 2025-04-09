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

package shopping.merchant.samples.reports.v1beta;

// [START merchantapi_search_report]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.reports.v1beta.ReportRow;
import com.google.shopping.merchant.reports.v1beta.ReportServiceClient;
import com.google.shopping.merchant.reports.v1beta.ReportServiceClient.SearchPagedResponse;
import com.google.shopping.merchant.reports.v1beta.ReportServiceSettings;
import com.google.shopping.merchant.reports.v1beta.SearchRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to search reports for a given Merchant Center account. */
public class SearchReportSample {

  public static void searchReports(String accountId) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    ReportServiceSettings reportServiceSettings =
        ReportServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (ReportServiceClient reportServiceClient =
        ReportServiceClient.create(reportServiceSettings)) {

      // The parent has the format: accounts/{accountId}
      String parent = String.format("accounts/%s", accountId);

      // Uncomment the desired query from below. Documentation can be found at
      // https://developers.google.com/merchant/api/guides/reports/query-language
      // The query below is an example of a query for the product_view.
      String query =
          "SELECT offer_id,"
              + "id,"
              + "price,"
              + "gtin,"
              + "item_issues,"
              + "channel,"
              + "language_code,"
              + "feed_label,"
              + "title,"
              + "brand,"
              + "category_l1,"
              + "product_type_l1,"
              + "availability,"
              + "shipping_label,"
              + "thumbnail_link,"
              + "click_potential"
              + " FROM product_view";

      /*
      // The query below is an example of a query for the price_competitiveness_product_view.
      String query =
              "SELECT offer_id,"
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
      // The query below is an example of a query for the price_insights_product_view.
      String query =
                  "SELECT offer_id,"
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
      // The query below is an example of a query for the product_performance_view.
      String query =
          "SELECT offer_id,"
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

      // Create the search report request.
      SearchRequest request = SearchRequest.newBuilder().setParent(parent).setQuery(query).build();

      System.out.println("Sending search reports request.");
      SearchPagedResponse response = reportServiceClient.search(request);
      System.out.println("Received search reports response: ");
      // Iterates over all report rows in all pages and prints the report row in each row.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (ReportRow row : response.iterateAll()) {
        System.out.println(row);
      }

    } catch (Exception e) {
      System.out.println("Failed to search reports.");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    searchReports(config.getAccountId().toString());
  }
}
// [END merchantapi_search_report]
