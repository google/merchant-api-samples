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

package shopping.merchant.samples.datasources.v1beta;

// [START merchantapi_fetch_file_data_source]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.datasources.v1beta.DataSourceName;
import com.google.shopping.merchant.datasources.v1beta.DataSourcesServiceClient;
import com.google.shopping.merchant.datasources.v1beta.DataSourcesServiceSettings;
import com.google.shopping.merchant.datasources.v1beta.FetchDataSourceRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to fetch a specific FileInput DataSource for a given Merchant Center
 * account.
 */
public class FetchFileDataSourceSample {

  public static void fetchDataSource(Config config, String dataSourceId) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    DataSourcesServiceSettings dataSourcesServiceSettings =
        DataSourcesServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates datasource name to identify datasource.
    String name =
        DataSourceName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setDatasource(dataSourceId)
            .build()
            .toString();

    // Calls the API and catches and prints any network failures/errors.
    try (DataSourcesServiceClient dataSourcesServiceClient =
        DataSourcesServiceClient.create(dataSourcesServiceSettings)) {

      // The name has the format: accounts/{account}/datasources/{datasource}
      FetchDataSourceRequest request = FetchDataSourceRequest.newBuilder().setName(name).build();

      System.out.println("Sending FETCH DataSource request:");
      // Fetch works ONLY for FileInput DataSource type.
      dataSourcesServiceClient.fetchDataSource(request); // No response returned on success

      System.out.println("Successfully fetched DataSource.");

    } catch (Exception e) {
      System.out.println(e);
      System.exit(1);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    String datasourceId = "<DATASOURCE_ID>"; // Replace with your FileInput DataSource ID.

    fetchDataSource(config, datasourceId);
  }
}
// [END merchantapi_fetch_file_data_source]
