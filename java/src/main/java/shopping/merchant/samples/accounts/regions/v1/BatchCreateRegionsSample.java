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

// [START merchantapi_batch_create_regions]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.BatchCreateRegionsRequest;
import com.google.shopping.merchant.accounts.v1.BatchCreateRegionsResponse;
import com.google.shopping.merchant.accounts.v1.CreateRegionRequest;
import com.google.shopping.merchant.accounts.v1.Region;
import com.google.shopping.merchant.accounts.v1.Region.PostalCodeArea;
import com.google.shopping.merchant.accounts.v1.Region.PostalCodeArea.PostalCodeRange;
import com.google.shopping.merchant.accounts.v1.RegionsServiceClient;
import com.google.shopping.merchant.accounts.v1.RegionsServiceSettings;
import java.util.ArrayList;
import java.util.List;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to create multiple regions for a Merchant Center account. */
public class BatchCreateRegionsSample {

  private static String getParent(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  public static void batchCreateRegions(Config config, List<String> regionIds) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    RegionsServiceSettings regionsServiceSettings =
        RegionsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates parent to identify where to insert the regions.
    String parent = getParent(config.getAccountId().toString());

    // Calls the API and catches and prints any network failures/errors.
    try (RegionsServiceClient regionsServiceClient =
        RegionsServiceClient.create(regionsServiceSettings)) {

      List<CreateRegionRequest> requests = new ArrayList<>();
      for (String regionId : regionIds) {
        requests.add(
            CreateRegionRequest.newBuilder()
                .setParent(parent)
                .setRegionId(regionId)
                .setRegion(
                    Region.newBuilder()
                        .setDisplayName("Region " + regionId)
                        .setPostalCodeArea(
                            PostalCodeArea.newBuilder()
                                .setRegionCode("US")
                                .addPostalCodes(
                                    PostalCodeRange.newBuilder()
                                        .setBegin("10001")
                                        .setEnd("10282")
                                        .build())
                                .build())
                        .build())
                .build());
      }

      BatchCreateRegionsRequest request =
          BatchCreateRegionsRequest.newBuilder().setParent(parent).addAllRequests(requests).build();

      System.out.println("Sending Batch Create Regions request");
      BatchCreateRegionsResponse response = regionsServiceClient.batchCreateRegions(request);
      System.out.println("Inserted Regions Names below");
      // The last part of the region name will be the ID of the region.
      // Format: `accounts/{account}/region/{region}`
      response.getRegionsList().forEach(region -> System.out.println(region.getName()));

    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // The unique IDs of the regions to create.
    List<String> regionIds = new ArrayList<>();
    regionIds.add("REGION_1");
    regionIds.add("REGION_2");
    regionIds.add("REGION_3");
    regionIds.add("REGION_4");
    regionIds.add("REGION_5");

    batchCreateRegions(config, regionIds);
  }
}
// [END merchantapi_batch_create_regions]
