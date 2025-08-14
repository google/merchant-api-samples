// Copyright 2025 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

// [START merchantapi_list_aggregate_product_statuses]
using System;
using static MerchantApi.Authenticator;
using Google.Api.Gax;
using Google.Apis.Auth.OAuth2;
using Google.Apis.Auth.OAuth2.Flows;
using Google.Apis.Auth.OAuth2.Responses;
using Google.Apis.Http;
using Newtonsoft.Json;
using Google.Shopping.Merchant.IssueResolution.V1;

namespace MerchantApi
{
    /// <summary>
    /// This class demonstrates how to list aggregate product statuses.
    /// </summary>
    public class ListAggregateProductStatusesSample
    {
        public static void ListAggregateProductStatuses()
        {
            // Authenticate using either oAuth or service account
            ICredential auth = Authenticator.Authenticate(
                    MerchantConfig.Load(),
                    // Passing the default scope for Merchant API: https://www.googleapis.com/auth/content
                    AggregateProductStatusesServiceClient.DefaultScopes[0]);

            // Creates the service client.
            var client = new AggregateProductStatusesServiceClientBuilder
            {
                Credential = auth
            }.Build();

            // Calls the API and catches and prints any network failures/errors.
            try
            {
                var request = new ListAggregateProductStatusesRequest
                {
                    // The parent resource for the request.
                    ParentAsAccountName = AccountName.FromAccount(MerchantConfig.Load().MerchantId.ToString()),
                    Filter = "country = \"US\"" // Optionally set a filter.
                };

                Console.WriteLine("Sending list aggregate product statuses request");
                PagedEnumerable<ListAggregateProductStatusesResponse, AggregateProductStatus> response =
                    client.ListAggregateProductStatuses(request);

                int count = 0;

                // Iterates over all rows in all pages and prints the datasource in each row.
                // Automatically uses the `nextPageToken` if returned to fetch all pages of data.
                foreach (var aggregateProductStatus in response)
                {
                    Console.WriteLine("Printing response:");
                    Console.WriteLine(aggregateProductStatus);
                    count++;
                }
                Console.Write("The following count of aggregate product statuses were returned: ");
                Console.WriteLine(count);
            }
            catch (Exception e)
            {
                Console.WriteLine("An error has occurred:");
                Console.WriteLine(e);
            }
        }

        public static void Main(string[] args)
        {
            ListAggregateProductStatuses();
        }
    }
}
// [END merchantapi_list_aggregate_product_statuses]