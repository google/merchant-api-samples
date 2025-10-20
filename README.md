# Samples for Merchant API

These code samples are organized by platform or language. Each language
directory contains a `README` with more information about how to run the
samples for that particular language. Here, we cover setting up
authentication and the common configuration file used by all the samples.

For more information on the API, please refer to the documentation for the
[Merchant API](https://developers.google.com/merchant/api/overview).

The Merchant API is a redesign of the Content API for Shopping.

## Prerequisites

Before getting started with new integration, you need to complete following
steps:

1.  [Enable Merchant API in your Google Cloud project](https://developers.google.com/merchant/api/guides/quickstart#before-you-begin)
2.  [Create Merchant Center account](https://developers.google.com/merchant/api/guides/quickstart#create-account)

## Choose your Authentication method

Setting up authentication for the Merchant API is similar to the Content API.
Merchant API supports 3 types of authentication flows based on your
specific use case:

1.  [Service account to access your own account](https://developers.google.com/merchant/api/guides/authorization/access-your-account)
2.  [OAuth 2.0 to access your merchants' accounts](https://developers.google.com/merchant/api/guides/authorization/access-client-accounts)
3.  [Google Application Default
    Credentials](https://developers.google.com/identity/protocols/application-default-credentials)

## Setting up Authentication and Sample Configuration

1.  Create the directory `$(HOME)/shopping-samples/content` to store the
    configuration.

    If you are unsure where this will be located in your particular setup,
    run the samples (following the language-specific `README`). Errors from
    the samples related to either this directory or necessary files not
    existing will provide the full path to the expected directory/files.

2.  Set up your desired authentication method (when multiple methods are set up,
    the service account will be prioritized):

    1.  **To use Google Application Default Credentials**
        1.  follow the directions on the
            [Google Application Default Credentials](https://developers.google.com/identity/protocols/application-default-credentials)
            page.
    2.  **To use a service account:**

        1.  [Create a service account](https://cloud.google.com/iam/docs/service-accounts-create#creating)
        1.  [Create and download a service account key](https://cloud.google.com/iam/docs/keys-create-delete#iam-service-account-keys-create-console)
            in JSON format.
        1.  Rename the JSON file you downloaded to `service-account.json` and
            move it to the configuration subdirectory
            `$(HOME)/shopping-samples/content`.
        1.  Add the service account as a user of your Merchant Center account
            using the "People and access" page

    3.  **to use an OAuth2 client ID:**

        1.  Register your application and
            [generate OAuth 2.0 Client ID](https://developers.google.com/merchant/api/guides/authorization/access-client-accounts#OAuth2Authorizing).
        1.  Download your
            [OAuth2 client credentials](https://console.developers.google.com/apis/credentials)
            to the file `client-secrets.json` in the configuration
            subdirectory `$(HOME)/shopping-samples/content`.

> [!IMPORTANT]
> The samples assume that you are using an OAuth2 client ID that can
> use a loopback IP address to retrieve tokens. For web app clients
> types, you must add "http://127.0.0.1:8080" to the "Authorized
> redirect URIs" list in your Google Cloud Console project before
> running samples. Please visit the
> [OAuth2 for Web Apps](https://developers.google.com/identity/protocols/oauth2/web-server)
> page and follow the instructions there to create a new OAuth2 client
> ID to use with the samples.

> [!NOTE]
> If using OAuth2 client credentials, once you have authorized access,
> your token details will be stored in the `token.json` file in the
> samples configuration directory. If you have any issues
> authenticating, remove this file and you will be asked to
> re-authorize access.

3.  Take the example `merchant-info.json` from this repository root and copy
    it into `$(HOME)/shopping-samples/content`. Next, change its contents
    appropriately. It contains a JSON object with the following field:

    | Field                     | Type   | Description                                    |
    |---------------------------|--------|------------------------------------------------|
    | `merchantId`              | number | The Merchant Center ID to run samples against. |

## Try Out the Samples

Now that you've configured both the common sample configuration file and set
up your authentication credentials, it's time to build and run any of the
included samples. As mentioned before, there are language-specific
instructions in the `README`s located in each language subdirectory.

> [!IMPORTANT]
> Before you can run any other Merchant API calls, you need to perform
> Developer registration API call once. You can do this by calling REST API
> directly or using your chosen client library and provided code sample.
> See
> [Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

## Code Assist Toolkit using MCP

Get assistance with migrating from Content API for Shopping to Merchant API or
developing new integrations from the Model Context Protocol (MCP) service. It
provides authoritative context from official Merchant API documentation and code
samples. You can easily integrate MCP into coding assistant of your choice. For
more information, see [MAPI Integration and Code
Assist Toolkit using
MCP](https://developers.google.com/merchant/api/guides/devdocs-mcp).

We also provide [MCP instructions](devdocs_mcp_instructions.md) you can use to
instruct your coding assistant on how to migrate and use MCP.
