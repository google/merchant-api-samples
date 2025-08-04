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

1.  Create the Apps Script project [here](https://script.google.com/u/1/home) or re-use an existing one.

1.  Go into "Project Settings" and select the "Show the appsscript.json manifest file in editor"

1.  Go back to the "Editor" and copy/paste the content of this repository's appsscript.json into the default one. Select the one for the desired API version (for example appscript_v1.json).
    *   If you are using a new project, you can overwrite everything.
    *   If you are using an existing project, you should merge the content of this file with your existing one.

1.  Copy/paste the selected sample code into your Apps Script project (in the "code.gs" file).

1.  Click "Run" to execute the script. You will be prompted to authorize the script using your own credentials

1.  Examine your shell output, be inspired and start hacking an amazing new app!