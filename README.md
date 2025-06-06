# Samples for Merchant API

These code samples are organized by platform or language. Each language
directory contains a `README` with more information about how to run the
samples for that particular language. Here, we cover setting up
authentication and the common configuration file used by all the samples.

For more information on the API, please refer to the documentation for the
[Merchant API](https://developers.google.com/merchant/api/overview).

The Merchant API is a redesign of the Content API for Shopping.

## Choose Your Method of Authentication

Before getting started, check the Getting Started section of the
[Merchant API documentation](https://developers.google.com/merchant/api/guides/quickstart).
You may want to use
[service accounts](https://developers.google.com/merchant/api/guides/authorization/access-your-account)
instead to simplify the authentication flow. These samples also support using
[Google Application Default Credentials](https://developers.google.com/identity/protocols/application-default-credentials).

Setting up authentication for the Merchant API is similar to the Content API.
Just make sure to enable the Merchant API in the API Console.

## Setting up Authentication and Sample Configuration

1.  Create the directory `$(HOME)/shopping-samples` to store the
    configuration.

    If you are unsure where this will be located in your particular setup, then
    run the samples (following the language-specific `README`). Errors
    from the samples related to either this directory or necessary files not
    existing will provide the full path to the expected directory/files.

    Within this directory, also create the following subdirectory:

    *   `content` for the Merchant API

    Place the files described below in the subdirectory.

2.  Set up your desired authentication method.

    If you want to use Google Application Default Credentials:

    *   Follow the directions on the [Google Application Default
        Credentials](https://developers.google.com/identity/protocols/application-default-credentials)
        page.

    If you want to use a service account:

    1.  [Create a service account](https://cloud.google.com/iam/docs/service-accounts-create#creating)
    1.  [Create and download a service account key](https://cloud.google.com/iam/docs/keys-create-delete#iam-service-account-keys-create-console)
        in JSON format.
    1.  Rename the JSON file you downloaded to `service-account.json` and move
        it to the configuration subdirectory.

    If you want to use an OAuth2 client ID:

    1.  Register your application and
        [generate OAuth 2.0 Client ID](https://developers.google.com/merchant/api/guides/authorization/access-client-accounts#OAuth2Authorizing).
    1.  Download your
        [OAuth2 client credentials](https://console.developers.google.com/apis/credentials)
        to the file `client-secrets.json` in the configuration subdirectory.

        > The samples assume that you are using an OAuth2 client ID that can
        > use a loopback IP address to retrieve tokens. For web app clients
        > types, you must add "http://127.0.0.1:8080" to the "Authorized
        > redirect URIs" list in your Google Cloud Console project before
        > running samples. Please visit the
        > [OAuth2 for Web Apps](https://developers.google.com/identity/protocols/oauth2/web-server)
        > page and follow the instructions there to create a new OAuth2 client
        > ID to use with the samples.

    You can set up multiple authentication methods to try out different flows,
    but note that the samples will always use the first credentials that can be
    loaded, in the order:

    1.  [Service accounts](https://developers.google.com/merchant/api/guides/authorization/access-your-account)
        credentials
    2.  [OAuth2 client](https://developers.google.com/merchant/api/guides/authorization/access-client-accounts)
        credentials

3.  Take the example `merchant-info.json` from the repository root and copy it
    into `$(HOME)/shopping-samples/content`. Next, change its contents
    appropriately. It contains a JSON object with the following field:

    | Field                     | Type   | Description                                    |
    |---------------------------|--------|------------------------------------------------|
    | `merchantId`              | number | The Merchant Center ID to run samples against. |

    If using OAuth2 client credentials, once you have authorized access, your
    token details will be stored in the `token.json` file in the samples
    configuration directory. If you have any issues authenticating, remove this
    file and you will be asked to re-authorize access.

## Try Out the Samples

Now that you've configured both the common sample configuration file and set up
your authentication credentials, it's time to build and run any of the included
samples. As mentioned before, there are language-specific instructions in the
`README`s located in each language subdirectory.
