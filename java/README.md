# Google Merchant API Java Samples

This is a set of simple samples written in Java, which provide a minimal example
of Google Shopping integration within a command line application.

This starter project provides a great place to start your experimentation into
the Merchant API.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. System Requirements

*   **Java**: Java 8+ is required.
*   **Maven**: Used for dependency management and running the samples.

### 2. Build the Project

Navigate to the `java/` directory and build/compile the project:

```bash
cd java/
mvn compile
```

### 3. Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README.md` to
discover how to set up both authentication and the common sample configuration.

--------------------------------------------------------------------------------

## Running the Samples

All commands should be run from the `java/` directory.

### 1. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

```bash
mvn exec:java -Dexec.mainClass="shopping.merchant.samples.accounts.developerregistration.v1.RegisterGcpSample"
```

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 2. Run a Sample

To run a specific sample, use `mvn exec:java` and specify the main class. For
example, to list your products, run:

```bash
mvn exec:java -Dexec.mainClass="shopping.merchant.samples.products.v1.ListProductsSample"
```

### 3. Building and Running Alpha Samples (Optional)

By default, alpha samples (such as reviews) are excluded from the build because
they require alpha client libraries that are not installed by default.

To compile and run alpha samples:

1.  Install the alpha client library on your local machine by following the
    instructions in the
    [alpha client repository](https://github.com/google/merchant-api-alpha-client).
2.  Build the project using the `allow-alpha` profile:

```bash
mvn compile -Pallow-alpha
```

Use the same profile when executing alpha samples. For example:

```bash
mvn exec:java -Dexec.mainClass="shopping.merchant.samples.reviews.v1alpha.ListProductReviewsSample" -Pallow-alpha
```

--------------------------------------------------------------------------------

Examine your shell output, be inspired, and start working on an amazing new app!
