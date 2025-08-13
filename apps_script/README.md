# Google Merchant API for Shopping Apps Script Samples

This is a set of simple samples written in Apps Script, which provide a minimal
example of Google Shopping integration within an Google Workspace Apps Script
environment ([read more](https://developers.google.com/apps-script/overview)).

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

## Prerequisites

* An Apps Script project with using runtime v8

## Setup Authentication

Apps Script uses oAuth authentication only (Service Account authentication is
not supported). This means that the samples will use the logged-in user
credentials to authorize the Merchant API requests

## Running the Samples

Before calling any `v1` Merchant API method, you will need to register the GCP
project used to call the APIs. You can do that by running the code sample
`examples/accounts/developerRegistration/v1/register_gcp_sample.gs`.
More informations can be found [here](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

1.  Create the Apps Script project [here](https://script.google.com/u/1/home) or re-use an existing one.

1.  Follow [these steps](https://developers.google.com/apps-script/advanced/merchant-api) to enable the Merchant API in the Apps Script project.

1.  Click "Run" to execute the script. You will be prompted to authorize the script using your own credentials

1.  Examine your shell output, be inspired and start hacking an amazing new app!