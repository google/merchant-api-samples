# Google Merchant API C#/.NET Samples

This is a set of simple samples written in C#/.NET, which provide a minimal
example of Google Merchant API integration within a command line application.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. Development Environment

You will need a C# development environment such as:

*   [Visual Studio](https://www.visualstudio.com/)
*   [Rider](https://www.jetbrains.com/rider/)
*   [Visual Studio Code](https://code.visualstudio.com/) (with C# Dev Kit)
*   Or the [.NET SDK](https://dotnet.microsoft.com/download) installed on your
    system.

### 2. Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README.md` to
discover how to set up both authentication and the common sample configuration.

--------------------------------------------------------------------------------

## Running the Samples

All commands should be run from the `dotnet/` directory.

```bash
cd dotnet/
```

### 1. Restore NuGet Packages

Restore the dependencies for the projects (for more details, see
[.NET Package Restore](https://learn.microsoft.com/en-us/nuget/consume-packages/package-restore)):

```bash
dotnet restore
```

### 2. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

```bash
dotnet run --project examples/accounts/developerRegistration/v1/RegisterGcpSample.csproj --framework netcoreapp8.0
```

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 3. Run a Sample

To run a specific sample project, use `dotnet run --project` pointing to the
`.csproj` file. For example, to list your products, run:

```bash
dotnet run --project examples/products/v1/ListProductsSample.csproj --framework netcoreapp8.0
```

Examine your shell output, be inspired, and start working on an amazing new app!
