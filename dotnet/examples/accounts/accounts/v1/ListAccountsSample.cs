// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

// [START merchantapi_list_accounts]
using System;
using static MerchantApi.Authenticator;
using Google.Api.Gax;
using Google.Apis.Auth.OAuth2;
using Google.Apis.Auth.OAuth2.Flows;
using Google.Apis.Auth.OAuth2.Responses;
using Google.Apis.Http;
using Newtonsoft.Json;
using Google.Shopping.Merchant.Accounts.V1;


namespace MerchantApi
{
    public class ListAccountsSample
    {
        public void ListAccounts()
        {
            Console.WriteLine("=================================================================");
            Console.WriteLine("Listing all Accounts the user has access to");
            Console.WriteLine("=================================================================");

            // Authenticate using either oAuth or service account
            ICredential auth = Authenticator.Authenticate(
                MerchantConfig.Load(),
                // Passing the default scope for Merchant API: https://www.googleapis.com/auth/content
                AccountsServiceClient.DefaultScopes[0]);

            // Create client
            AccountsServiceSettings accountsServiceSettings = AccountsServiceSettings.GetDefault();

            // Create the AccountsServiceClient with the credentials
            AccountsServiceClientBuilder accountsServiceClientBuilder = new AccountsServiceClientBuilder
            {
                Credential = auth
            };
            AccountsServiceClient client = accountsServiceClientBuilder.Build();

            // Initialize request argument(s)
            ListAccountsRequest request = new ListAccountsRequest
            {
                PageSize = 1000, // Optional: specify the maximum number of accounts to return per page
            };

            // List all accounts the user has access to
            PagedEnumerable<ListAccountsResponse, Account> response = client.ListAccounts(request);

            // Print the paginated results. This automatically handles pagination
            // and retrieves all accounts in the account.
            foreach (ListAccountsResponse page in response.AsRawResponses())
            {

                Console.WriteLine("A page of results:");
                foreach (Account item in page)
                {
                    // Pretty print the accounts
                    Console.WriteLine(JsonConvert.SerializeObject(item, Formatting.Indented));

                }
            }
        }


        internal static void Main(string[] args)
        {
            var sample = new ListAccountsSample();
            sample.ListAccounts();
        }
    }
}
// [END merchantapi_list_accounts]