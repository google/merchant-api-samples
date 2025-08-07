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

// [START merchantapi_register_gcp]
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
    public class RegisterGcpSample
    {
        public void RegisterGcp(string developerEmail)
        {
            // Registers a specific developer's email address on the current GCP project
            Console.WriteLine("=================================================================");
            Console.WriteLine("Calling the RegisterGcp method");
            Console.WriteLine("=================================================================");

            // Authenticate using either oAuth or service account
            ICredential auth = Authenticator.Authenticate(
                MerchantConfig.Load(),
                // Passing the default scope for Merchant API: https://www.googleapis.com/auth/content
                AccountsServiceClient.DefaultScopes[0]);

            // Create the DeveloperRegistrationServiceClient with the credentials
            DeveloperRegistrationServiceClientBuilder DeveloperRegistrationServiceClientBuilder = new DeveloperRegistrationServiceClientBuilder
            {
                Credential = auth
            };
            DeveloperRegistrationServiceClient client = DeveloperRegistrationServiceClientBuilder.Build();

            // The name of the developer registration resource.
            // The format is: accounts/{merchantId}/developerRegistration
            // where {merchantId} is the ID of the merchant account.
            string name = "accounts/" + MerchantConfig.Load().MerchantId + "/developerRegistration";

            // Initialize request argument(s)
            RegisterGcpRequest request = new RegisterGcpRequest
            {
                Name = name,
                // Specify the email address of the developer to register
                DeveloperEmail = developerEmail
            };

            // Call the RegisterGcp method
            DeveloperRegistration response = client.RegisterGcp(request);
            Console.WriteLine(JsonConvert.SerializeObject(response, Formatting.Indented));
        }

        internal static void Main(string[] args)
        {
            var registration = new RegisterGcpSample();
            string developerEmail = "YOUR_EMAIL";
            registration.RegisterGcp(developerEmail);
        }
    }
}
// [END merchantapi_register_gcp]