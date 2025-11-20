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

// Package v1 provides examples for retrieving account information using the Merchant API.
package v1

// [START merchantapi_get_account]

import (
	"context"
	"fmt"
	"log"

	"google3/third_party/golang/google_api/merchantapi/accounts/v1beta/merchantapi"
	"google.golang.org/api/option"
	"github.com/google/merchant-api-samples/go/collection"
	"github.com/google/merchant-api-samples/go/examples/googleauth"
)

// The resource name of the account to retrieve.
// Replace "1234567890" with your account ID.
const name = "accounts/1234567890"

type getAccount struct{}

func init() {
	// Use the new Add function from the collection package
	if err := collection.Add("accounts.accounts.v1.get_account", &getAccount{}); err != nil {
		log.Fatalf("could not add example: %v", err)
	}
}

func (s *getAccount) Description() string {
	return "Sample retrieves specific account by Merchant ID."
}

func (s *getAccount) Execute() error {
	ctx := context.Background()

	client, err := googleauth.AuthWithGoogle(ctx)
	if err != nil {
		return fmt.Errorf("Failed to authenticate: %w", err)
	}

	// Create a new Merchant API service using the service account credentials.
	merchantapiService, err := merchantapi.NewService(ctx, option.WithHTTPClient(client))
	if err != nil {
		return fmt.Errorf("Unable to create Merchant API service with credentials file: %w", err)
	}

	// Call the Get method of the Accounts service.
	account, err := merchantapiService.Accounts.Get(name).Do()
	if err != nil {
		return fmt.Errorf("Unable to retrieve account: %w", err)
	}

	// Print the account information.
	fmt.Printf("Successfully retrieved account: %+v\n", account)
	return nil
}

// [END merchantapi_get_account]
