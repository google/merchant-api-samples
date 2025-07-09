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

package shopping.merchant.samples.products.v1beta;
// [START merchantapi_update_product_input]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.protobuf.FieldMask;
import com.google.shopping.merchant.datasources.v1beta.DataSourceName;
import com.google.shopping.merchant.products.v1beta.Attributes;
import com.google.shopping.merchant.products.v1beta.ProductInput;
import com.google.shopping.merchant.products.v1beta.ProductInputName;
import com.google.shopping.merchant.products.v1beta.ProductInputsServiceClient;
import com.google.shopping.merchant.products.v1beta.ProductInputsServiceSettings;
import com.google.shopping.merchant.products.v1beta.UpdateProductInputRequest;
import com.google.shopping.type.CustomAttribute;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to update a product input */
public class UpdateProductInputSample {

  public static void updateProductInput(Config config, String productId, String dataSourceId)
      throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    ProductInputsServiceSettings productInputsServiceSettings =
        ProductInputsServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates product name to identify product.
    String name =
        ProductInputName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .setProductinput(productId)
            .build()
            .toString();

    // Just attributes and customAttributes can be updated
    FieldMask fieldMask =
        FieldMask.newBuilder()
            .addPaths("attributes.title")
            .addPaths("attributes.description")
            .addPaths("attributes.link")
            .addPaths("attributes.image_link")
            .addPaths("attributes.availability")
            .addPaths("attributes.condition")
            .addPaths("attributes.gtins")
            .addPaths("custom_attributes.mycustomattribute")
            .build();

    // Calls the API and catches and prints any network failures/errors.
    try (ProductInputsServiceClient productInputsServiceClient =
        ProductInputsServiceClient.create(productInputsServiceSettings)) {

      Attributes attributes =
          Attributes.newBuilder()
              .setTitle("A Tale of Two Cities")
              .setDescription("A classic novel about the French Revolution")
              .setLink("https://exampleWebsite.com/tale-of-two-cities.html")
              .setImageLink("https://exampleWebsite.com/tale-of-two-cities.jpg")
              .setAvailability("in stock")
              .setCondition("new")
              .addGtin("9780007350896")
              .build();

      // The datasource can be either a primary or supplemental datasource.
      String dataSource =
          DataSourceName.newBuilder()
              .setAccount(config.getAccountId().toString())
              .setDatasource(dataSourceId)
              .build()
              .toString();

      UpdateProductInputRequest request =
          UpdateProductInputRequest.newBuilder()
              .setUpdateMask(fieldMask)
              // You can only update product attributes and custom_attributes
              .setDataSource(dataSource)
              .setProductInput(
                  ProductInput.newBuilder()
                      .setName(name)
                      .setAttributes(attributes)
                      .addCustomAttributes(
                          CustomAttribute.newBuilder()
                              .setName("mycustomattribute")
                              .setValue("Example value")
                              .build())
                      .build())
              .build();

      System.out.println("Sending update ProductInput request");
      ProductInput response = productInputsServiceClient.updateProductInput(request);
      System.out.println("Updated ProductInput Name below");
      // The last part of the product name will be the product ID assigned to a product by Google.
      // Product ID has the format `channel~contentLanguage~feedLabel~offerId`
      System.out.println(response.getName());
      System.out.println("Updated Product below");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // An ID assigned to a product by Google. In the format
    // channel~contentLanguage~feedLabel~offerId
    String productId = "online~en~label~sku123"; // Replace with your product ID.

    // Identifies the data source that will own the product input.
    String dataSourceId = "{INSERT_DATASOURCE_ID}"; // Replace with your datasource ID.

    updateProductInput(config, productId, dataSourceId);
  }
}
// [END merchantapi_update_product_input]