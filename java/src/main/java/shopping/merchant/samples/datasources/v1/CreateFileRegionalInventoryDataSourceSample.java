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
// [START merchantapi_create_file_regional_inventory_data_source]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.datasources.v1.CreateDataSourceRequest;
import com.google.shopping.merchant.datasources.v1.DataSource;
import com.google.shopping.merchant.datasources.v1.DataSourcesServiceClient;
import com.google.shopping.merchant.datasources.v1.DataSourcesServiceSettings;
import com.google.shopping.merchant.datasources.v1.FileInput;
import com.google.shopping.merchant.datasources.v1.RegionalInventoryDataSource;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to create a regional inventory datasource */
public class CreateFileRegionalInventoryDataSourceSample {

  private static String getParent(String merchantId) {
    return String.format("accounts/%s", merchantId);
  }

  private static FileInput setFileInput() {
    // If FetchSettings are not set, then this will be an `UPLOAD` file type
    // that you must manually upload via the Merchant Center UI.
    return FileInput.newBuilder()
        // FileName is required for `UPLOAD` fileInput type.
        .setFileName("British T-shirts Regional Inventory Data")
        .build();
  }

  public static String createDataSource(Config config, String displayName, FileInput fileInput)
      throws Exception {
    GoogleCredentials credential = new Authenticator().authenticate();

    DataSourcesServiceSettings dataSourcesServiceSettings =
        DataSourcesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    String parent = getParent(config.getAccountId().toString());

    // RegionalInventoryDataSources can only be created for a specific `feedLabel` and
    // `contentLanguage` combination.
    RegionalInventoryDataSource regionalInventoryDataSource =
        RegionalInventoryDataSource.newBuilder()
            .setContentLanguage("en")
            .setFeedLabel("GB")
            .build();

    try (DataSourcesServiceClient dataSourcesServiceClient =
        DataSourcesServiceClient.create(dataSourcesServiceSettings)) {

      CreateDataSourceRequest request =
          CreateDataSourceRequest.newBuilder()
              .setParent(parent)
              .setDataSource(
                  DataSource.newBuilder()
                      .setDisplayName(displayName)
                      .setRegionalInventoryDataSource(regionalInventoryDataSource)
                      .setFileInput(fileInput)
                      .build())
              .build();

      System.out.println("Sending Create Regional Inventory DataSource request");
      DataSource response = dataSourcesServiceClient.createDataSource(request);
      System.out.println("Inserted DataSource Name below");
      System.out.println(response.getName());
      return response.getName();
    } catch (Exception e) {
      System.out.println(e);
      System.exit(1);
      return null;
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // The displayed datasource name in the Merchant Center UI.
    String displayName = "British Regional Inventory File";

    // The file input data that this datasource will receive.
    FileInput fileInput = setFileInput();

    createDataSource(config, displayName, fileInput);
  }
}
// [END merchantapi_create_file_regional_inventory_data_source]