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

package shopping.merchant.samples.accounts.regions.v1;

// [START merchantapi_batch_update_regions]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1.BatchUpdateRegionsRequest;
import com.google.shopping.merchant.accounts.v1.BatchUpdateRegionsResponse;
import com.google.shopping.merchant.accounts.v1.Region;
import com.google.shopping.merchant.accounts.v1.RegionsServiceClient;
import com.google.shopping.merchant.accounts.v1.RegionsServiceSettings;
import com.google.shopping.merchant.accounts.v1.UpdateRegionRequest;
import java.util.ArrayList;
import java.util.List;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to update multiple regions for a Merchant Center account. */
public class BatchUpdateRegionsSample {

  private static String getParent(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  private static String getRegionName(String accountId, String regionId) {
    return String.format("accounts/%s/regions/%s", accountId, regionId);
  }

  public static void batchUpdateRegions(Config config, List<String> regionIds) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    RegionsServiceSettings regionsServiceSettings =
        RegionsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates parent to identify where to update the regions.
    String parent = getParent(config.getAccountId().toString());
    String accountId = config.getAccountId().toString();

    // Calls the API and catches and prints any network failures/errors.
    try (RegionsServiceClient regionsServiceClient =
        RegionsServiceClient.create(regionsServiceSettings)) {

      List<UpdateRegionRequest> requests = new ArrayList<>();
      for (String regionId : regionIds) {
        requests.add(
            UpdateRegionRequest.newBuilder()
                .setRegion(
                    Region.newBuilder()
                        .setName(getRegionName(accountId, regionId))
                        .setDisplayName("Updated Region " + regionId)
                        .build())
                .setUpdateMask(FieldMask.newBuilder().addPaths("display_name").build())
                .build());
      }

      BatchUpdateRegionsRequest request =
          BatchUpdateRegionsRequest.newBuilder().setParent(parent).addAllRequests(requests).build();

      System.out.println("Sending Batch Update Regions request");
      BatchUpdateRegionsResponse response = regionsServiceClient.batchUpdateRegions(request);
      System.out.println("Updated Regions Names below");
      // The last part of the region name will be the ID of the region.
      // Format: `accounts/{account}/region/{region}`
      response.getRegionsList().forEach(region -> System.out.println(region.getName()));

    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // The unique IDs of the regions to update.
    List<String> regionIds = new ArrayList<>();
    regionIds.add("REGION_1");
    regionIds.add("REGION_2");
    regionIds.add("REGION_3");
    regionIds.add("REGION_4");
    regionIds.add("REGION_5");

    batchUpdateRegions(config, regionIds);
  }
}
// [END merchantapi_batch_update_regions]
