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

package shopping.merchant.samples.productstudio.v1alpha;

// [START merchantapi_generate_product_image_background]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.productstudio.v1alpha.GenerateImageBackgroundConfig;
import com.google.shopping.merchant.productstudio.v1alpha.GenerateProductImageBackgroundRequest;
import com.google.shopping.merchant.productstudio.v1alpha.GenerateProductImageBackgroundResponse;
import com.google.shopping.merchant.productstudio.v1alpha.ImageServiceClient;
import com.google.shopping.merchant.productstudio.v1alpha.ImageServiceSettings;
import com.google.shopping.merchant.productstudio.v1alpha.InputImage;
import com.google.shopping.merchant.productstudio.v1alpha.OutputImageConfig;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to create product images with generated backgrounds. */
public class GenerateProductImageBackgroundSample {

  private static String getName(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  public static void generateProductImageBackground(Config config, String imageUri)
      throws Exception {
    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    ImageServiceSettings imageServiceSettings =
        ImageServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    String name = getName(config.getAccountId().toString());

    // Calls the API and catches and prints any network failures/errors.
    try (ImageServiceClient imageServiceClient = ImageServiceClient.create(imageServiceSettings)) {

      OutputImageConfig outputImageConfig =
          // Set this field to false to return the image bytes in the response instead.
          OutputImageConfig.newBuilder().setReturnImageUri(true).build();

      InputImage inputImage =
          InputImage.newBuilder()
              // You can also use image bytes here instead of a URI.
              .setImageUri(imageUri)
              .build();

      GenerateImageBackgroundConfig generateImageBackgroundConfig =
          GenerateImageBackgroundConfig.newBuilder()
              .setProductDescription("a jar")
              .setBackgroundDescription(
                  "sitting on a cracked stone surface surrounded by a cherry blossom tree and pink"
                      + " and white flowers in the background, high resolution, product"
                      + " photography, strong shadows and lights, creative")
              .build();

      GenerateProductImageBackgroundRequest request =
          GenerateProductImageBackgroundRequest.newBuilder()
              .setName(name)
              .setOutputConfig(outputImageConfig)
              .setInputImage(inputImage)
              .setConfig(generateImageBackgroundConfig)
              .build();

      System.out.println("Sending GenerateProductImageBackground request: " + name);
      GenerateProductImageBackgroundResponse response =
          imageServiceClient.generateProductImageBackground(request);
      System.out.println("Generated product image background response below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occurred: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // Replace with your image URI.
    String imageUri =
        "https://services.google.com/fh/files/misc/abundance_intention_bath_salts.jpg";
    generateProductImageBackground(config, imageUri);
  }
}

// [END merchantapi_generate_product_image_background]
