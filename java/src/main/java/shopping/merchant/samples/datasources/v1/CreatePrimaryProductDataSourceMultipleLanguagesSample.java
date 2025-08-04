// Copyright 2024 Google LLC
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

package shopping.merchant.samples.datasources.v1;

// [START merchantapi_create_primary_product_data_source_multiple_languages]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.datasources.v1.CreateDataSourceRequest;
import com.google.shopping.merchant.datasources.v1.DataSource;
import com.google.shopping.merchant.datasources.v1.DataSourcesServiceClient;
import com.google.shopping.merchant.datasources.v1.DataSourcesServiceSettings;
import com.google.shopping.merchant.datasources.v1.PrimaryProductDataSource;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to create a primary product data source for all `feedLabel` and
 * `contentLanguage` combinations.
 */
public class CreatePrimaryProductDataSourceMultipleLanguagesSample {

  private static String getParent(String merchantId) {
    return String.format("accounts/%s", merchantId);
  }

  public static String createDataSource(Config config, String displayName) throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    DataSourcesServiceSettings dataSourcesServiceSettings =
        DataSourcesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    String parent = getParent(config.getAccountId().toString());

    // The type of data that this datasource will receive.
    PrimaryProductDataSource primaryProductDataSource =
        PrimaryProductDataSource.newBuilder()
            .addCountries("GB")
            .build();

    try (DataSourcesServiceClient dataSourcesServiceClient =
        DataSourcesServiceClient.create(dataSourcesServiceSettings)) {

      CreateDataSourceRequest request =
          CreateDataSourceRequest.newBuilder()
              .setParent(parent)
              .setDataSource(
                  DataSource.newBuilder()
                      .setDisplayName(displayName)
                      .setPrimaryProductDataSource(primaryProductDataSource)
                      .build())
              .build();

      System.out.println("Sending Create PrimaryProduct DataSource request");
      DataSource response = dataSourcesServiceClient.createDataSource(request);
      System.out.println("Created DataSource Name below");
      System.out.println(response.getName());
      return response.getName();
    } catch (Exception e) {
      System.out.println(e);
      System.exit(1);
      // Null is necessary to satisfy the compiler as we're not returning a String on failure.
      return null;
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // The displayed data source name in the Merchant Center UI.
    String displayName = "Primary Product Data Multiple Languages";

    createDataSource(config, displayName);
  }
}
// [END merchantapi_create_primary_product_data_source_multiple_languages]
