# Google Merchant API Go Samples

This is a set of simple samples written in Go, which provide a minimal example
of Google Shopping integration within a command line application.

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

## Prerequisites

Navigate to the root of the go directory in your terminal and run `go mod tidy`.
 This will download and install all the necessary libraries listed in `go.mod`.

```bash
go mod tidy
```

## Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README` to discover
how to set up the authentication configuration. The rest of this document
assumes you have performed both tasks.

## Running the Samples

You can run the application from the root of the project directory.

### Listing Available Samples

To see a list of all runnable code samples, run the program without any
arguments:

```bash
go run .
```

### Running a Specific Sample

To execute a specific sample, provide its name as a command-line argument.

```bash
go run . accounts.accounts.v1.get_account
```
