# Google Merchant API Go Samples

This is a set of simple samples written in Go, which provide a minimal example
of Google Shopping integration within a command line application.

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. Install Dependencies

Navigate to the `golang/` directory and download/install the necessary
libraries:

```bash
cd golang/
go mod tidy
```

### 2. Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README.md` to
discover how to set up the authentication configuration.

--------------------------------------------------------------------------------

## Running the Samples

All commands should be run from the `golang/` directory.

### 1. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

```bash
go run . accounts.developerregistration.v1.register_gcp
```

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 2. List Available Samples

To see a list of all runnable code samples, run the program without any
arguments:

```bash
go run .
```

### 3. Run a Specific Sample

To execute a specific sample, provide its registered name as a command-line
argument. For example, to run the "get account" sample, run:

```bash
go run . accounts.accounts.v1.get_account
```
