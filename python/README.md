# Google Merchant API Python Samples

This is a set of simple samples written in Python, which provide a minimal
example of Google Shopping integration within a command line application.

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. Supported Python Versions

*   Python >= 3.8

### 2. Installation & Virtual Environment

We recommend using a virtual environment to install the required dependencies.
Run the following commands from the repository root:

```bash
# Navigate to the python directory
cd python/

# Create a virtual environment named 'venv'
python3 -m venv venv

# Activate the virtual environment
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt
```

### 3. Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README.md` to
discover how to set up the authentication configuration.

--------------------------------------------------------------------------------

## Running the Samples

All commands should be run from the `python/` directory.

### 1. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

```bash
python -m examples.accounts.developerregistration.v1.register_gcp_sample
```

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 2. Run a Sample

To run a specific sample, use the `python -m` syntax. For example, to list your
products, run:

```bash
python -m examples.products.v1.list_products_sample
```

### 3. Running Alpha Samples (Optional)

For instructions on how to install the required alpha client libraries and run
the alpha samples (located in `v1alpha` subdirectories), please refer to the
[alpha client repository](https://github.com/google/merchant-api-alpha-client).

--------------------------------------------------------------------------------

Examine your shell output, be inspired, and start hacking an amazing new app!
