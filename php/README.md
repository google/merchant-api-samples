# Google Merchant API PHP Samples

This is a set of simple samples written in PHP, which provide a minimal example
of Google Shopping integration within a command line application. These samples
are written to be run as a command line application, not as a webpage.

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. Install Dependencies

A [Composer](https://getcomposer.org/) configuration has been included for
dependency management. Run the following commands from the repository root to
install the necessary dependencies:

```bash
cd php/
composer install
```

### 2. Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README.md` to
discover how to set up authentication on your local machine.

If you are using OAuth 2.0 Client IDs, you must generate a refresh token before
running the samples. Run the following commands from the `php/` directory:

```bash
php examples/Authentication/GenerateUserCredentials.php
```

This will guide you through the authorization flow and store your credentials in
`token.json` on your local machine (within your configuration directory).

--------------------------------------------------------------------------------

## Running the Samples

All commands should be run from the `php/` directory.

### 1. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

```bash
php examples/accounts/developerregistration/v1/RegisterGcpSample.php
```

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 2. Run a Sample

Once registered, you can run any of the included samples. For example, to list
your products, run:

```bash
php examples/products/v1/ListProductsSample.php
```

### 3. Running Alpha Samples (Optional)

For instructions on how to install the required alpha client libraries and run
the alpha samples (located in `v1alpha` subdirectories), please refer to the
[alpha client repository](https://github.com/google/merchant-api-alpha-client).

--------------------------------------------------------------------------------

Examine your shell output, be inspired, and start hacking an amazing new app!
