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

package shopping.merchant.samples.accounts.accounttax.v1beta;
// [START merchantapi_update_account_tax]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.accounts.v1beta.AccountTax;
import com.google.shopping.merchant.accounts.v1beta.AccountTaxName;
import com.google.shopping.merchant.accounts.v1beta.AccountTaxServiceClient;
import com.google.shopping.merchant.accounts.v1beta.AccountTaxServiceSettings;
import com.google.shopping.merchant.accounts.v1beta.TaxRule;
import com.google.shopping.merchant.accounts.v1beta.UpdateAccountTaxRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to update AccountTax to be enabled. */
public class UpdateAccountTaxSample {

  public static void updateAccountTax(Config config) throws Exception {

    GoogleCredentials credential = new Authenticator().authenticate();

    AccountTaxServiceSettings accountTaxServiceSettings =
        AccountTaxServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates AccountTax name to identify AccountTax.
    String name =
        AccountTaxName.newBuilder().setAccount(config.getAccountId().toString()).build().toString();

    // Create AccountTax with the updated fields.
    AccountTax accountTax =
        AccountTax.newBuilder()
            .setName(name)
            .addTaxRules(
                TaxRule.newBuilder()
                    .setRegionCode("US")
                    .setLocationId(21137)
                    .setShippingTaxed(true)
                    .setUseGoogleRate(true)
                    .build())
            .addTaxRules(
                TaxRule.newBuilder()
                    .setRegionCode("US")
                    .setLocationId(21138)
                    .setSelfSpecifiedRateMicros(25000)
                    .build())
            .build();

    FieldMask fieldMask = FieldMask.newBuilder().addPaths("*").build();

    try (AccountTaxServiceClient accountTaxServiceClient =
        AccountTaxServiceClient.create(accountTaxServiceSettings)) {

      UpdateAccountTaxRequest request =
          UpdateAccountTaxRequest.newBuilder()
              .setAccountTax(accountTax)
              .setUpdateMask(fieldMask)
              .build();

      System.out.println("Sending Update AccountTax request");
      AccountTax response = accountTaxServiceClient.updateAccountTax(request);
      System.out.println("Updated AccountTax Name below");
      System.out.println(response.getName());
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();

    updateAccountTax(config);
  }
}
// [END merchantapi_update_account_tax]