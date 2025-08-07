# Google Merchant API C#/.NET Samples

This is a set of simple samples written in C#/.NET, which provide a minimal
example of Google Merchant API integration within a command line application.

## Prerequisites

Please make sure that you're using either

* [Visual Studio](https://www.visualstudio.com/),
* [Xamarin Studio](https://www.xamarin.com/studio), or
* [MonoDevelop](http://www.monodevelop.com/)

with [NuGet package management](https://www.nuget.org/).
NuGet will handle getting all the dependencies for you with the package
configuration in these projects.

## Setup Authentication and Sample Configuration

If you have not already done so, please read the top-level `README` to discover
how to set up both authentication and the common sample configuration. The rest
of this document assumes you have performed both tasks.

## Running the Samples

We are assuming you've checked out the code and are reading this from a local
directory. If not, check out the code to a local directory and load up the
solution. You will need to
[Restore NuGet Packages](https://docs.nuget.org/ndocs/consume-packages/package-restore)
as well to pull in the dependencies. (The IDEs listed above all support this.)

Use the following syntax to run the `ListProductsSample` project, for example.

1. Open a command line and navigate to the samples' root directory (dotnet/)

2. Run
```
dotnet run --project examples/products/v1beta/ListProductsSample.csproj --framework netcoreapp8.0
```

Examine your shell output, be inspired and start working on an amazing new app!

We hope these samples give you the inspiration needed to create your new
application!