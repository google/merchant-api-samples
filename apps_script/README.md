# Google Merchant API for Shopping Apps Script Samples

This is a set of simple samples written in Apps Script, which provide a minimal
example of Google Shopping integration within a Google Workspace Apps Script
environment.

This starter project provides a great place to start your experimentation into
the Google Merchant API for Shopping.

--------------------------------------------------------------------------------

## Prerequisites for Running

### 1. Create Apps Script Project

Create a new Apps Script project at
[script.google.com](https://script.google.com/) or re-use an existing one.
Ensure your project is using the **V8 runtime** (enabled by default in new
projects).

By default, Apps Script automatically creates and manages a default Google Cloud
Platform (GCP) project for your script. While this is sufficient for quick
testing, we recommend creating and linking a
[standard GCP project](https://developers.google.com/apps-script/guides/cloud-platform-projects#standard_cloud_platform_projects)
for more complex or advanced setups.

### 2. Enable Merchant API Advanced Service

You must enable the Merchant API in your Apps Script project to use the Advanced
Services. You can do this in one of two ways:

#### Option A: Enable via manifest file `appsscript.json` (Recommended)

This method allows you to enable all required services at once by copying the
configuration:

1.  Open your Apps Script project.
2.  At the left, click **Project Settings** (gear icon).
3.  Check the box for **Show "appsscript.json" manifest file in editor**.
4.  At the left, click **Editor** (`< >`). You should now see `appsscript.json`
    in the file list.
5.  Open `appsscript.json` and replace its content with the contents of the
    `appsscript_v1.json` file located in the `apps_script/` directory.

#### Option B: Enable via the Apps Script UI

1.  Open your Apps Script project.
2.  At the left, click **Editor** (`< >`).
3.  Next to **Services**, click **Add a service** (`+`).
4.  Select a Google Merchant API service (e.g., `merchantapi`), rename it to
    correspond with the code samples (for example `MerchantApiInventories`) and
    click **Add**.
5.  Repeat for other required services (Accounts, Products, Reports,
    DataSources).

For more detailed instructions, see the official
[Google Merchant API Service guide](https://developers.google.com/apps-script/advanced/merchant-api).

--------------------------------------------------------------------------------

## Setup Authentication

Apps Script uses **OAuth 2.0** with the credentials of the logged-in user and
the GCP project associated with your script. Service Account authentication is
not supported.

When you run the script, Apps Script will automatically prompt you to authorize
the necessary scopes using your Google Account. You do not need to configure
credentials files locally.

--------------------------------------------------------------------------------

## Running the Samples

### 1. Developer Registration

Before calling any `v1` Merchant API method, you must register the GCP project
used to call the APIs. You only need to do this once.

1.  In your Apps Script project, create a new script file (e.g., `Register.gs`).
2.  Copy the code from
    `examples/accounts/developerRegistration/v1/register_gcp_sample.gs` in this
    repository and paste it into the new file.
3.  Replace the placeholders `<ACCOUNT_ID>` and `<YOUR_EMAIL>` in the code with
    your actual Merchant Center ID and email address.
4.  In the editor's toolbar, select the `registerDeveloper` function from the
    dropdown.
5.  Click **Run** and authorize the script when prompted.

For more information, see
[Register as a developer](https://developers.google.com/merchant/api/guides/quickstart#register_as_a_developer).

### 2. Copy the Sample Code

1.  Select a sample file you want to run from this repository (e.g.,
    `examples/products/v1/list_products_sample.gs`).
2.  In your Apps Script project, create a new script file (e.g., `Code.gs`).
3.  Copy the entire content of the sample file and paste it into your script
    file.
4.  Replace placeholders like `<MERCHANT_CENTER_ID>` with your actual Merchant
    Center ID.

### 3. Run the Sample Function

1.  In the editor's toolbar, select the main function of the sample you want to
    run (e.g., `productList` for `list_products_sample.gs`) from the function
    dropdown.
2.  Click **Run**.
3.  View the execution logs at the bottom of the editor to see the results.
