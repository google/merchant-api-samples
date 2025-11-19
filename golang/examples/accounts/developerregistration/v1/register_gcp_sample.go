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

// Package v1 provides examples for registering a GCP project with a developer email using the Merchant API.
package v1

// [START merchantapi_register_gcp]
import (
	"context"
	"fmt"
	"log"

	"google.golang.org/api/merchantapi/accounts/v1"
	"google.golang.org/api/option"
	"github.com/google/merchant-api-samples/go/collection"
	"github.com/google/merchant-api-samples/go/examples/googleauth"
)

// The Merchant Center account ID.
// Replace "1234567890" with your account ID.
const accountIDForRegisterGcp = "1234567890"

// The email of the developer to register.
// Replace with your email.
const developerEmailForRegisterGcp = "YOUR_EMAIL_HERE"

type registerGcpSample struct{}

func init() {
	// Use the new Add function from the collection package
	if err := collection.Add("accounts.developerregistration.v1.register_gcp", &registerGcpSample{}); err != nil {
		log.Fatalf("could not add example: %v", err)
	}
}

func (s *registerGcpSample) Description() string {
	return "Registers the GCP project with a developer email."
}

func (s *registerGcpSample) Execute() error {
	ctx := context.Background()

	// Authenticates with Google using the golang authentication library.
	tokenSource, err := googleauth.AuthWithGoogle(ctx)
	if err != nil {
		return fmt.Errorf("failed to authenticate: %w", err)
	}

	// Creates a new Merchant API service.
	merchantapiService, err := merchantapi.NewService(ctx, option.WithTokenSource(tokenSource))
	if err != nil {
		return fmt.Errorf("unable to create Merchant API service: %w", err)
	}

	// Constructs the name of the developer registration resource.
	// Format: accounts/{account}/developerRegistration
	name := fmt.Sprintf("accounts/%s/developerRegistration", accountIDForRegisterGcp)

	// Creates the request to register the GCP project with the developer email.
	req := &merchantapi.RegisterGcpRequest{
		DeveloperEmail: developerEmailForRegisterGcp,
	}

	fmt.Println("Sending RegisterGcp request:")
	// Calls the RegisterGcp method of the DeveloperRegistration service.
	response, err := merchantapiService.Accounts.DeveloperRegistration.RegisterGcp(name, req).Do()
	if err != nil {
		return fmt.Errorf("unable to register GCP project: %w", err)
	}

	// Prints the response from the API.
	fmt.Printf("Received response: %+v\n", response)
	return nil
}

// [END merchantapi_register_gcp]
