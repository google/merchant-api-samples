package shopping.merchant.samples.accounts.developerregistration.v1;

// [START merchantapi_unregister_gcp]
import com.google.api.gax.core.FixedCredentialsProvider;
import com.google.auth.oauth2.GoogleCredentials;
import com.google.shopping.merchant.accounts.v1.DeveloperRegistrationName;
import com.google.shopping.merchant.accounts.v1.DeveloperRegistrationServiceClient;
import com.google.shopping.merchant.accounts.v1.DeveloperRegistrationServiceSettings;
import com.google.shopping.merchant.accounts.v1.UnregisterGcpRequest;
import shopping.merchant.samples.utils.Authenticator;
import shopping.merchant.samples.utils.Config;

/**
 * This class demonstrates how to unregister the GCP project currently used to call the Merchant API
 * for a specific account.
 */
public class UnregisterGcpSample {

  public static void unregisterGcp(Config config) throws Exception {

    // Obtains OAuth token based on the user's configuration.
    GoogleCredentials credential = new Authenticator().authenticate();

    // Creates service settings using the credentials retrieved above.
    DeveloperRegistrationServiceSettings developerRegistrationServiceSettings =
        DeveloperRegistrationServiceSettings.newBuilder()
            .setCredentialsProvider(FixedCredentialsProvider.create(credential))
            .build();

    // Creates DeveloperRegistration name to identify the DeveloperRegistration.
    // The name has the format: accounts/{account}/developerRegistration
    String name =
        DeveloperRegistrationName.newBuilder()
            .setAccount(config.getAccountId().toString())
            .build()
            .toString();

    // Calls the API and propagates any network failures/errors.
    try (DeveloperRegistrationServiceClient developerRegistrationServiceClient =
        DeveloperRegistrationServiceClient.create(developerRegistrationServiceSettings)) {

      // Creates a request to unregister the GCP project.
      UnregisterGcpRequest request = UnregisterGcpRequest.newBuilder().setName(name).build();

      System.out.println("Sending UnregisterGcp request for: " + name);
      developerRegistrationServiceClient.unregisterGcp(request);
      System.out.println("Unregister GCP successful.");
    }
  }

  public static void main(String[] args) throws Exception {
    Config config = Config.load();
    unregisterGcp(config);
  }
}
// [END merchantapi_unregister_gcp]
