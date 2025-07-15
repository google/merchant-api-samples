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

// [START merchantapi_upscale_product_image]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.productstudio.v1alpha.ImageServiceClient;
import com.google.shopping.merchant.productstudio.v1alpha.ImageServiceSettings;
import com.google.shopping.merchant.productstudio.v1alpha.InputImage;
import com.google.shopping.merchant.productstudio.v1alpha.OutputImageConfig;
import com.google.shopping.merchant.productstudio.v1alpha.UpscaleProductImageRequest;
import com.google.shopping.merchant.productstudio.v1alpha.UpscaleProductImageResponse;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/** This class demonstrates how to create upscaled product images. */
public class UpscaleProductImageSample {

  private static String getName(String accountId) {
    return String.format("accounts/%s", accountId);
  }

  public static void upscaleProductImage(Config config, String imageUri) throws Exception {
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

      UpscaleProductImageRequest request =
          UpscaleProductImageRequest.newBuilder()
              .setName(name)
              .setOutputConfig(outputImageConfig)
              .setInputImage(inputImage)
              .build();

      System.out.println("Sending UpscaleProductImage request: " + name);
      UpscaleProductImageResponse response = imageServiceClient.upscaleProductImage(request);
      System.out.println("Upscaled product image response below:");
      System.out.println(response);
    } catch (Exception e) {
      System.out.println("An error has occurred: ");
      System.out.println(e);
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    // Replace with your image URI.
    String imageUri = "https://services.google.com/fh/files/misc/ring_image_400_600.jpg";
    upscaleProductImage(config, imageUri);
  }
}

// [END merchantapi_upscale_product_image]
