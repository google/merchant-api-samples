# Google Merchant API Node.js Samples

This is a set of simple samples written in Node.js, which provide a minimal
example of Google Shopping integration within a command line application.

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. System Requirements

*   Node.js >= 18

### 2. Install Dependencies

Run the following commands from the repository root to install all needed
dependencies:

```bash
cd nodejs/
npm install
```

### 3. Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README.md` to
discover how to set up the authentication configuration.

--------------------------------------------------------------------------------

## Running the Samples

All commands should be run from the `nodejs/` directory.

### 1. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

```bash
node examples/accounts/developerregistration/v1/register_gcp_sample.js
```

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 2. Run a Sample

To run a specific sample, use `node` followed by the path to the file. For
example, to list your products, run:

```bash
node examples/products/v1/list_products_sample.js
```

Examine your shell output, be inspired, and start hacking an amazing new app!
