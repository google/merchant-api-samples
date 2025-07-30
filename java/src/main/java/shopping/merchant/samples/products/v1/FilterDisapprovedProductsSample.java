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

package shopping.merchant.samples.products.v1;

// [START merchantapi_filter_disapproved_products]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.products.v1.GetProductRequest;
import com.google.shopping.merchant.products.v1.Product;
import com.google.shopping.merchant.products.v1.ProductsServiceClient;
import com.google.shopping.merchant.products.v1.ProductsServiceSettings;
import com.google.shopping.merchant.reports.v1.ReportRow;
import com.google.shopping.merchant.reports.v1.ReportServiceClient;
import com.google.shopping.merchant.reports.v1.ReportServiceClient.SearchPagedResponse;
import com.google.shopping.merchant.reports.v1.ReportServiceSettings;
import com.google.shopping.merchant.reports.v1.SearchRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to get the list of all the disapproved products for a given merchant
 * center account.
 */
public class FilterDisapprovedProductsSample {

  // Gets the product details for a given product using the GetProduct method.
  public static void getProduct(GoogleCredentials credential, Config config, String productName)
      throws Exception {

    // Creates service settings using the credentials retrieved above.
    ProductsServiceSettings productsServiceSettings =
        ProductsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (ProductsServiceClient productsServiceClient =
        ProductsServiceClient.create(productsServiceSettings)) {

      // The name has the format: accounts/{account}/products/{productId}
      GetProductRequest request = GetProductRequest.newBuilder().setName(productName).build();
      Product response = productsServiceClient.getProduct(request);
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  // Filters the disapproved products for a given Merchant Center account using the Reporting API.
  // Then, it prints the product details for each disapproved product.
  public static void filterDisapprovedProducts(Config config) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    ReportServiceSettings reportServiceSettings =
        ReportServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    try (ReportServiceClient reportServiceClient =
        ReportServiceClient.create(reportServiceSettings)) {

      // The parent has the format: accounts/{accountId}
      String parent = String.format("accounts/%s", config.getAccountId().toString());
      // The query below is an example of a query for the productView that gets product informations
      // for all disapproved products.
      String query =
          "SELECT offer_id,"
              + "id,"
              + "title,"
              + "price"
              + " FROM product_view"
              // aggregated_reporting_context_status can be one of the following values:
              // NOT_ELIGIBLE_OR_DISAPPROVED, ELIGIBLE, PENDING, ELIGIBLE_LIMITED,
              // AGGREGATED_REPORTING_CONTEXT_STATUS_UNSPECIFIED
              + " WHERE aggregated_reporting_context_status = 'NOT_ELIGIBLE_OR_DISAPPROVED'";

      // Create the search report request.
      SearchRequest request = SearchRequest.newBuilder().setParent(parent).setQuery(query).build();

      System.out.println("Sending search report request for Product View.");
      // Calls the Reports.search API method.
      SearchPagedResponse response = reportServiceClient.search(request);
      System.out.println("Received search reports response: ");
      // Iterates over all report rows in all pages and prints each report row in separate line.
      // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
      for (ReportRow row : response.iterateAll()) {
        System.out.println("Printing data from Product View:");
        System.out.println(row);
        // Optionally, you can get the full product details by calling the GetProduct method.
        String productName =
            "accounts/"
                + config.getAccountId().toString()
                + "/products/"
                + row.getProductView().getId();
        System.out.println("Getting full product details by calling GetProduct method:");
        getProduct(credential, config, productName);
      }

    } catch (Exception e) {
      System.out.println("Failed to search reports for Product View.");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    filterDisapprovedProducts(config);
  }
}
// [END merchantapi_filter_disapproved_products]